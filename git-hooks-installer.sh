#!/bin/bash

# Copy pre-commit hook script to .git/hooks directory
cp git-hooks-store/pre-commit .git/hooks/pre-commit

# Make it executable
chmod +x .git/hooks/pre-commit

# Hooks installation successful
echo "Git hooks installed successfully."
