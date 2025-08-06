<?php

/**
 * Legendary Randomizer: MariaDB Duplicate Entity Update Script
 *
 * This script safely adds the 19 missing duplicate entities to your database
 * It works with Laravel's database configuration and handles both SQLite and MariaDB
 */

// Make sure we're in the Laravel project root
chdir(__DIR__ . '/../../');

// Bootstrap Laravel
require_once __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🚀 Legendary Randomizer: Database Duplicate Entity Update\n";
echo "========================================================\n\n";

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
    echo "Please ensure your Laravel database is configured and accessible.\n";
    exit(1);
}

// Check current database state
echo "🔍 Analyzing current database state...\n\n";

try {
    $currentCounts = DB::table('store')
        ->select('list', DB::raw('COUNT(*) as count'))
        ->whereIn('list', ['heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders'])
        ->groupBy('list')
        ->orderBy('list')
        ->get();

    echo "📋 Current entity counts:\n";
    $totalCurrent = 0;
    foreach ($currentCounts as $row) {
        echo "  {$row->list}: {$row->count} records\n";
        $totalCurrent += $row->count;
    }
    echo "  TOTAL: {$totalCurrent} records\n\n";

    // Check Howard the Duck specifically
    $howardCount = DB::table('store')
        ->where('list', 'heroes')
        ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(rec, '$.name')) = ?", ['Howard the Duck'])
        ->count();

    echo "🦆 Howard the Duck current status: {$howardCount} record(s)\n";

    if ($howardCount > 1) {
        echo "⚠️  Howard the Duck already has multiple records - duplicates may already be implemented.\n\n";

        $howardRecords = DB::table('store')
            ->where('list', 'heroes')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(rec, '$.name')) = ?", ['Howard the Duck'])
            ->get();

        foreach ($howardRecords as $howard) {
            $rec = json_decode($howard->rec, true);
            echo "  - Set {$rec['set']}: Record ID {$howard->id}\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error analyzing database: " . $e->getMessage() . "\n";
    exit(1);
}

// Load SQLite data
echo "📖 Loading corrected data from SQLite...\n";

$sqliteFile = __DIR__ . '/master-strike-parse/my-parser-scripts/legendary-randomizer-corrected.sqlite';

if (!file_exists($sqliteFile)) {
    echo "❌ SQLite file not found: {$sqliteFile}\n";
    echo "Please run the extraction scripts first to generate the corrected database.\n";
    exit(1);
}

try {
    $sqliteDb = new SQLite3($sqliteFile);

    // Get counts from SQLite for comparison
    $sqliteCountsQuery = $sqliteDb->query("
        SELECT list, COUNT(*) as count
        FROM store
        WHERE list IN ('heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders')
        GROUP BY list
        ORDER BY list
    ");

    echo "📋 SQLite corrected counts:\n";
    $totalSqlite = 0;
    while ($row = $sqliteCountsQuery->fetchArray(SQLITE3_ASSOC)) {
        echo "  {$row['list']}: {$row['count']} records\n";
        $totalSqlite += $row['count'];
    }
    echo "  TOTAL: {$totalSqlite} records\n\n";

    $expectedNewRecords = $totalSqlite - $totalCurrent;
    echo "📊 Expected records to add: {$expectedNewRecords}\n\n";
} catch (Exception $e) {
    echo "❌ Error reading SQLite: " . $e->getMessage() . "\n";
    exit(1);
}

// Find entities that need duplicates
echo "🎯 Finding entities that need duplicate records...\n\n";

$sqliteDuplicatesQuery = $sqliteDb->query("
    SELECT
        list,
        json_extract(rec, '$.name') as name,
        json_extract(rec, '$.id') as entity_id,
        COUNT(*) as count,
        GROUP_CONCAT(json_extract(rec, '$.set')) as sets
    FROM store
    WHERE list IN ('heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders')
    GROUP BY
        list,
        json_extract(rec, '$.name'),
        json_extract(rec, '$.id')
    HAVING COUNT(*) > 1
    ORDER BY list, name
");

$recordsToAdd = [];
$duplicateCount = 0;

while ($duplicate = $sqliteDuplicatesQuery->fetchArray(SQLITE3_ASSOC)) {
    $duplicateCount++;
    echo "📋 {$duplicate['name']} ({$duplicate['list']})\n";
    echo "   Should exist in sets: [{$duplicate['sets']}]\n";

    // Check what exists in current database
    $existing = DB::table('store')
        ->where('list', $duplicate['list'])
        ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(rec, '$.name')) = ?", [$duplicate['name']])
        ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(rec, '$.id')) = ?", [$duplicate['entity_id']])
        ->get();

    $existingSets = $existing->map(function ($record) {
        $rec = json_decode($record->rec, true);
        return $rec['set'];
    })->toArray();

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
        if (!in_array($rec['set'], $existingSets)) {
            $missingRecords[] = $entityRecord;
            echo "   ➕ Missing in set: {$rec['set']}\n";
        }
    }

    if (!empty($missingRecords)) {
        $recordsToAdd = array_merge($recordsToAdd, $missingRecords);
    } else {
        echo "   ✅ Already complete\n";
    }
    echo "\n";
}

echo "📊 Summary:\n";
echo "  Entities with duplicates: {$duplicateCount}\n";
echo "  Records to add: " . count($recordsToAdd) . "\n";
echo "  Current total: {$totalCurrent}\n";
echo "  Expected after update: " . ($totalCurrent + count($recordsToAdd)) . "\n\n";

if (count($recordsToAdd) === 0) {
    echo "✅ No updates needed! Your database already has all duplicate entities.\n";
    exit(0);
}

// Confirmation
echo "⚠️  CONFIRMATION REQUIRED ⚠️\n";
echo "This will add " . count($recordsToAdd) . " new records to your database.\n";
echo "Type 'YES' to proceed: ";

$handle = fopen("php://stdin", "r");
$confirmation = trim(fgets($handle));
fclose($handle);

if ($confirmation !== 'YES') {
    echo "❌ Update cancelled.\n";
    exit(0);
}

// Perform the update
echo "\n🚀 Adding duplicate entities...\n\n";

DB::beginTransaction();

try {
    foreach ($recordsToAdd as $record) {
        $rec = json_decode($record['rec'], true);

        DB::table('store')->insert([
            '_id' => $record['_id'],
            'list' => $record['list'],
            'rec' => $record['rec'],
            'user_id' => 0
        ]);

        echo "  ✅ Added {$rec['name']} to set {$rec['set']}\n";
    }

    DB::commit();

    echo "\n🎉 Update completed successfully!\n\n";

    // Final verification
    echo "🔍 Post-update verification:\n";

    $finalCounts = DB::table('store')
        ->select('list', DB::raw('COUNT(*) as count'))
        ->whereIn('list', ['heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders'])
        ->groupBy('list')
        ->orderBy('list')
        ->get();

    $totalFinal = 0;
    foreach ($finalCounts as $row) {
        echo "  {$row->list}: {$row->count} records\n";
        $totalFinal += $row->count;
    }
    echo "  TOTAL: {$totalFinal} records\n\n";

    // Verify Howard the Duck
    $howardFinal = DB::table('store')
        ->where('list', 'heroes')
        ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(rec, '$.name')) = ?", ['Howard the Duck'])
        ->get();

    echo "🦆 Howard the Duck final verification:\n";
    echo "  Found {$howardFinal->count()} records:\n";
    foreach ($howardFinal as $howard) {
        $rec = json_decode($howard->rec, true);
        echo "  - Set {$rec['set']}: " . count($rec['cards'] ?? []) . " cards\n";
    }

    echo "\n✅ Your Legendary Randomizer now supports proper duplicate entities!\n";
    echo "Howard the Duck will now appear when using either the 3D set or Dimensions set.\n";
} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error during update: " . $e->getMessage() . "\n";
    echo "All changes have been rolled back.\n";
    exit(1);
}

$sqliteDb->close();
