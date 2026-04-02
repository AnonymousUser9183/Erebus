#!/bin/bash
set -euo pipefail

##############################################
# © 2026 Erebus Development Team             #
# Author: AnonymousUser9183                  #
##############################################
# Maintenance Enable/Disable Script          #
##############################################

##############################################
# Configurable Variables                     #
##############################################
PROJECT_DIR="/var/www/Erebus"
FLAG_FILE="storage/framework/down"

##############################################
# Function: Enable Maintenance Mode          #
##############################################
enable_maintenance() {
    echo "Enabling maintenance mode..."
    cd "$PROJECT_DIR" || { echo "Error: Could not navigate to $PROJECT_DIR"; exit 1; }
    
    # Put Laravel into maintenance mode.
    sudo php artisan down

    # Create the flag file for nginx.
    touch "$FLAG_FILE"
    
    echo "Maintenance mode enabled. Reloading nginx..."
    sudo systemctl reload nginx
    echo "Nginx reloaded."
}

##############################################
# Function: Disable Maintenance Mode         #
##############################################
disable_maintenance() {
    echo "Disabling maintenance mode..."
    cd "$PROJECT_DIR" || { echo "Error: Could not navigate to $PROJECT_DIR"; exit 1; }
    
    # Bring Laravel back up.
    sudo php artisan up

    # Remove the flag file.
    sudo rm -f "$FLAG_FILE"
    
    echo "Maintenance mode disabled. Reloading nginx..."
    sudo systemctl reload nginx
    echo "Nginx reloaded."
}

##############################################
# Main Menu and User Interaction             #
##############################################
echo "Maintenance Mode Manager"
echo "-------------------------"
echo "1) Enable Maintenance Mode"
echo "2) Disable Maintenance Mode"
echo "-------------------------"
read -rp "Enter your choice (1 or 2): " choice

case "$choice" in
    1)
        enable_maintenance
        ;;
    2)
        disable_maintenance
        ;;
    *)
        echo "Invalid choice. Please enter 1 or 2."
        exit 1
        ;;
esac

exit 0
