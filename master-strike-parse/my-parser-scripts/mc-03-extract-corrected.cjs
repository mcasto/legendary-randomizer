const fs = require("fs");
const path = require("path");
const nedb = require("nedb-promises");

console.log(
  "🎯 Processing master-strike data with CORRECT duplicate handling...\n"
);

// Database setup
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

const db = {};

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

// Helper function to process entities - ONLY duplicate if set is an array with multiple values
function processEntitiesCorrectly(entities, entityType, fileSetId) {
  if (!entities || !Array.isArray(entities)) return [];

  const processedEntities = [];
  let duplicateCount = 0;

  for (let entity of entities) {
    // CHECK: Does this entity have set as an array with multiple values?
    if (entity.set && Array.isArray(entity.set) && entity.set.length > 1) {
      // TRUE DUPLICATE: Entity explicitly marked for multiple sets
      console.log(
        `    🎯 TRUE DUPLICATE: ${entity.name} → sets: ${entity.set.join(", ")}`
      );

      for (let setId of entity.set) {
        const duplicatedEntity = { ...entity };
        duplicatedEntity.set = parseInt(setId);
        processedEntities.push(duplicatedEntity);
        duplicateCount++;
      }
    } else {
      // SINGLE SET ENTITY: Normal processing
      const singleEntity = { ...entity };
      singleEntity.set = entity.set || fileSetId;
      processedEntities.push(singleEntity);
    }
  }

  return {
    entities: processedEntities,
    duplicateCount:
      duplicateCount -
      (duplicateCount > 0
        ? entities.filter(
            (e) => e.set && Array.isArray(e.set) && e.set.length > 1
          ).length
        : 0),
  };
}

(async () => {
  // Clean and create databases
  for (let [key, dbFile] of Object.entries(dbFiles)) {
    if (fs.existsSync(dbFile)) fs.unlinkSync(dbFile);
    db[key] = nedb.create(dbFile);
  }

  // Process card files
  const cardsDir = path.join(
    __dirname,
    "master-strike/packages/data/src/definitions/cards"
  );
  const cardFiles = fs
    .readdirSync(cardsDir)
    .filter((file) => file.endsWith(".ts") && file !== "index.ts")
    .map((file) => ({
      name: path.basename(file, ".ts"),
      path: path.join(cardsDir, file),
    }));

  console.log(`Processing ${cardFiles.length} card files...\n`);

  let totalEntities = 0;
  let totalDuplicates = 0;
  let filesWithDuplicates = 0;

  for (let { name: fileName, path: filePath } of cardFiles) {
    console.log(`📁 ${fileName}.ts (Set ID: ${fileName})`);

    const data = parseTypeScriptFile(filePath);
    if (!data) {
      console.log("  ❌ Failed to parse file\n");
      continue;
    }

    const fileSetId = data.id;
    let fileHasDuplicates = false;

    // Process each entity type
    const entityTypes = [
      "heroes",
      "masterminds",
      "villains",
      "henchmen",
      "schemes",
      "bystanders",
    ];

    for (let entityType of entityTypes) {
      if (data[entityType]) {
        const result = processEntitiesCorrectly(
          data[entityType],
          entityType,
          fileSetId
        );
        const originalCount = data[entityType].length;
        const finalCount = result.entities.length;
        const duplicatesCreated = result.duplicateCount;

        if (result.entities.length > 0) {
          await db[entityType].insertMany(result.entities);

          if (duplicatesCreated > 0) {
            console.log(
              `  ✅ ${entityType}: ${originalCount} → ${finalCount} (+${duplicatesCreated} duplicates)`
            );
            fileHasDuplicates = true;
            totalDuplicates += duplicatesCreated;
          } else {
            console.log(`  ✅ ${entityType}: ${finalCount} records`);
          }

          totalEntities += finalCount;
        }
      }
    }

    if (fileHasDuplicates) filesWithDuplicates++;
    console.log("");
  }

  // Summary
  console.log("🎉 Processing Complete!\n");
  console.log("📊 Summary:");
  console.log(`  Files processed: ${cardFiles.length}`);
  console.log(`  Files with duplicates: ${filesWithDuplicates}`);
  console.log(`  Total entities: ${totalEntities}`);
  console.log(`  Duplicate records created: ${totalDuplicates}`);

  // Verify Howard the Duck specifically
  const howardRecords = await db.heroes.find({ name: "Howard the Duck" });
  console.log(`\n🦆 Howard the Duck verification:`);
  console.log(`  Found ${howardRecords.length} records`);
  for (let record of howardRecords) {
    console.log(`  - Set ${record.set}: ${record.cards?.length || 0} cards`);
  }

  console.log("\n✅ Corrected extraction complete!");
})();
