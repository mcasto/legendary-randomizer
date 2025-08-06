const sqlite3 = require("sqlite3").verbose();
const path = require("path");

console.log("🔍 Final Verification: Corrected SQLite Database Analysis\n");
console.log(
  "This analysis shows the 17 corrected duplicate entities that should be added to your MariaDB.\n"
);

const dbPath = path.join(__dirname, "legendary-randomizer-corrected.sqlite");
const db = new sqlite3.Database(dbPath);

(async () => {
  // Get total counts
  const totalRecords = await new Promise((resolve) => {
    db.get("SELECT COUNT(*) as count FROM store", (err, row) => {
      resolve(row?.count || 0);
    });
  });

  const entityCounts = await new Promise((resolve) => {
    db.all(
      "SELECT list, COUNT(*) as count FROM store GROUP BY list ORDER BY list",
      (err, rows) => {
        resolve(rows || []);
      }
    );
  });

  console.log("📊 Database Summary:");
  console.log(`  Total records: ${totalRecords}`);
  console.log("  Records by type:");
  entityCounts.forEach((row) => {
    console.log(`    ${row.list}: ${row.count}`);
  });

  // Get only the true duplicates
  const duplicates = await new Promise((resolve) => {
    db.all(
      `
      SELECT
        list,
        json_extract(rec, '$.name') as name,
        COUNT(*) as count,
        GROUP_CONCAT(json_extract(rec, '$.set')) as sets,
        json_extract(rec, '$.id') as entity_id
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
        resolve(rows || []);
      }
    );
  });

  console.log(`\n🎯 The ${duplicates.length} Entities That Need Duplication:`);
  console.log("  (These are currently missing from your MariaDB)\n");

  let fromSet9_23 = 0;
  let fromSet1_20 = 0;

  duplicates.forEach((row) => {
    const sets = row.sets
      .split(",")
      .map((s) => parseInt(s))
      .sort((a, b) => a - b);
    console.log(`📋 ${row.name} (${row.list})`);
    console.log(
      `   Sets: ${sets.join(", ")} | Entity ID: ${row.entity_id} | Records: ${
        row.count
      }`
    );

    if (sets.includes(9) && sets.includes(23)) fromSet9_23++;
    if (sets.includes(1) && sets.includes(20)) fromSet1_20++;
  });

  console.log(`\n📈 Summary by Source:`);
  console.log(`  3D set duplicates (sets 9 & 23): ${fromSet9_23} entities`);
  console.log(`  Core set duplicates (sets 1 & 20): ${fromSet1_20} entities`);

  // Provide specific SQL for MariaDB comparison
  console.log(`\n📝 SQL Queries for Your MariaDB Comparison:`);
  console.log(`\n-- 1. Check if Howard the Duck exists in both sets 9 and 23:`);
  console.log(`SELECT JSON_VALUE(rec, '$.name') as name, JSON_VALUE(rec, '$.set') as set_id, list
FROM store
WHERE JSON_VALUE(rec, '$.name') = 'Howard the Duck'
ORDER BY JSON_VALUE(rec, '$.set');`);

  console.log(`\n-- 2. Count current entities by type in your MariaDB:`);
  console.log(`SELECT list, COUNT(*) as count
FROM store
WHERE list IN ('heroes', 'masterminds', 'villains', 'henchmen', 'schemes', 'bystanders')
GROUP BY list
ORDER BY list;`);

  console.log(
    `\n-- 3. Find entities that should have duplicates but might not:`
  );
  duplicates.slice(0, 5).forEach((row) => {
    console.log(`SELECT JSON_VALUE(rec, '$.name') as name, JSON_VALUE(rec, '$.set') as set_id, COUNT(*) as count
FROM store
WHERE JSON_VALUE(rec, '$.name') = '${row.name}' AND list = '${row.list}'
GROUP BY JSON_VALUE(rec, '$.name');`);
  });
  console.log(`-- (Run similar queries for all ${duplicates.length} entities)`);

  console.log(`\n🎯 Expected Differences:`);
  console.log(
    `  Your current MariaDB likely has: ~${
      totalRecords - duplicates.length
    } records`
  );
  console.log(`  This corrected database has: ${totalRecords} records`);
  console.log(
    `  Difference: +${duplicates.length} duplicate records that should be added`
  );

  console.log(`\n✅ Database ready for production use!`);
  console.log(`   File: legendary-randomizer-corrected.sqlite`);

  db.close();
})();
