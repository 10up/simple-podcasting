#!  /bin/bash

#Site configuration options
SITE_TITLE="Simple Podcasting Development Site"
ADMIN_USER=admin
ADMIN_PASS=password
ADMIN_EMAIL="admin@example.com"

echo "Setting up WordPress"

sudo chown www-data: -R /var/www/html

cd /var/www/html

rm -f wp-config.php

wp core download --force
wp config create --dbhost="db" --dbname="wordpress" --dbuser="admin" --dbpass="password" --skip-check
wp db reset --yes
wp core install --url="http://localhost:8080" --title="$SITE_TITLE" --admin_user="$ADMIN_USER" --admin_email="$ADMIN_EMAIL" --admin_password="$ADMIN_PASS" --skip-email
wp plugin activate simple-podcasting

echo "Install plugin dependencies"

cd /var/www/html/wp-content/plugins/simple-podcasting

source ~/.nvm/nvm.sh
nvm install 10
nvm use 10
nvm alias default 10
npm install
npm run build

composer install