# Legendary Randomizer Database Update Scripts

This directory contains automated scripts to keep your MariaDB database synchronized with the latest master-strike card data.

## Quick Usage

### One-time Update
```bash
./update-db.sh
```

### Manual PHP Execution
```bash
php auto-update-database.php
```

## What These Scripts Do

1. **Clone/Update** the master-strike repository with latest card data
2. **Extract** all entity data with proper duplicate handling
3. **Process** entities that appear in multiple sets (like Howard the Duck)
4. **Compare** with your current MariaDB database
5. **Add** any missing duplicate entities automatically

## Scripts Included

- **`auto-update-database.php`** - Main update script that handles everything
- **`update-db.sh`** - Simple shell wrapper for easy execution
- **`scheduled-update.sh`** - For running as a cron job (optional)

## Automatic Scheduling (Optional)

To automatically update your database daily at 2 AM:

```bash
# Edit your crontab
crontab -e

# Add this line:
0 2 * * * /full/path/to/legendary-randomizer/master-strike-parse/my-parser-scripts/scheduled-update.sh
```

## What Gets Updated

The scripts will automatically add duplicate entities for characters like:

- **Howard the Duck** (appears in 3D and Dimensions sets)
- **Man-Thing** (appears in 3D and Dimensions sets)  
- **Core Set Heroes** (appear in Core Set and multiple anniversary sets)
- **And any future duplicate entities** added to master-strike

## Safety Features

- ✅ **Database transactions** - All changes are rolled back on error
- ✅ **Duplicate detection** - Only adds truly missing records
- ✅ **ID management** - Properly assigns unique IDs
- ✅ **Field handling** - Copies required fields like `always_leads` for masterminds
- ✅ **Verification** - Shows before/after counts and Howard the Duck status

## Output Example

```
🚀 Legendary Randomizer: Automatic Database Update
================================================

📥 Step 1: Updating master-strike repository...
📖 Step 2: Extracting corrected entity data...
🗄️ Step 3: Creating corrected SQLite database...
🔍 Step 4: Analyzing database differences...

✅ Database is up to date! No new duplicate entities found.
🦆 Howard the Duck verification:
  Found 3 records:
  - ID 69: Set '3d'
  - ID 296: Set '9'
  - ID 297: Set '23'
```

Your database is now fully automated! 🎉
