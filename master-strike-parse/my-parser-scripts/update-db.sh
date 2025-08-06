#!/bin/bash

# Legendary Randomizer: Automatic Database Update Script
# Usage: ./update-db.sh

echo "🚀 Legendary Randomizer: Starting automatic database update..."
echo "============================================================"

# Change to the scripts directory
cd "$(dirname "$0")"

# Run the PHP script
php auto-update-database.php

echo ""
echo "🎯 Update process completed!"
echo ""
echo "💡 To run this update again in the future, simply execute:"
echo "   ./master-strike-parse/my-parser-scripts/update-db.sh"
echo ""
