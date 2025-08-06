<?php

// MariaDB Update Script for Legendary Randomizer Duplicate Entities
// This script safely adds the 19 missing duplicate entities to your MariaDB

require_once(__DIR__ . '/../../app/bootstrap/app.php');

use Illuminate\Database\Capsule\Manager as DB;

echo "🚀 Legendary Randomizer: MariaDB Duplicate Entity Update\n";
echo "======================================================\n\n";

// First, let's check your current database state
echo "🔍 Analyzing current MariaDB state...\n\n";

try {
    $currentCounts = DB::table('store')
        ->select('list', DB::raw('COUNT(*) as count'))
        ->whereIn('list', ['heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders'])
        ->groupBy('list')
        ->orderBy('list')
        ->get();

    echo "📊 Current entity counts in your MariaDB:\n";
    $totalCurrent = 0;
    foreach ($currentCounts as $row) {
        echo "  {$row->list}: {$row->count} records\n";
        $totalCurrent += $row->count;
    }
    echo "  TOTAL: {$totalCurrent} records\n\n";

    // Check if Howard the Duck already has duplicates
    $howardCheck = DB::table('store')
        ->where('list', 'heroes')
        ->where('rec->name', 'Howard the Duck')
        ->get();

    echo "🦆 Howard the Duck current status:\n";
    echo "  Found {$howardCheck->count()} records\n";
    foreach ($howardCheck as $howard) {
        $rec = json_decode($howard->rec, true);
        echo "  - Set {$rec['set']}: {$howard->id}\n";
    }

    if ($howardCheck->count() > 1) {
        echo "\n⚠️  WARNING: Howard the Duck already has multiple records!\n";
        echo "   This suggests duplicates may already be partially implemented.\n";
        echo "   Please review before proceeding.\n\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "❌ Error connecting to MariaDB: " . $e->getMessage() . "\n";
    echo "Please ensure your Laravel database configuration is correct.\n";
    exit(1);
}

// Load the corrected SQLite data
echo "📖 Loading corrected data from SQLite...\n";

try {
    $sqliteDb = new SQLite3(__DIR__ . '/legendary-randomizer-corrected.sqlite');

    // Get all records from SQLite
    $sqliteRecords = $sqliteDb->query("SELECT * FROM store ORDER BY list, json_extract(rec, '$.name')");

    $recordsByType = [];
    $duplicateEntities = [];

    while ($row = $sqliteRecords->fetchArray(SQLITE3_ASSOC)) {
        if (!isset($recordsByType[$row['list']])) {
            $recordsByType[$row['list']] = [];
        }
        $recordsByType[$row['list']][] = $row;

        // Track entities that appear multiple times (duplicates)
        $rec = json_decode($row['rec'], true);
        $entityKey = $row['list'] . '|' . $rec['name'] . '|' . $rec['id'];

        if (!isset($duplicateEntities[$entityKey])) {
            $duplicateEntities[$entityKey] = [];
        }
        $duplicateEntities[$entityKey][] = $row;
    }

    // Find true duplicates
    $trueDuplicates = array_filter($duplicateEntities, function ($records) {
        return count($records) > 1;
    });

    echo "  SQLite contains " . count($trueDuplicates) . " entities with duplicates\n\n";
} catch (Exception $e) {
    echo "❌ Error reading SQLite database: " . $e->getMessage() . "\n";
    exit(1);
}

// Show what will be added
echo "🎯 Entities that need duplicate records added:\n\n";

$recordsToAdd = [];
foreach ($trueDuplicates as $entityKey => $records) {
    list($listType, $entityName, $entityId) = explode('|', $entityKey);
    $rec = json_decode($records[0]['rec'], true);

    echo "📋 {$entityName} ({$listType})\n";

    // Check which records already exist in MariaDB
    $existingRecords = DB::table('store')
        ->where('list', $listType)
        ->where('rec->name', $entityName)
        ->where('rec->id', $entityId)
        ->get();

    $existingSets = $existingRecords->map(function ($record) {
        $rec = json_decode($record->rec, true);
        return $rec['set'];
    })->toArray();

    echo "   Current in MariaDB: sets [" . implode(', ', $existingSets) . "]\n";

    $sqliteSets = [];
    foreach ($records as $record) {
        $rec = json_decode($record['rec'], true);
        $sqliteSets[] = $rec['set'];
    }

    echo "   Should be in: sets [" . implode(', ', $sqliteSets) . "]\n";

    // Find missing sets
    $missingSets = array_diff($sqliteSets, $existingSets);

    if (!empty($missingSets)) {
        echo "   ➕ Need to add: sets [" . implode(', ', $missingSets) . "]\n";

        foreach ($records as $record) {
            $rec = json_decode($record['rec'], true);
            if (in_array($rec['set'], $missingSets)) {
                $recordsToAdd[] = [
                    'list' => $record['list'],
                    'rec' => $record['rec'],
                    '_id' => $record['_id'],
                    'user_id' => 0,
                    'entity_name' => $entityName,
                    'set_id' => $rec['set']
                ];
            }
        }
    } else {
        echo "   ✅ Already complete\n";
    }
    echo "\n";
}

echo "📊 Summary:\n";
echo "  Records to add: " . count($recordsToAdd) . "\n";
echo "  Expected total after update: " . ($totalCurrent + count($recordsToAdd)) . "\n\n";

if (count($recordsToAdd) === 0) {
    echo "✅ No updates needed! Your MariaDB already has all duplicate entities.\n";
    exit(0);
}

// Confirm before proceeding
echo "⚠️  CONFIRMATION REQUIRED ⚠️\n";
echo "This will add " . count($recordsToAdd) . " new records to your MariaDB.\n";
echo "Type 'YES' to proceed, or anything else to cancel: ";

$confirmation = trim(fgets(STDIN));

if ($confirmation !== 'YES') {
    echo "❌ Update cancelled.\n";
    exit(0);
}

// Perform the update
echo "\n🚀 Adding duplicate entities to MariaDB...\n\n";

DB::beginTransaction();

try {
    foreach ($recordsToAdd as $record) {
        DB::table('store')->insert([
            '_id' => $record['_id'],
            'list' => $record['list'],
            'rec' => $record['rec'],
            'user_id' => $record['user_id']
        ]);

        echo "  ✅ Added {$record['entity_name']} to set {$record['set_id']}\n";
    }

    DB::commit();

    echo "\n🎉 Update completed successfully!\n";

    // Verify the update
    echo "\n🔍 Post-update verification:\n";

    $newCounts = DB::table('store')
        ->select('list', DB::raw('COUNT(*) as count'))
        ->whereIn('list', ['heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders'])
        ->groupBy('list')
        ->orderBy('list')
        ->get();

    $totalNew = 0;
    foreach ($newCounts as $row) {
        echo "  {$row->list}: {$row->count} records\n";
        $totalNew += $row->count;
    }
    echo "  TOTAL: {$totalNew} records (was {$totalCurrent})\n\n";

    // Verify Howard the Duck specifically
    $howardVerify = DB::table('store')
        ->where('list', 'heroes')
        ->where('rec->name', 'Howard the Duck')
        ->get();

    echo "🦆 Howard the Duck verification:\n";
    echo "  Found {$howardVerify->count()} records\n";
    foreach ($howardVerify as $howard) {
        $rec = json_decode($howard->rec, true);
        echo "  - Set {$rec['set']}: {$howard->id} (cards: " . count($rec['cards'] ?? []) . ")\n";
    }

    echo "\n✅ Your Legendary Randomizer now has proper duplicate entity support!\n";
} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error during update: " . $e->getMessage() . "\n";
    echo "Update rolled back. No changes made.\n";
    exit(1);
}
