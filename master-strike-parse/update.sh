#!/bin/bash

# Legendary Randomizer Database Update
# Simple wrapper script for the Node.js updater

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "🚀 Legendary Randomizer Database Update"
echo "======================================="
echo ""

# Check if node is available
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed or not in PATH"
    echo "Please install Node.js first: https://nodejs.org/"
    exit 1
fi

# Check if dependencies are installed
if [ ! -d "$SCRIPT_DIR/node_modules" ]; then
    echo "📦 Installing dependencies..."
    cd "$SCRIPT_DIR" && npm install
    if [ $? -ne 0 ]; then
        echo "❌ Failed to install dependencies"
        exit 1
    fi
    echo ""
fi

# Run the update script
echo "🎯 Starting database update..."
echo ""
cd "$SCRIPT_DIR" && node update-database.cjs

echo ""
echo "💡 To run this again: ./master-strike-parse/update.sh"
echo "   Or directly: node ./master-strike-parse/update-database.cjs"
