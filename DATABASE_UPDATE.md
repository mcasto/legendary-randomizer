# 🎮 Legendary Randomizer Database Update

## Quick Start

To update your database with the latest Legendary card game data:

```bash
php artisan legendary:update-database
```

## Options

```bash
# Preview changes without updating
php artisan legendary:update-database --dry-run

# Skip confirmation prompts  
php artisan legendary:update-database --force

# Combine options
php artisan legendary:update-database --dry-run --force
```

## What This Does

1. 🔄 **Downloads** the latest card data from master-strike repository
2. 🎯 **Processes** duplicate entities (like Howard the Duck appearing in multiple sets)  
3. 🗄️ **Updates** your MariaDB database with any missing records
4. 🧹 **Cleans up** all temporary files automatically

## Safety Features

- ✅ **Dry run mode** - See what would change without making updates
- ✅ **Database transactions** - All changes rolled back on errors  
- ✅ **Smart detection** - Only adds truly missing duplicate records
- ✅ **Automatic cleanup** - No leftover files

## Example Output

```
🚀 Legendary Randomizer Database Update
==========================================

📥 Step 1: Cloning master-strike repository...
✅ Repository cloned successfully

📖 Step 2: Extracting and processing entity data...
✅ Extracted 842 entities
🎯 Created 19 duplicate records

🗄️ Step 3: Creating corrected SQLite database...
✅ SQLite database created with 842 records

🔄 Step 4: Analyzing and updating MariaDB...
✅ Database is already up to date! No new records needed.

🦆 Howard the Duck verification:
  Found 3 records:
  - ID 69: Set '3d'
  - ID 296: Set '9'  
  - ID 297: Set '23'

✅ Database update completed successfully! 🎉
```

Your database will automatically include duplicate entities like Howard the Duck in both the 3D and Dimensions sets! 🦆
