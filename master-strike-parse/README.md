# Legendary Randomizer Database Update Scripts

This directory contains Node.js scripts to update the Laravel database with the latest Legendary card game data from the master-strike repository.

## Usage

### Laravel Artisan Command (Recommended)

```bash
# Update database with latest data
php artisan legendary:update-database

# Preview what would be changed without making updates  
php artisan legendary:update-database --dry-run

# Skip confirmation prompts
php artisan legendary:update-database --force

# Combine options
php artisan legendary:update-database --dry-run --force
```

### Direct Script Execution (Advanced)

```bash
# 1. Clone repository
node mc-01-clone-repo.cjs

# 2. Extract and process entities  
node mc-03-extract-corrected.cjs

# 3. Create SQLite database
node mc-07-create-sqlite-corrected.cjs
```

## What It Does

1. **Clones** the latest master-strike repository
2. **Extracts** all entity data from TypeScript card definitions  
3. **Processes** duplicate entities (like Howard the Duck in multiple sets)
4. **Creates** a SQLite database with corrected duplicate records
5. **Compares** with your current MariaDB database
6. **Adds** any missing duplicate entity records
7. **Cleans up** all temporary files

## Duplicate Entity Handling

The scripts properly handle entities that appear in multiple sets:

- **Howard the Duck**: 3D set (9) + Dimensions set (23)
- **Man-Thing**: 3D set (9) + Dimensions set (23)  
- **Core Set Heroes**: Core Set (1) + Anniversary sets (20)
- **And more**: Any future duplicate entities from master-strike

## Safety Features

- ✅ **Database transactions** - Rollback on errors
- ✅ **Dry run mode** - Preview changes without updating
- ✅ **Smart detection** - Only adds truly missing records
- ✅ **ID management** - Proper unique ID assignment  
- ✅ **Field copying** - Handles special fields like `always_leads`
- ✅ **Automatic cleanup** - Removes all temporary files

## Dependencies

- **Node.js** (for running the scripts)
- **NeDB** (for intermediate data processing)
- **TypeScript parsing** (for master-strike card definitions)
- **SQLite3** (for temporary database creation)

Dependencies are automatically installed via `npm install`.

## Files

- **`mc-01-clone-repo.cjs`** - Clones master-strike repository
- **`mc-03-extract-corrected.cjs`** - Extracts and processes entities with duplicate handling
- **`mc-07-create-sqlite-corrected.cjs`** - Creates corrected SQLite database
- **`package.json`** - Node.js dependencies

## Laravel Integration

The Artisan command integrates seamlessly with your Laravel app:

- Uses Laravel's database connections
- Follows Laravel naming conventions  
- Provides rich console output
- Handles errors gracefully
- Cleans up automatically

Your Legendary Randomizer database will stay perfectly synchronized with the latest official card data! 🎉
