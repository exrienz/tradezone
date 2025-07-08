#!/bin/bash
set -e
php /var/www/html/app/scripts/init_db.php
php /var/www/html/worker/monitor.php &
exec apache2-foreground
