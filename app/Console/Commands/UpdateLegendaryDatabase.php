<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use SQLite3;

class UpdateLegendaryDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'legendary:update-database
                          {--dry-run : Show what would be updated without making changes}
                          {--force : Skip confirmation prompts}
                          {--local : Update local database instead of remote}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Legendary Randomizer database with latest master-strike data, including duplicate entities';

    /**
     * Configuration
     */
    private $scriptDir;
    private $masterStrikeDir;
    private $sqliteFile;
    private $authToken = null;
    private $apiBaseUrl;

    public function __construct()
    {
        parent::__construct();

        $this->scriptDir = base_path('master-strike-parse');
        $this->masterStrikeDir = $this->scriptDir . '/master-strike';
        $this->sqliteFile = $this->scriptDir . '/legendary-randomizer-corrected.sqlite';
        $this->apiBaseUrl = config('legendary.api_base_url', env('LEGENDARY_API_BASE_URL'));
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Legendary Randomizer Database Update');
        $this->info('==========================================');
        $this->newLine();

        // Check if scripts directory exists
        if (!is_dir($this->scriptDir)) {
            $this->error('❌ Scripts directory not found: ' . $this->scriptDir);
            return 1;
        }

        // Determine update mode
        $isLocalUpdate = $this->option('local') || empty($this->apiBaseUrl);

        if ($isLocalUpdate) {
            $this->info('📍 Local database update mode');
        } else {
            $this->info('🌐 Remote database update mode');
            $this->line('API Base URL: ' . $this->apiBaseUrl);
        }

        try {
            // Step 1: Clone repository
            if (!$this->cloneRepository()) {
                return 1;
            }

            // Step 2: Extract and process data
            if (!$this->extractData()) {
                return 1;
            }

            // Step 3: Create SQLite database
            if (!$this->createSqliteDatabase()) {
                return 1;
            }

            // Step 4: Update database (local or remote)
            if ($isLocalUpdate) {
                if (!$this->updateMariaDB()) {
                    return 1;
                }
            } else {
                if (!$this->updateRemoteDatabase()) {
                    return 1;
                }
            }

            // Step 5: Verify results
            if ($isLocalUpdate) {
                $this->verifyResults();
            }

            $this->info('✅ Database update completed successfully! 🎉');
        } catch (\Exception $e) {
            $this->error('❌ Unexpected error: ' . $e->getMessage());
            return 1;
        } finally {
            // Always clean up
            $this->cleanup();
        }

        return 0;
    }

    /**
     * Clone/update the master-strike repository
     */
    private function cloneRepository(): bool
    {
        $this->info('📥 Step 1: Cloning master-strike repository...');

        $cloneScript = $this->scriptDir . '/mc-01-clone-repo.cjs';

        if (!file_exists($cloneScript)) {
            $this->error("❌ Clone script not found: {$cloneScript}");
            return false;
        }

        try {
            $result = shell_exec("cd " . escapeshellarg($this->scriptDir) . " && node mc-01-clone-repo.cjs 2>&1");

            if (!is_dir($this->masterStrikeDir)) {
                $this->error('❌ Failed to clone repository');
                if ($result) {
                    $this->line($result);
                }
                return false;
            }

            $this->info('✅ Repository cloned successfully');
            return true;
        } catch (\Exception $e) {
            $this->error('❌ Clone failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract entity data with duplicate processing
     */
    private function extractData(): bool
    {
        $this->info('📖 Step 2: Extracting and processing entity data...');

        $extractScript = $this->scriptDir . '/mc-03-extract-corrected.cjs';

        if (!file_exists($extractScript)) {
            $this->error("❌ Extract script not found: {$extractScript}");
            return false;
        }

        try {
            $this->line('Processing TypeScript card definitions...');

            $result = shell_exec("cd " . escapeshellarg($this->scriptDir) . " && node mc-03-extract-corrected.cjs 2>&1");

            if (strpos($result, 'Processing Complete') === false) {
                $this->error('❌ Data extraction failed');
                if ($result) {
                    $this->line($result);
                }
                return false;
            }

            // Show summary from the output
            if (preg_match('/Total entities: (\d+)/', $result, $matches)) {
                $this->info("✅ Extracted {$matches[1]} entities");
            }

            if (preg_match('/Duplicate records created: (\d+)/', $result, $matches)) {
                $this->info("🎯 Created {$matches[1]} duplicate records");
            }

            return true;
        } catch (\Exception $e) {
            $this->error('❌ Data extraction failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create SQLite database
     */
    private function createSqliteDatabase(): bool
    {
        $this->info('🗄️ Step 3: Creating corrected SQLite database...');

        $sqliteScript = $this->scriptDir . '/mc-07-create-sqlite-corrected.cjs';

        if (!file_exists($sqliteScript)) {
            $this->error("❌ SQLite script not found: {$sqliteScript}");
            return false;
        }

        try {
            $result = shell_exec("cd " . escapeshellarg($this->scriptDir) . " && node mc-07-create-sqlite-corrected.cjs 2>&1");

            if (!file_exists($this->sqliteFile)) {
                $this->error('❌ SQLite database creation failed');
                if ($result) {
                    $this->line($result);
                }
                return false;
            }

            // Show summary from the output
            if (preg_match('/Total records: (\d+)/', $result, $matches)) {
                $this->info("✅ SQLite database created with {$matches[1]} records");
            }

            return true;
        } catch (\Exception $e) {
            $this->error('❌ SQLite creation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update MariaDB database
     */
    private function updateMariaDB(): bool
    {
        $this->info('🔄 Step 4: Analyzing and updating MariaDB...');

        try {
            // Table mapping from SQLite list names to Laravel table names
            $tableMapping = [
                'heroes' => 'heroes',
                'masterminds' => 'masterminds',
                'villains' => 'villains',
                'henchmen' => 'henchmens',
                'schemes' => 'schemes'
                // bystanders not included as Laravel doesn't have this table
            ];

            $sqliteDb = new SQLite3($this->sqliteFile);

            // Find entities that should have duplicates
            $duplicatesQuery = $sqliteDb->query("
                SELECT
                    list,
                    json_extract(rec, '\$.name') as name,
                    json_extract(rec, '\$.id') as entity_id,
                    COUNT(*) as count
                FROM store
                WHERE list IN ('heroes', 'masterminds', 'villains', 'henchmen', 'schemes')
                GROUP BY list, json_extract(rec, '\$.name'), json_extract(rec, '\$.id')
                HAVING COUNT(*) > 1
                ORDER BY list, name
            ");

            $recordsToAdd = [];

            while ($duplicate = $duplicatesQuery->fetchArray(SQLITE3_ASSOC)) {
                $tableName = $tableMapping[$duplicate['list']];

                $this->line("📋 Checking {$duplicate['name']} ({$duplicate['list']})...");

                // Check what exists in current Laravel database
                $existing = DB::table($tableName)
                    ->where('name', $duplicate['name'])
                    ->get();

                $existingSets = $existing->pluck('set')->toArray();

                // Get all records for this entity from SQLite
                $sqliteEntityQuery = $sqliteDb->prepare("
                    SELECT * FROM store
                    WHERE list = ?
                    AND json_extract(rec, '\$.name') = ?
                    AND json_extract(rec, '\$.id') = ?
                ");
                $sqliteEntityQuery->bindValue(1, $duplicate['list']);
                $sqliteEntityQuery->bindValue(2, $duplicate['name']);
                $sqliteEntityQuery->bindValue(3, $duplicate['entity_id']);
                $sqliteEntityResult = $sqliteEntityQuery->execute();

                while ($entityRecord = $sqliteEntityResult->fetchArray(SQLITE3_ASSOC)) {
                    $rec = json_decode($entityRecord['rec'], true);
                    $setName = $rec['set_name'] ?? $rec['set'];

                    if (!in_array($setName, $existingSets)) {
                        $recordsToAdd[] = [
                            'table' => $tableName,
                            'name' => $duplicate['name'],
                            'set' => $setName
                        ];
                        $this->line("   ➕ Missing in set: {$setName}");
                    }
                }
            }

            $sqliteDb->close();

            if (empty($recordsToAdd)) {
                $this->info('✅ Database is already up to date! No new records needed.');
                return true;
            }

            // Show what will be updated
            $this->info("📊 Found " . count($recordsToAdd) . " missing duplicate records:");
            foreach ($recordsToAdd as $record) {
                $this->line("  ➕ {$record['name']} → {$record['table']} (set: {$record['set']})");
            }

            // Dry run check
            if ($this->option('dry-run')) {
                $this->warn('🔍 DRY RUN: No changes made to database');
                return true;
            }

            // Confirmation (unless forced)
            if (!$this->option('force')) {
                if (!$this->confirm('Do you want to add these records to the database?')) {
                    $this->warn('❌ Update cancelled by user');
                    return false;
                }
            }

            // Perform the update
            $this->info('🚀 Adding missing duplicate records...');

            // Get current max IDs
            $maxIds = [];
            foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
                $maxIds[$table] = DB::table($table)->max('id') ?? 0;
            }

            DB::beginTransaction();

            try {
                foreach ($recordsToAdd as $record) {
                    $maxIds[$record['table']]++;

                    $insertData = [
                        'id' => $maxIds[$record['table']],
                        'name' => $record['name'],
                        'set' => $record['set'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // For masterminds, copy always_leads from existing record
                    if ($record['table'] === 'masterminds') {
                        $existingMastermind = DB::table('masterminds')
                            ->where('name', $record['name'])
                            ->first();

                        if ($existingMastermind) {
                            $insertData['always_leads'] = $existingMastermind->always_leads;
                            $insertData['handler_done'] = $existingMastermind->handler_done ?? 0;
                        } else {
                            $insertData['always_leads'] = '';
                            $insertData['handler_done'] = 0;
                        }
                    }

                    DB::table($record['table'])->insert($insertData);

                    $this->line("  ✅ Added {$record['name']} to {$record['table']} (ID: {$maxIds[$record['table']]})");
                }

                DB::commit();
                $this->info('🎉 MariaDB update completed successfully!');
            } catch (\Exception $e) {
                DB::rollback();
                $this->error('❌ Database update failed: ' . $e->getMessage());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            $this->error('❌ MariaDB analysis failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify the results
     */
    private function verifyResults(): void
    {
        $this->info('🔍 Verification Results:');

        try {
            // Show updated counts
            $tables = ['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'];
            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                $this->line("  {$table}: {$count} records");
            }

            // Verify Howard the Duck specifically
            $howardRecords = DB::table('heroes')
                ->where('name', 'Howard the Duck')
                ->get();

            $this->newLine();
            $this->info('🦆 Howard the Duck verification:');
            $this->info("  Found {$howardRecords->count()} records:");
            foreach ($howardRecords as $howard) {
                $this->line("  - ID {$howard->id}: Set '{$howard->set}'");
            }
        } catch (\Exception $e) {
            $this->warn('⚠️ Verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Clean up temporary files
     */
    private function cleanup(): void
    {
        $this->line('🧹 Cleaning up temporary files...');

        try {
            // Always attempt to logout if we have a token
            if ($this->authToken) {
                $this->logoutFromProduction();
            }

            // Remove master-strike directory
            if (is_dir($this->masterStrikeDir)) {
                shell_exec("rm -rf " . escapeshellarg($this->masterStrikeDir));
                $this->line('  Removed master-strike directory');
            }

            // Remove SQLite file
            if (file_exists($this->sqliteFile)) {
                unlink($this->sqliteFile);
                $this->line('  Removed SQLite database');
            }

            // Remove any extracted directories
            $extractedDir = $this->scriptDir . '/extracted';
            if (is_dir($extractedDir)) {
                shell_exec("rm -rf " . escapeshellarg($extractedDir));
                $this->line('  Removed extracted directory');
            }

            // Remove JSON export files
            $jsonDir = $this->scriptDir . '/json-export';
            if (is_dir($jsonDir)) {
                shell_exec("rm -rf " . escapeshellarg($jsonDir));
                $this->line('  Removed JSON export directory');
            }

            $this->line('✅ Cleanup completed');
        } catch (\Exception $e) {
            $this->warn('⚠️ Cleanup failed: ' . $e->getMessage());
        }
    }

    /**
     * Authenticate with production API
     */
    private function authenticateWithProduction(): bool
    {
        $this->info('🔐 Authenticating with production API...');

        $email = env('LEGENDARY_UPDATE_EMAIL');
        $password = env('LEGENDARY_UPDATE_PASSWORD');

        if (empty($email) || empty($password)) {
            $this->error('❌ Missing authentication credentials. Please set LEGENDARY_UPDATE_EMAIL and LEGENDARY_UPDATE_PASSWORD in .env');
            return false;
        }

        // For dry-run mode, we can skip actual authentication and just export JSON
        if ($this->option('dry-run')) {
            $this->warn('🔍 DRY RUN: Skipping authentication, will only export JSON files');
            $this->authToken = 'dry-run-token';
            return true;
        }

        try {
            $response = Http::timeout(30)->post($this->apiBaseUrl . '/auth/login', [
                'email' => $email,
                'password' => $password,
            ]);

            if (!$response->successful()) {
                $this->error('❌ Authentication failed: ' . $response->status());
                if ($response->json('message')) {
                    $this->error('   ' . $response->json('message'));
                }
                return false;
            }

            $data = $response->json();
            if (empty($data['token'])) {
                $this->error('❌ No token received from authentication');
                return false;
            }

            $this->authToken = $data['token'];
            $this->info('✅ Authentication successful');
            return true;
        } catch (\Exception $e) {
            $this->error('❌ Authentication failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Logout from production API
     */
    private function logoutFromProduction(): void
    {
        if (!$this->authToken) {
            return;
        }

        try {
            $response = Http::withToken($this->authToken)
                ->timeout(10)
                ->post($this->apiBaseUrl . '/auth/logout');

            if ($response->successful()) {
                $this->line('  Logged out from production API');
            }
        } catch (\Exception $e) {
            $this->line('  Logout failed: ' . $e->getMessage());
        } finally {
            $this->authToken = null;
        }
    }

    /**
     * Update remote database via API endpoints
     */
    private function updateRemoteDatabase(): bool
    {
        $this->info('🌐 Step 4: Updating remote database via API...');

        // Step 1: Authenticate
        if (!$this->authenticateWithProduction()) {
            return false;
        }

        // Step 2: Export SQLite data to JSON files
        if (!$this->exportToJsonFiles()) {
            return false;
        }

        // Step 3: Upload each entity type
        $entityTypes = ['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'];
        $results = [];

        foreach ($entityTypes as $entityType) {
            $this->line("📤 Uploading {$entityType}...");

            if ($this->option('dry-run')) {
                $this->warn("   🔍 DRY RUN: Would upload {$entityType}");
                $results[$entityType] = ['success' => true, 'message' => 'Dry run - no upload performed'];
                continue;
            }

            $result = $this->uploadEntityType($entityType);
            $results[$entityType] = $result;

            if ($result['success']) {
                $this->info("   ✅ {$entityType} updated successfully");
                if (isset($result['message'])) {
                    $this->line('     ' . $result['message']);
                }
            } else {
                $this->error("   ❌ {$entityType} update failed: " . $result['message']);
            }
        }

        // Step 4: Show summary
        $this->showUploadSummary($results);

        // Check if all succeeded
        $allSucceeded = collect($results)->every(fn($result) => $result['success']);

        return $allSucceeded;
    }

    /**
     * Export SQLite data to JSON files
     */
    private function exportToJsonFiles(): bool
    {
        $this->line('📁 Exporting SQLite data to JSON files...');

        $jsonDir = $this->scriptDir . '/json-export';
        if (!is_dir($jsonDir)) {
            mkdir($jsonDir, 0755, true);
        }

        try {
            $sqliteDb = new SQLite3($this->sqliteFile);

            // Table mapping from SQLite list names to Laravel table names
            $tableMapping = [
                'heroes' => 'heroes',
                'masterminds' => 'masterminds',
                'villains' => 'villains',
                'henchmen' => 'henchmens',
                'schemes' => 'schemes'
            ];

            foreach ($tableMapping as $sqliteList => $tableName) {
                $entities = [];

                $query = $sqliteDb->prepare("
                    SELECT * FROM store
                    WHERE list = ?
                    ORDER BY json_extract(rec, '\$.name'), json_extract(rec, '\$.set_name')
                ");
                $query->bindValue(1, $sqliteList);
                $result = $query->execute();

                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $rec = json_decode($row['rec'], true);

                    $entity = [
                        'name' => $rec['name'],
                        'set' => $rec['set_name'] ?? $rec['set'],
                        'id' => $rec['id']
                    ];

                    // Add mastermind-specific fields
                    if ($tableName === 'masterminds') {
                        $entity['always_leads'] = $rec['always_leads'] ?? '';
                        $entity['handler_done'] = 0;
                    }

                    $entities[] = $entity;
                }

                $jsonFile = $jsonDir . '/' . $tableName . '.json';
                file_put_contents($jsonFile, json_encode($entities, JSON_PRETTY_PRINT));

                $this->line("   📝 Exported " . count($entities) . " {$tableName} to {$tableName}.json");
            }

            $sqliteDb->close();
            $this->info('✅ JSON export completed');
            return true;
        } catch (\Exception $e) {
            $this->error('❌ JSON export failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload a specific entity type to the API
     */
    private function uploadEntityType(string $entityType): array
    {
        $jsonFile = $this->scriptDir . '/json-export/' . $entityType . '.json';

        if (!file_exists($jsonFile)) {
            return ['success' => false, 'message' => 'JSON file not found'];
        }

        $entities = json_decode(file_get_contents($jsonFile), true);
        if ($entities === null) {
            return ['success' => false, 'message' => 'Invalid JSON data'];
        }

        try {
            $response = Http::withToken($this->authToken)
                ->timeout(60)
                ->post($this->apiBaseUrl . '/legendary/update-' . $entityType, [
                    'entities' => $entities,
                    'dry_run' => $this->option('dry-run'),
                    'force' => $this->option('force')
                ]);

            if (!$response->successful()) {
                $errorMsg = $response->json('message') ?? 'HTTP ' . $response->status();
                return ['success' => false, 'message' => $errorMsg];
            }

            $data = $response->json();
            return [
                'success' => true,
                'message' => $data['message'] ?? 'Updated successfully',
                'data' => $data
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Show upload summary
     */
    private function showUploadSummary(array $results): void
    {
        $this->newLine();
        $this->info('📊 Upload Summary:');

        $successful = 0;
        $failed = 0;

        foreach ($results as $entityType => $result) {
            if ($result['success']) {
                $successful++;
                $this->line("  ✅ {$entityType}: Success");
            } else {
                $failed++;
                $this->line("  ❌ {$entityType}: Failed - " . $result['message']);
            }
        }

        $this->newLine();
        if ($failed === 0) {
            $this->info("🎉 All {$successful} entity types updated successfully!");
        } else {
            $this->warn("⚠️  {$successful} successful, {$failed} failed");
        }
    }
}
