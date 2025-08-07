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

// Map set IDs to set values
const setIdToValueMap = {};

// Helper function to initialize set mapping
async function initializeSetMapping() {
  if (fs.existsSync(dbFiles.sets)) {
    const sets = await db.sets.find({});
    sets.forEach((set) => {
      setIdToValueMap[set.id] = set.value;
    });
    console.log(`📋 Loaded ${sets.length} set mappings`);
  }
}

// Helper function to process entities - ONLY duplicate if they have same name, same cards, and appear in multiple sets
function processEntitiesCorrectly(
  entities,
  entityType,
  fileSetId,
  fileSetValue
) {
  if (!entities || !Array.isArray(entities)) return [];

  const processedEntities = [];
  let duplicateCount = 0;

  for (let entity of entities) {
    // CHECK: Does this entity have set as an array with multiple values?
    if (entity.set && Array.isArray(entity.set) && entity.set.length > 1) {
      // TRUE DUPLICATE: Entity explicitly marked for multiple sets
      const setValues = entity.set.map(
        (setId) => setIdToValueMap[setId] || setId
      );

      // Get card names for verification
      const cardNames = entity.cards
        ? entity.cards.map((c) => c.name).sort()
        : [];

      console.log(
        `    🎯 TRUE DUPLICATE: ${entity.name} → sets: ${setValues.join(
          ", "
        )} (IDs: ${entity.set.join(", ")}) [${cardNames.length} cards]`
      );

      for (let setId of entity.set) {
        const duplicatedEntity = { ...entity };
        // Use set VALUE, not set ID
        duplicatedEntity.set = setIdToValueMap[setId] || setId;
        processedEntities.push(duplicatedEntity);
        duplicateCount++;
      }
    } else {
      // SINGLE SET ENTITY: Normal processing
      const singleEntity = { ...entity };
      // Use the file set value, not ID
      singleEntity.set = fileSetValue || entity.set || fileSetId;
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

  // First, we need to create a sets database to establish the ID to value mapping
  console.log("📋 Creating sets mapping...");

  const setsData = [
    { id: 1, value: "coreset", label: "Core Set" },
    { id: 2, value: "promo", label: "Promo Cards" },
    { id: 3, value: "darkcity", label: "Dark City" },
    { id: 4, value: "ff", label: "Fantastic Four" },
    { id: 5, value: "pttr", label: "Paint the Town Red" },
    { id: 6, value: "gotg", label: "Guardians of the Galaxy" },
    { id: 7, value: "xmen", label: "X-Men" },
    { id: 8, value: "sw1", label: "Secret Wars Volume 1" },
    { id: 9, value: "3d", label: "Playable Marvel 3D Trading Cards" },
    {
      id: 10,
      value: "captainamerica",
      label: "Captain America 75th Anniversary",
    },
    { id: 11, value: "deadpool", label: "Deadpool" },
    { id: 12, value: "noir", label: "Noir" },
    { id: 13, value: "sw2", label: "Secret Wars Volume 2" },
    { id: 14, value: "villains", label: "Villains" },
    { id: 15, value: "civilwar", label: "Civil War" },
    { id: 16, value: "realmofkings", label: "Realm of Kings" },
    { id: 17, value: "revelations", label: "Revelations" },
    { id: 18, value: "doctorstrange", label: "Doctor Strange" },
    { id: 19, value: "champions", label: "Champions" },
    { id: 20, value: "fearitself", label: "Fear Itself" },
    { id: 21, value: "wwhulk", label: "World War Hulk" },
    { id: 22, value: "antman", label: "Ant-Man" },
    { id: 23, value: "dimensions", label: "Dimensions" },
    { id: 24, value: "blackwidow", label: "Black Widow" },
    { id: 25, value: "blackpanther", label: "Black Panther" },
    { id: 26, value: "venom", label: "Venom" },
    { id: 27, value: "spiderhomecoming", label: "Spider-Man Homecoming" },
    { id: 28, value: "heroesofasgard", label: "Heroes of Asgard" },
    { id: 29, value: "intothecosmos", label: "Into the Cosmos" },
    { id: 30, value: "shield", label: "S.H.I.E.L.D." },
    { id: 31, value: "midnightsons", label: "Midnight Sons" },
    { id: 32, value: "weaponx", label: "Weapon X" },
    { id: 33, value: "newmutants", label: "New Mutants" },
    { id: 34, value: "messiahcomplex", label: "Messiah Complex" },
    { id: 35, value: "annihilation", label: "Annihilation" },
    { id: 36, value: "msaw", label: "Marvel Studios's Ant-Man and The Wasp" },
    {
      id: 37,
      value: "msgotg",
      label: "Marvel Studios' Guardians of the Galaxy",
    },
    { id: 38, value: "msis", label: "Marvel Studios' Infinity Saga" },
    { id: 39, value: "mswi", label: "Marvel Studios' What If...?" },
    { id: 40, value: "marvelstudios", label: "Marvel Studios Phase 1" },
    { id: 41, value: "2099", label: "Marvel 2099" },
  ];

  await db.sets.insertMany(setsData);
  await initializeSetMapping();

  // Collect all entities first to detect cross-file duplicates
  const allEntitiesByType = {
    heroes: [],
    masterminds: [],
    villains: [],
    henchmen: [],
    schemes: [],
    bystanders: [],
  };

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

  // First pass: collect all entities and handle set arrays
  for (let { name: fileName, path: filePath } of cardFiles) {
    const data = parseTypeScriptFile(filePath);
    if (!data) continue;

    const fileSetValue = fileName;
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
        for (let entity of data[entityType]) {
          // Check if entity has set array (like Howard the Duck)
          if (
            entity.set &&
            Array.isArray(entity.set) &&
            entity.set.length > 1
          ) {
            // Handle set array duplicates
            const setValues = entity.set.map(
              (setId) => setIdToValueMap[setId] || setId
            );
            const cardNames = entity.cards
              ? entity.cards.map((c) => c.name).sort()
              : [];

            console.log(
              `    🎯 SET ARRAY DUPLICATE: ${
                entity.name
              } → sets: ${setValues.join(", ")} [${cardNames.length} cards]`
            );

            // Create entity for each set
            for (let setId of entity.set) {
              const entityWithContext = {
                ...entity,
                fileSetValue: setIdToValueMap[setId] || setId,
                fileSetId: setId,
                isDuplicate: true,
              };
              delete entityWithContext.set; // Remove the array
              allEntitiesByType[entityType].push(entityWithContext);
            }
          } else {
            // Regular entity - add file context
            const entityWithContext = {
              ...entity,
              fileSetValue,
              fileSetId: data.id,
              isDuplicate: false,
            };
            allEntitiesByType[entityType].push(entityWithContext);
          }
        }
      }
    }
  }

  console.log("🔍 Analyzing entities for true duplicates...\n");

  let totalEntities = 0;
  let totalDuplicates = 0;
  let filesWithDuplicates = 0;

  // Second pass: detect cross-file duplicates and process all entities
  for (let entityType of Object.keys(allEntitiesByType)) {
    const entities = allEntitiesByType[entityType];
    if (entities.length === 0) continue;

    console.log(`📋 Processing ${entityType}...`);

    // Separate already-processed duplicates from regular entities
    const alreadyDuplicates = entities.filter((e) => e.isDuplicate);
    const regularEntities = entities.filter((e) => !e.isDuplicate);

    // Group regular entities by name and card signature to find cross-file duplicates
    const entityGroups = {};

    for (let entity of regularEntities) {
      const cardNames = entity.cards
        ? entity.cards.map((c) => c.name).sort()
        : [];
      const cardSignature = JSON.stringify(cardNames);
      const key = `${entity.name}|${cardSignature}`;

      if (!entityGroups[key]) {
        entityGroups[key] = [];
      }
      entityGroups[key].push(entity);
    }

    // Process already identified duplicates (from set arrays)
    for (let entity of alreadyDuplicates) {
      const processedEntity = { ...entity };
      processedEntity.set = entity.fileSetValue;
      // Remove temporary fields
      delete processedEntity.fileSetValue;
      delete processedEntity.fileSetId;
      delete processedEntity.isDuplicate;

      await db[entityType].insert(processedEntity);
      totalEntities++;
    }

    // Process cross-file entity groups
    for (let [key, group] of Object.entries(entityGroups)) {
      const [entityName, cardSignature] = key.split("|");

      if (group.length > 1) {
        // Potential duplicate - check if in different sets
        const setsInvolved = [...new Set(group.map((e) => e.fileSetValue))];

        if (setsInvolved.length > 1) {
          // TRUE CROSS-FILE DUPLICATE: Same name, same cards, multiple sets
          const cardNames = JSON.parse(cardSignature);
          console.log(
            `    🎯 CROSS-FILE DUPLICATE: ${entityName} → sets: ${setsInvolved.join(
              ", "
            )} [${cardNames.length} cards]`
          );

          // Add all instances
          for (let entity of group) {
            const processedEntity = { ...entity };
            processedEntity.set = entity.fileSetValue;
            // Remove temporary fields
            delete processedEntity.fileSetValue;
            delete processedEntity.fileSetId;
            delete processedEntity.isDuplicate;

            await db[entityType].insert(processedEntity);
            totalEntities++;
          }
          totalDuplicates += group.length - 1; // Extra records beyond the first
        } else {
          // Same set, just add once (shouldn't happen, but handle it)
          const entity = group[0];
          const processedEntity = { ...entity };
          processedEntity.set = entity.fileSetValue;
          delete processedEntity.fileSetValue;
          delete processedEntity.fileSetId;
          delete processedEntity.isDuplicate;

          await db[entityType].insert(processedEntity);
          totalEntities++;
        }
      } else {
        // Single entity
        const entity = group[0];
        const processedEntity = { ...entity };
        processedEntity.set = entity.fileSetValue;
        delete processedEntity.fileSetValue;
        delete processedEntity.fileSetId;
        delete processedEntity.isDuplicate;

        await db[entityType].insert(processedEntity);
        totalEntities++;
      }
    }

    console.log(`  ✅ Processed ${entities.length} ${entityType} entities`);
  }

  // Count duplicates from set arrays
  const setArrayDuplicates = Object.values(allEntitiesByType)
    .flat()
    .filter((e) => e.isDuplicate).length;

  // Adjust total duplicates count
  const groupNames = new Set();
  Object.values(allEntitiesByType)
    .flat()
    .filter((e) => e.isDuplicate)
    .forEach((e) => {
      groupNames.add(e.name);
    });

  totalDuplicates += setArrayDuplicates - groupNames.size;

  // Summary
  console.log("\n🎉 Processing Complete!\n");
  console.log("📊 Summary:");
  console.log(`  Files processed: ${cardFiles.length}`);
  console.log(`  Total entities: ${totalEntities}`);
  console.log(`  True duplicate records created: ${totalDuplicates}`);

  // Verify Howard the Duck specifically
  const howardRecords = await db.heroes.find({ name: "Howard the Duck" });
  console.log(`\n🦆 Howard the Duck verification:`);
  console.log(`  Found ${howardRecords.length} records`);
  for (let record of howardRecords) {
    console.log(`  - Set ${record.set}: ${record.cards?.length || 0} cards`);
  }

  console.log("\n✅ Corrected extraction complete!");
})();
