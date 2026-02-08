#!/bin/bash
# Rotate form performance logs
LOG_FILE="/var/www/html/ksp_mono/logs/form_performance.log"
MAX_SIZE=1048576 # 1MB

if [ -f "$LOG_FILE" ]; then
    CURRENT_SIZE=$(stat -c%s "$LOG_FILE")
    
    if [ "$CURRENT_SIZE" -gt "$MAX_SIZE" ]; then
        mv "$LOG_FILE" "$LOG_FILE.$(date +%Y%m%d%H%M%S)"
        touch "$LOG_FILE"
        chown www-data:www-data "$LOG_FILE"
        chmod 644 "$LOG_FILE"
    fi
fi
