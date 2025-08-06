<?php

/**
 * Legendary Randomizer: Automatic Database Update Script
 *
 * This script automatically:
 * 1. Extracts latest data from master-strike repository
 * 2. Processes duplicate entities
 * 3. Updates MariaDB with any new records
 */

// Make sure we're in the Laravel project root
chdir(__DIR__ . '/../../');

// Bootstrap Laravel
require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🚀 Legendary Randomizer: Automatic Database Update\n";
echo "================================================\n\n";

// Step 1: Clone/update master-strike repository
echo "📥 Step 1: Updating master-strike repository...\n";

$masterStrikeDir = __DIR__ . '/master-strike';
$cloneScript = __DIR__ . '/mc-01-clone-repo.cjs';

if (!file_exists($cloneScript)) {
    echo "❌ Clone script not found: {$cloneScript}\n";
    exit(1);
}

$cloneResult = shell_exec("cd " . escapeshellarg(__DIR__) . " && node mc-01-clone-repo.cjs 2>&1");
echo "Repository update result:\n{$cloneResult}\n";

// Step 2: Extract corrected data
echo "📖 Step 2: Extracting corrected entity data...\n";

$extractScript = __DIR__ . '/mc-03-extract-corrected.cjs';
if (!file_exists($extractScript)) {
    echo "❌ Extract script not found: {$extractScript}\n";
    exit(1);
}

$extractResult = shell_exec("cd " . escapeshellarg(__DIR__) . " && node mc-03-extract-corrected.cjs 2>&1");
echo "Data extraction result:\n{$extractResult}\n";

// Step 3: Create SQLite database
echo "🗄️ Step 3: Creating corrected SQLite database...\n";

$sqliteScript = __DIR__ . '/mc-07-create-sqlite-corrected.cjs';
if (!file_exists($sqliteScript)) {
    echo "❌ SQLite script not found: {$sqliteScript}\n";
    exit(1);
}

$sqliteResult = shell_exec("cd " . escapeshellarg(__DIR__) . " && node mc-07-create-sqlite-corrected.cjs 2>&1");
echo "SQLite creation result:\n{$sqliteResult}\n";

// Step 4: Check if SQLite database was created successfully
$sqliteFile = __DIR__ . '/legendary-randomizer-corrected.sqlite';
if (!file_exists($sqliteFile)) {
    echo "❌ SQLite database not created: {$sqliteFile}\n";
    echo "Please check the extraction scripts for errors.\n";
    exit(1);
}

echo "✅ Data extraction completed successfully!\n\n";

// Step 5: Connect to MariaDB and analyze differences
echo "🔍 Step 4: Analyzing database differences...\n";

try {
    $connection = DB::connection();
    $driverName = $connection->getDriverName();
    $databaseName = $connection->getDatabaseName();

    echo "📊 Database Connection: {$driverName} - {$databaseName}\n\n";
} catch (Exception $e) {
    echo "❌ Error connecting to database: " . $e->getMessage() . "\n";
    exit(1);
}

// Table mapping from SQLite list names to Laravel table names
$tableMapping = [
    'heroes' => 'heroes',
    'masterminds' => 'masterminds',
    'villains' => 'villains',
    'henchmen' => 'henchmens',
    'schemes' => 'schemes',
    'bystanders' => null  // No bystanders table in Laravel
];

try {
    $sqliteDb = new SQLite3($sqliteFile);

    // Get entities that should have duplicates (excluding bystanders)
    $duplicatesQuery = $sqliteDb->query("
        SELECT
            list,
            json_extract(rec, '$.name') as name,
            json_extract(rec, '$.id') as entity_id,
            COUNT(*) as count,
            GROUP_CONCAT(json_extract(rec, '$.set')) as sets
        FROM store
        WHERE list IN ('heroes', 'masterminds', 'villains', 'henchmen', 'schemes')
        GROUP BY
            list,
            json_extract(rec, '$.name'),
            json_extract(rec, '$.id')
        HAVING COUNT(*) > 1
        ORDER BY list, name
    ");

    $recordsToAdd = [];
    $duplicateCount = 0;

    echo "🎯 Checking for missing duplicate records...\n\n";

    while ($duplicate = $duplicatesQuery->fetchArray(SQLITE3_ASSOC)) {
        $duplicateCount++;
        $tableName = $tableMapping[$duplicate['list']];

        // Check what exists in current Laravel database
        $existing = DB::table($tableName)
            ->where('name', $duplicate['name'])
            ->get();

        $existingSets = $existing->pluck('set')->toArray();

        // Get all records for this entity from SQLite
        $sqliteEntityQuery = $sqliteDb->prepare("
            SELECT * FROM store
            WHERE list = ?
            AND json_extract(rec, '$.name') = ?
            AND json_extract(rec, '$.id') = ?
        ");
        $sqliteEntityQuery->bindValue(1, $duplicate['list']);
        $sqliteEntityQuery->bindValue(2, $duplicate['name']);
        $sqliteEntityQuery->bindValue(3, $duplicate['entity_id']);
        $sqliteEntityResult = $sqliteEntityQuery->execute();

        $missingRecords = [];
        while ($entityRecord = $sqliteEntityResult->fetchArray(SQLITE3_ASSOC)) {
            $rec = json_decode($entityRecord['rec'], true);
            $setName = $rec['set_name'] ?? $rec['set'];

            if (!in_array($setName, $existingSets)) {
                $missingRecords[] = [
                    'table' => $tableName,
                    'name' => $duplicate['name'],
                    'set' => $setName,
                    'full_record' => $rec
                ];
            }
        }

        if (!empty($missingRecords)) {
            echo "📋 {$duplicate['name']} ({$duplicate['list']}): Missing " . count($missingRecords) . " record(s)\n";
            $recordsToAdd = array_merge($recordsToAdd, $missingRecords);
        }
    }
} catch (Exception $e) {
    echo "❌ Error reading SQLite: " . $e->getMessage() . "\n";
    exit(1);
}

// Show current database state
echo "\n📊 Current database counts:\n";
$currentTotals = [];
foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
    $count = DB::table($table)->count();
    $currentTotals[$table] = $count;
    echo "  {$table}: {$count} records\n";
}
$totalCurrent = array_sum($currentTotals);
echo "  TOTAL: {$totalCurrent} records\n\n";

if (count($recordsToAdd) === 0) {
    echo "✅ Database is up to date! No new duplicate entities found.\n";
    echo "🦆 Howard the Duck verification:\n";

    $howardRecords = DB::table('heroes')
        ->where('name', 'Howard the Duck')
        ->get();

    echo "  Found {$howardRecords->count()} records:\n";
    foreach ($howardRecords as $howard) {
        echo "  - ID {$howard->id}: Set '{$howard->set}'\n";
    }
    exit(0);
}

// Auto-update: Add missing records
echo "🚀 Step 5: Adding {count($recordsToAdd)} missing duplicate entities...\n\n";

// Get current max IDs
$maxIds = [];
foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
    $maxIds[$table] = DB::table($table)->max('id') ?? 0;
}

DB::beginTransaction();

try {
    foreach ($recordsToAdd as $record) {
        $maxIds[$record['table']]++;

        // Prepare insert data
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

        echo "  ✅ Added {$record['name']} to {$record['table']} (ID: {$maxIds[$record['table']]}, set: {$record['set']})\n";
    }

    DB::commit();

    echo "\n🎉 Database update completed successfully!\n\n";
} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error during update: " . $e->getMessage() . "\n";
    echo "All changes have been rolled back.\n";
    exit(1);
}

// Final verification
echo "🔍 Final verification:\n";

$finalTotals = [];
foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
    $count = DB::table($table)->count();
    $finalTotals[$table] = $count;
    $change = $count - $currentTotals[$table];
    echo "  {$table}: {$count} records" . ($change > 0 ? " (+{$change})" : "") . "\n";
}
$totalFinal = array_sum($finalTotals);
$totalChange = $totalFinal - $totalCurrent;
echo "  TOTAL: {$totalFinal} records" . ($totalChange > 0 ? " (+{$totalChange})" : "") . "\n\n";

// Verify Howard the Duck specifically
echo "🦆 Howard the Duck final verification:\n";
$howardRecords = DB::table('heroes')
    ->where('name', 'Howard the Duck')
    ->get();

echo "  Found {$howardRecords->count()} records:\n";
foreach ($howardRecords as $howard) {
    echo "  - ID {$howard->id}: Set '{$howard->set}'\n";
}

echo "\n✅ Your Legendary Randomizer database is now fully up to date!\n";
echo "All duplicate entities are properly synchronized with the latest master-strike data.\n";

$sqliteDb->close();
