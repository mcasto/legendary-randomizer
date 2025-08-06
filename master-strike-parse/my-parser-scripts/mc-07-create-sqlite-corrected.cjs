const nedb = require("nedb-promises");
const sqlite3 = require("sqlite3").verbose();
const path = require("path");
const fs = require("fs");

console.log("🏗️ Building CORRECTED SQLite database...\n");

// Create SQLite database
const dbPath = path.join(__dirname, "legendary-randomizer-corrected.sqlite");
if (fs.existsSync(dbPath)) {
  fs.unlinkSync(dbPath);
  console.log("🗑️ Removed existing SQLite database");
}

const db = new sqlite3.Database(dbPath);

// Load NeDB databases
const dbFiles = {
  sets: path.join(__dirname, "sets.nedb"),
  keywords: path.join(__dirname, "keywords.nedb"),
  teams: path.join(__dirname, "teams.nedb"),
  masterminds: path.join(__dirname, "masterminds.nedb"),
  villains: path.join(__dirname, "villains.nedb"),
  henchmen: path.join(__dirname, "henchmen.nedb"),
  heroes: path.join(__dirname, "heroes.nedb"),
  bystanders: path.join(__dirname, "bystanders.nedb"),
  schemes: path.join(__dirname, "schemes.nedb"),
  classes: path.join(__dirname, "classes.nedb"),
  rarities: path.join(__dirname, "rarities.nedb"),
  rules: path.join(__dirname, "rules.nedb"),
};

const nedbInstances = {};

(async () => {
  // Initialize NeDB instances
  for (let [key, dbFile] of Object.entries(dbFiles)) {
    if (fs.existsSync(dbFile)) {
      nedbInstances[key] = nedb.create(dbFile);
    }
  }

  // Create SQLite tables to match your Laravel structure
  await new Promise((resolve, reject) => {
    db.serialize(() => {
      // Main store table (matches your Laravel structure)
      db.run(`CREATE TABLE store (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        _id TEXT NOT NULL,
        list TEXT NOT NULL,
        rec TEXT NOT NULL,
        user_id INTEGER DEFAULT 0
      )`);

      console.log("✅ Created SQLite tables");
      resolve();
    });
  });

  // Process each entity type
  const entityTypes = [
    "heroes",
    "masterminds",
    "villains",
    "henchmen",
    "schemes",
    "bystanders",
  ];
  let totalRecords = 0;
  let trueDuplicatesFound = 0;

  for (let entityType of entityTypes) {
    if (nedbInstances[entityType]) {
      console.log(`📋 Processing ${entityType}...`);

      const entities = await nedbInstances[entityType].find({});
      console.log(`  Found ${entities.length} ${entityType} records`);

      // Track true duplicates (entities that exist in multiple sets)
      const entityGroups = {};
      entities.forEach((entity) => {
        const key = `${entity.name}|${entity.id}|${JSON.stringify(
          entity.cards?.map((c) => c.name).sort()
        )}`;
        if (!entityGroups[key]) {
          entityGroups[key] = [];
        }
        entityGroups[key].push(entity);
      });

      const trueDuplicates = Object.entries(entityGroups).filter(
        ([key, group]) => group.length > 1
      );

      if (trueDuplicates.length > 0) {
        console.log(`  🎯 True duplicates found: ${trueDuplicates.length}`);
        trueDuplicates.forEach(([key, group]) => {
          const entity = group[0];
          const sets = group.map((e) => e.set).sort((a, b) => a - b);
          console.log(
            `    - ${entity.name}: ${group.length} records in sets [${sets.join(
              ", "
            )}]`
          );
        });
        trueDuplicatesFound += trueDuplicates.length;
      }

      // Insert all entities into SQLite
      for (let entity of entities) {
        const entityJson = JSON.stringify(entity);

        await new Promise((resolve, reject) => {
          db.run(
            "INSERT INTO store (_id, list, rec, user_id) VALUES (?, ?, ?, ?)",
            [entity.id || 0, entityType, entityJson, 0],
            function (err) {
              if (err) reject(err);
              else resolve(this.lastID);
            }
          );
        });
      }

      totalRecords += entities.length;
      console.log(`  ✅ Inserted ${entities.length} ${entityType} records`);
    }
  }

  // Generate final verification report
  console.log("\n🎉 Corrected SQLite Database Creation Complete!\n");

  console.log("📊 Final Summary:");
  console.log(`  Database file: legendary-randomizer-corrected.sqlite`);
  console.log(`  Total records: ${totalRecords}`);
  console.log(`  True duplicate entities: ${trueDuplicatesFound}`);

  // Verify the exact duplicates we expect
  console.log("\n🔍 Verification of Expected Duplicates:");

  // Howard the Duck
  const howardRecords = await new Promise((resolve, reject) => {
    db.all(
      "SELECT rec FROM store WHERE list = 'heroes' AND json_extract(rec, '$.name') = 'Howard the Duck'",
      (err, rows) => {
        if (err) reject(err);
        else resolve(rows);
      }
    );
  });

  console.log(`  Howard the Duck: ${howardRecords.length} records`);
  howardRecords.forEach((row, index) => {
    const record = JSON.parse(row.rec);
    console.log(
      `    Record ${index + 1}: Set ${record.set}, Cards: ${
        record.cards?.length || 0
      }`
    );
  });

  // Count all true duplicates
  const allDuplicates = await new Promise((resolve, reject) => {
    db.all(
      `
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
    `,
      (err, rows) => {
        if (err) reject(err);
        else resolve(rows);
      }
    );
  });

  console.log(`\n📋 All Duplicate Entities (${allDuplicates.length} total):`);
  allDuplicates.forEach((row) => {
    console.log(
      `  ${row.list}: ${row.name} (${row.count} records in sets [${row.sets}])`
    );
  });

  console.log(
    "\n✅ This database should now match your MariaDB structure with correct duplicates only!"
  );

  db.close();
})().catch(console.error);
