#!/usr/bin/env node

/**
 * Legendary Randomizer: Complete Database Update Script
 *
 * This script automatically:
 * 1. Clones/updates master-strike repository
 * 2. Extracts and processes duplicate entities
 * 3. Updates Laravel MariaDB database
 * 4. Cleans up all temporary files
 *
 * Usage: node update-database.js
 */

const fs = require("fs");
const path = require("path");
const { execSync } = require("child_process");
const sqlite3 = require("sqlite3").verbose();

// Configuration
const SCRIPT_DIR = __dirname;
const MASTER_STRIKE_DIR = path.join(SCRIPT_DIR, "master-strike");
const TEMP_DIR = path.join(SCRIPT_DIR, "temp");
const SQLITE_FILE = path.join(
  SCRIPT_DIR,
  "legendary-randomizer-corrected.sqlite"
);
const LARAVEL_ROOT = path.resolve(SCRIPT_DIR, "..");

// ANSI colors for better output
const colors = {
  reset: "\x1b[0m",
  bright: "\x1b[1m",
  red: "\x1b[31m",
  green: "\x1b[32m",
  yellow: "\x1b[33m",
  blue: "\x1b[34m",
  magenta: "\x1b[35m",
  cyan: "\x1b[36m",
};

function log(message, color = colors.reset) {
  console.log(`${color}${message}${colors.reset}`);
}

function logStep(step, message) {
  log(`${step} ${message}`, colors.cyan);
}

function logSuccess(message) {
  log(`✅ ${message}`, colors.green);
}

function logError(message) {
  log(`❌ ${message}`, colors.red);
}

function logWarning(message) {
  log(`⚠️  ${message}`, colors.yellow);
}

// Cleanup function
function cleanup() {
  logStep("🧹", "Cleaning up temporary files...");

  try {
    // Remove master-strike directory
    if (fs.existsSync(MASTER_STRIKE_DIR)) {
      fs.rmSync(MASTER_STRIKE_DIR, { recursive: true, force: true });
      log("  Removed master-strike directory");
    }

    // Remove temp directory
    if (fs.existsSync(TEMP_DIR)) {
      fs.rmSync(TEMP_DIR, { recursive: true, force: true });
      log("  Removed temp directory");
    }

    // Remove SQLite file
    if (fs.existsSync(SQLITE_FILE)) {
      fs.unlinkSync(SQLITE_FILE);
      log("  Removed SQLite database");
    }

    // Remove any NeDB files but preserve package files
    const tempFiles = fs
      .readdirSync(SCRIPT_DIR)
      .filter(
        (f) =>
          (f.endsWith(".db") || f.endsWith(".json")) &&
          f !== "package.json" &&
          f !== "package-lock.json"
      );
    tempFiles.forEach((file) => {
      const filePath = path.join(SCRIPT_DIR, file);
      if (fs.existsSync(filePath)) {
        fs.unlinkSync(filePath);
        log(`  Removed ${file}`);
      }
    });

    logSuccess("Cleanup completed");
  } catch (error) {
    logWarning(`Cleanup failed: ${error.message}`);
  }
}

// Clone/update master-strike repository
function cloneRepository() {
  logStep("📥", "Step 1: Cloning master-strike repository...");

  try {
    // Remove existing directory if it exists
    if (fs.existsSync(MASTER_STRIKE_DIR)) {
      fs.rmSync(MASTER_STRIKE_DIR, { recursive: true, force: true });
    }

    // Clone the repository
    execSync(
      `git clone --depth 1 https://github.com/emfmesquita/master-strike.git "${MASTER_STRIKE_DIR}"`,
      {
        stdio: "pipe",
        cwd: SCRIPT_DIR,
      }
    );

    logSuccess("Repository cloned successfully");
    return true;
  } catch (error) {
    logError(`Failed to clone repository: ${error.message}`);
    return false;
  }
}

// Parse TypeScript files and extract entities
function extractEntities() {
  logStep("📖", "Step 2: Extracting entity data...");

  const cardsDir = path.join(
    MASTER_STRIKE_DIR,
    "packages",
    "data",
    "src",
    "definitions",
    "cards"
  );

  if (!fs.existsSync(cardsDir)) {
    logError(`Cards directory not found: ${cardsDir}`);
    return null;
  }

  // Helper function to parse TypeScript file content and extract the data
  function parseTypeScriptFile(filePath) {
    try {
      const content = fs.readFileSync(filePath, "utf8");

      // This is a simplified parser - we'll use eval in a controlled way
      // Remove imports and replace with empty objects for missing types
      let processedContent = content
        .replace(/import.*from.*;\s*\n/g, "")
        .replace(/export\s+const\s+(\w+):\s*CardSetDef\s*=/, "const $1 =")
        .replace(/CardSetDef/g, "any");

      // Add the variable to make it available
      processedContent +=
        '\nif (typeof module !== "undefined") { module.exports = { data: ' +
        (content.match(/export\s+const\s+(\w+):/)?.[1] || "undefined") +
        " }; }";

      // Create a temporary file and require it
      const tempFile = path.join(__dirname, "temp-" + Date.now() + ".js");
      fs.writeFileSync(tempFile, processedContent);

      try {
        const result = require(tempFile);
        fs.unlinkSync(tempFile); // Clean up
        return result.data;
      } catch (e) {
        fs.unlinkSync(tempFile); // Clean up even on error
        throw e;
      }
    } catch (error) {
      console.error(`Error parsing ${filePath}:`, error.message);
      return null;
    }
  }

  const cardFiles = fs.readdirSync(cardsDir).filter((f) => f.endsWith(".ts"));
  const allEntities = {
    heroes: [],
    masterminds: [],
    villains: [],
    henchmen: [],
    schemes: [],
    bystanders: [],
  };

  // Set ID to value mapping (based on Laravel database structure)
  const setIdToValueMap = {
    1: "coreset",
    2: "promo",
    3: "darkcity",
    4: "ff",
    5: "pttr",
    6: "gotg",
    7: "xmen",
    8: "sw1",
    9: "3d",
    10: "captainamerica",
    11: "deadpool",
    12: "noir",
    13: "sw2",
    14: "villains",
    15: "civilwar",
    16: "realmofkings",
    17: "revelations",
    18: "doctorstrange",
    19: "champions",
    20: "fearitself",
    21: "wwhulk",
    22: "antman",
    23: "dimensions",
    24: "blackwidow",
    25: "blackpanther",
    26: "venom",
    27: "spiderhomecoming",
    28: "heroesofasgard",
    29: "intothecosmos",
    30: "shield",
    31: "midnightsons",
    32: "weaponx",
    33: "newmutants",
    34: "messiahcomplex",
    35: "annihilation",
    36: "msaw",
    37: "msgotg",
    38: "msis",
    39: "mswi",
    40: "marvelstudios",
    41: "2099",
  };

  let totalDuplicates = 0;

  log(`Processing ${cardFiles.length} card files...`);

  cardFiles.forEach((filename) => {
    const filePath = path.join(cardsDir, filename);
    const setId = filename.replace(".ts", "");

    try {
      const data = parseTypeScriptFile(filePath);
      if (!data) {
        logWarning(`Failed to parse ${filename}`);
        return;
      }

      // Process each entity type and handle duplicates
      const entityTypes = [
        "heroes",
        "masterminds",
        "villains",
        "henchmen",
        "schemes",
        "bystanders",
      ];

      entityTypes.forEach((type) => {
        if (data[type] && Array.isArray(data[type])) {
          data[type].forEach((entity) => {
            if (
              entity.set &&
              Array.isArray(entity.set) &&
              entity.set.length > 1
            ) {
              // This entity appears in multiple sets - create duplicates
              const setValues = entity.set.map(
                (setId) => setIdToValueMap[setId] || setId
              );
              entity.set.forEach((setIdNum) => {
                const duplicateEntity = { ...entity };
                // Use set VALUE, not set ID
                duplicateEntity.set = setIdToValueMap[setIdNum] || setIdNum;
                duplicateEntity.set_name =
                  setIdToValueMap[setIdNum] || setIdNum; // For compatibility
                delete duplicateEntity.sets; // Remove the array if it exists
                allEntities[type].push(duplicateEntity);
                totalDuplicates++;
              });
              log(
                `    🎯 DUPLICATE: ${entity.name} → sets: ${setValues.join(
                  ", "
                )} (IDs: ${entity.set.join(", ")})`,
                colors.yellow
              );
            } else {
              // Regular entity
              const singleEntity = { ...entity };
              // Use the file set value (filename), not ID
              singleEntity.set = setId;
              allEntities[type].push(singleEntity);
            }
          });
        }
      });

      log(`📁 ${filename} processed`);
    } catch (error) {
      logWarning(`Failed to process ${filename}: ${error.message}`);
    }
  });

  // Calculate totals
  const totals = Object.entries(allEntities)
    .map(([type, entities]) => `${type}: ${entities.length}`)
    .join(", ");

  logSuccess(`Data extraction completed! ${totals}`);
  log(`🎯 Total duplicate records created: ${totalDuplicates}`, colors.magenta);

  return allEntities;
}

// Create SQLite database
function createSQLiteDatabase(entities) {
  logStep("🗄️", "Step 3: Creating SQLite database...");

  return new Promise((resolve, reject) => {
    // Remove existing database
    if (fs.existsSync(SQLITE_FILE)) {
      fs.unlinkSync(SQLITE_FILE);
    }

    const db = new sqlite3.Database(SQLITE_FILE);

    db.serialize(() => {
      // Create store table
      db.run(`CREATE TABLE store (
                _id TEXT PRIMARY KEY,
                list TEXT NOT NULL,
                rec TEXT NOT NULL,
                user_id INTEGER DEFAULT 0
            )`);

      let recordId = 1;

      // Insert all entities
      Object.entries(entities).forEach(([type, entityList]) => {
        log(`📋 Processing ${type}...`);

        entityList.forEach((entity) => {
          const rec = JSON.stringify(entity);
          const _id = `record_${recordId++}`;

          db.run(
            "INSERT INTO store (_id, list, rec, user_id) VALUES (?, ?, ?, ?)",
            [_id, type, rec, 0]
          );
        });

        log(`✅ Inserted ${entityList.length} ${type} records`);
      });
    });

    db.close((err) => {
      if (err) {
        logError(`SQLite database creation failed: ${err.message}`);
        reject(err);
      } else {
        logSuccess("SQLite database created successfully");
        resolve();
      }
    });
  });
}

// Update MariaDB database
function updateMariaDB(entities) {
  logStep("🔄", "Step 4: Updating MariaDB database...");

  try {
    // Create PHP script content
    const phpScript = `<?php
// Bootstrap Laravel
require_once '${LARAVEL_ROOT}/vendor/autoload.php';
$app = require_once '${LARAVEL_ROOT}/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

use Illuminate\\Support\\Facades\\DB;

// Table mapping
$tableMapping = [
    'heroes' => 'heroes',
    'masterminds' => 'masterminds',
    'villains' => 'villains',
    'henchmen' => 'henchmens',
    'schemes' => 'schemes'
];

// Get SQLite data
$sqliteDb = new SQLite3('${SQLITE_FILE}');

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

    // Check what exists in Laravel database
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
                'set' => $setName,
                'entity_id' => $duplicate['entity_id']
            ];
        }
    }
}

if (empty($recordsToAdd)) {
    echo "✅ Database is up to date!\\n";
    exit(0);
}

echo "🚀 Adding " . count($recordsToAdd) . " missing duplicate entities...\\n";

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

        echo "  ✅ Added {$record['name']} to {$record['table']} (ID: {$maxIds[$record['table']]}, set: {$record['set']}, entity_id: {$record['entity_id']})\\n";
    }

    DB::commit();
    echo "🎉 Database update completed successfully!\\n";

} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error: " . $e->getMessage() . "\\n";
    exit(1);
}

$sqliteDb->close();
?>`;

    // Write and execute PHP script
    const phpFile = path.join(SCRIPT_DIR, "temp_update.php");
    fs.writeFileSync(phpFile, phpScript);

    const result = execSync(`php "${phpFile}"`, {
      stdio: "pipe",
      encoding: "utf8",
      cwd: LARAVEL_ROOT,
    });

    console.log(result);

    // Clean up PHP file
    fs.unlinkSync(phpFile);

    return true;
  } catch (error) {
    logError(`MariaDB update failed: ${error.message}`);
    return false;
  }
}

// Verify Howard the Duck
function verifyHowardTheDuck() {
  logStep("🦆", "Verifying Howard the Duck...");

  try {
    const phpScript = `<?php
require_once '${LARAVEL_ROOT}/vendor/autoload.php';
$app = require_once '${LARAVEL_ROOT}/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
$kernel->bootstrap();

use Illuminate\\Support\\Facades\\DB;

$howardRecords = DB::table('heroes')
    ->where('name', 'Howard the Duck')
    ->get();

echo "🦆 Howard the Duck verification:\\n";
echo "  Found {$howardRecords->count()} records:\\n";
foreach ($howardRecords as $howard) {
    echo "  - ID {$howard->id}: Set '{$howard->set}'\\n";
}
?>`;

    const phpFile = path.join(SCRIPT_DIR, "temp_verify.php");
    fs.writeFileSync(phpFile, phpScript);

    const result = execSync(`php "${phpFile}"`, {
      stdio: "pipe",
      encoding: "utf8",
      cwd: LARAVEL_ROOT,
    });

    console.log(result);

    // Clean up PHP file
    fs.unlinkSync(phpFile);
  } catch (error) {
    logWarning(`Verification failed: ${error.message}`);
  }
}

// Main execution
async function main() {
  log("🚀 Legendary Randomizer: Complete Database Update", colors.bright);
  log("================================================\n", colors.bright);

  // Ensure cleanup happens on exit
  process.on("exit", cleanup);
  process.on("SIGINT", () => {
    log("\n🛑 Process interrupted", colors.yellow);
    cleanup();
    process.exit(1);
  });
  process.on("SIGTERM", () => {
    log("\n🛑 Process terminated", colors.yellow);
    cleanup();
    process.exit(1);
  });

  try {
    // Step 1: Clone repository
    if (!cloneRepository()) {
      process.exit(1);
    }

    // Step 2: Extract entities
    const entities = extractEntities();
    if (!entities) {
      process.exit(1);
    }

    // Step 3: Create SQLite database
    await createSQLiteDatabase(entities);

    // Step 4: Update MariaDB
    if (!updateMariaDB(entities)) {
      process.exit(1);
    }

    // Step 5: Verify results
    verifyHowardTheDuck();

    logSuccess("All steps completed successfully! 🎉");
  } catch (error) {
    logError(`Unexpected error: ${error.message}`);
    process.exit(1);
  } finally {
    // Cleanup is handled by process exit events
  }
}

// Check for required dependencies
function checkDependencies() {
  try {
    require("sqlite3");
  } catch (error) {
    logError(
      "sqlite3 module not found. Please install it with: npm install sqlite3"
    );
    process.exit(1);
  }
}

// Run the script
checkDependencies();
main();
