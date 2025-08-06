<?php

/**
 * Legendary Randomizer: Laravel Database Duplicate Entity Update Script
 *
 * This script safely adds missing duplicate entities to your Laravel normalized database
 * Works with heroes, masterminds, villains, henchmens, and schemes tables
 */

// Make sure we're in the Laravel project root
chdir(__DIR__ . '/../../');

// Bootstrap Laravel
require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🚀 Legendary Randomizer: Laravel Database Duplicate Entity Update\n";
echo "===============================================================\n\n";

try {
    // Check database connection
    $connection = DB::connection();
    $driverName = $connection->getDriverName();
    $databaseName = $connection->getDatabaseName();

    echo "📊 Database Connection:\n";
    echo "  Driver: {$driverName}\n";
    echo "  Database: {$databaseName}\n\n";
} catch (Exception $e) {
    echo "❌ Error connecting to database: " . $e->getMessage() . "\n";
    exit(1);
}

// Define the table mapping from SQLite list names to Laravel table names
$tableMapping = [
    'heroes' => 'heroes',
    'masterminds' => 'masterminds',
    'villains' => 'villains',
    'henchmen' => 'henchmens',  // Note: Laravel uses plural 'henchmens'
    'schemes' => 'schemes',
    'bystanders' => null  // No bystanders table in Laravel
];

// Load SQLite data
echo "📖 Loading corrected data from SQLite...\n";

$sqliteFile = __DIR__ . '/legendary-randomizer-corrected.sqlite';

if (!file_exists($sqliteFile)) {
    echo "❌ SQLite file not found: {$sqliteFile}\n";
    echo "Please run the extraction scripts first to generate the corrected database.\n";
    exit(1);
}

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

    echo "🎯 Finding entities that need duplicate records...\n\n";

    while ($duplicate = $duplicatesQuery->fetchArray(SQLITE3_ASSOC)) {
        $duplicateCount++;
        $tableName = $tableMapping[$duplicate['list']];

        echo "📋 {$duplicate['name']} ({$duplicate['list']} → {$tableName} table)\n";
        echo "   Should exist in sets: [{$duplicate['sets']}]\n";

        // Check what exists in current Laravel database
        $existing = DB::table($tableName)
            ->where('name', $duplicate['name'])
            ->get();

        $existingSets = $existing->pluck('set')->toArray();
        echo "   Currently in database: [" . implode(', ', $existingSets) . "]\n";

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
            $setName = $rec['set_name'] ?? $rec['set'];  // Use set_name if available, fallback to set

            if (!in_array($setName, $existingSets)) {
                $missingRecords[] = [
                    'table' => $tableName,
                    'name' => $duplicate['name'],
                    'set' => $setName,
                    'full_record' => $rec
                ];
                echo "   ➕ Missing in set: {$setName}\n";
            }
        }

        if (!empty($missingRecords)) {
            $recordsToAdd = array_merge($recordsToAdd, $missingRecords);
        } else {
            echo "   ✅ Already complete\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error reading SQLite: " . $e->getMessage() . "\n";
    exit(1);
}

// Show current database state
echo "📊 Current database counts:\n";
$currentTotals = [];
foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
    $count = DB::table($table)->count();
    $currentTotals[$table] = $count;
    echo "  {$table}: {$count} records\n";
}
$totalCurrent = array_sum($currentTotals);
echo "  TOTAL: {$totalCurrent} records\n\n";

echo "📊 Summary:\n";
echo "  Entities with duplicates: {$duplicateCount}\n";
echo "  Records to add: " . count($recordsToAdd) . "\n";
echo "  Expected after update: " . ($totalCurrent + count($recordsToAdd)) . "\n\n";

if (count($recordsToAdd) === 0) {
    echo "✅ No updates needed! Your database already has all duplicate entities.\n";
    exit(0);
}

// Show records to add
echo "📋 Records to be added:\n";
foreach ($recordsToAdd as $record) {
    echo "  ➕ {$record['name']} → {$record['table']} (set: {$record['set']})\n";
}
echo "\n";

// Confirmation
echo "⚠️  CONFIRMATION REQUIRED ⚠️\n";
echo "This will add " . count($recordsToAdd) . " new records to your Laravel database.\n";
echo "Type 'YES' to proceed: ";

$handle = fopen("php://stdin", "r");
$confirmation = trim(fgets($handle));
fclose($handle);

if ($confirmation !== 'YES') {
    echo "❌ Update cancelled.\n";
    exit(0);
}

// Get current max IDs for each table
echo "📊 Getting current max IDs...\n";
$maxIds = [];
foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
    $maxIds[$table] = DB::table($table)->max('id') ?? 0;
    echo "  {$table}: max ID = {$maxIds[$table]}\n";
}
echo "\n";

// Perform the update
echo "🚀 Adding duplicate entities...\n\n";

DB::beginTransaction();

try {
    foreach ($recordsToAdd as $record) {
        $maxIds[$record['table']]++;  // Increment for next ID

        // Get additional fields from existing record if needed
        $insertData = [
            'id' => $maxIds[$record['table']],
            'name' => $record['name'],
            'set' => $record['set'],
            'created_at' => now(),
            'updated_at' => now()
        ];

        // For masterminds, we need the always_leads field from existing record
        if ($record['table'] === 'masterminds') {
            $existingMastermind = DB::table('masterminds')
                ->where('name', $record['name'])
                ->first();

            if ($existingMastermind) {
                $insertData['always_leads'] = $existingMastermind->always_leads;
                $insertData['handler_done'] = $existingMastermind->handler_done ?? 0;
            } else {
                echo "  ⚠️  Warning: No existing {$record['name']} found for always_leads value\n";
                $insertData['always_leads'] = '';
                $insertData['handler_done'] = 0;
            }
        }

        DB::table($record['table'])->insert($insertData);

        echo "  ✅ Added {$record['name']} to {$record['table']} (ID: {$maxIds[$record['table']]}, set: {$record['set']})\n";
    }

    DB::commit();

    echo "\n🎉 Update completed successfully!\n\n";
} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error during update: " . $e->getMessage() . "\n";
    echo "All changes have been rolled back.\n";
    exit(1);
}

// Final verification
echo "🔍 Post-update verification:\n";

$finalTotals = [];
foreach (['heroes', 'masterminds', 'villains', 'henchmens', 'schemes'] as $table) {
    $count = DB::table($table)->count();
    $finalTotals[$table] = $count;
    $change = $count - $currentTotals[$table];
    echo "  {$table}: {$count} records (+{$change})\n";
}
$totalFinal = array_sum($finalTotals);
$totalChange = $totalFinal - $totalCurrent;
echo "  TOTAL: {$totalFinal} records (+{$totalChange})\n\n";

// Verify Howard the Duck specifically
echo "🦆 Howard the Duck final verification:\n";
$howardRecords = DB::table('heroes')
    ->where('name', 'Howard the Duck')
    ->get();

echo "  Found {$howardRecords->count()} records:\n";
foreach ($howardRecords as $howard) {
    echo "  - ID {$howard->id}: Set '{$howard->set}'\n";
}

echo "\n✅ Your Legendary Randomizer now supports proper duplicate entities!\n";
echo "Howard the Duck and other entities will now appear when using any of their associated sets.\n";

$sqliteDb->close();
