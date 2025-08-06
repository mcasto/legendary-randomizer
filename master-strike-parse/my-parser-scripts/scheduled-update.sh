#!/bin/bash

# Legendary Randomizer: Scheduled Database Update
# This script can be run as a cron job to automatically keep your database updated
#
# To add to cron, run: crontab -e
# Then add: 0 2 * * * /path/to/your/legendary-randomizer/master-strike-parse/my-parser-scripts/scheduled-update.sh
# This will run at 2 AM every day

# Capture the date for logging
DATE=$(date '+%Y-%m-%d %H:%M:%S')
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LOG_FILE="$SCRIPT_DIR/update.log"

echo "[$DATE] Starting scheduled database update..." >> "$LOG_FILE"

# Change to the scripts directory
cd "$SCRIPT_DIR"

# Run the update and capture output
php auto-update-database.php >> "$LOG_FILE" 2>&1

# Check if the update was successful
if [ $? -eq 0 ]; then
    echo "[$DATE] Database update completed successfully" >> "$LOG_FILE"
else
    echo "[$DATE] Database update failed with exit code $?" >> "$LOG_FILE"
fi

echo "[$DATE] Scheduled update finished" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"

# Keep only last 50 lines of log to prevent it from growing too large
tail -n 50 "$LOG_FILE" > "$LOG_FILE.tmp" && mv "$LOG_FILE.tmp" "$LOG_FILE"
