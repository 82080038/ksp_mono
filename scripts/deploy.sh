#!/bin/bash
# Deployment script for KSP Mono

ENV="$1"

case "$ENV" in
    "staging")
        echo "Deploying to staging environment"
        cp config/staging_config.php config/config.php
        ;;
    "production")
        echo "Deploying to production environment"
        cp config/production_config.php config/config.php
        ;;
    *)
        echo "Deploying to development environment"
        cp config/development_config.php config/config.php
        ;;
esac

# Common deployment steps
php artisan migrate --force
php artisan cache:clear
