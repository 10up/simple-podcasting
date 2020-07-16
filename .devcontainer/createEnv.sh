#!  /bin/bash

if test -f "style.css"; then
    TYPE=theme
else
    TYPE=plugin
fi

/bin/cat <<EOM > .env
SLUG=$1
PROJECT_TYPE=$TYPE
SITE_HOST=http://localhost
ENABLE_XDEBUG=false
ADMIN_USER=admin
ADMIN_PASS=password
ADMIN_EMAIL=admin@example.com
MYSQL_DATABASE=wordpress
MYSQL_USER=admin
MYSQL_PASSWORD=password
MYSQL_ROOT_PASSWORD=password
NODE_VERSION=10
USE_COMPOSER=true
USE_NODE=true
EOM
