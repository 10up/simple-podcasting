#! /bin/bash
CURRENT_WORKING_DIR="$(pwd)"
PROJECT_DIR="$(dirname "$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )" )"
SLUG="$(basename "$PROJECT_DIR")"
PROJECT_TYPE=plugin
if [ -f "$PROJECT_DIR/style.css" ]; then
	PROJECT_TYPE=theme
fi

if [[ ! -z "$CODESPACE_NAME" ]]
then
	SITE_HOST="https://${CODESPACE_NAME}-8080.githubpreview.dev"
else
	SITE_HOST="http://localhost:8080"
fi

exec 3>&1 4>&2
trap 'exec 2>&4 1>&3' 0 1 2 3
exec 1>setup.log 2>&1

touch /root/.bashrc

# Prepare a nice name from project name for the site title.
function getTitleFromSlug()
{
    local _slug=${SLUG//-/ }
    local __slug=${_slug//_/ }
    local ___slug=( $__slug )
    echo "${___slug[@]^}"
}

apt-get update && \
apt-get upgrade -y && \
apt-get install -y git && \
apt-get install -y sudo && \
apt-get install -y zip

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install WP CLI
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
        chmod +x wp-cli.phar && \
        mv wp-cli.phar /usr/local/bin/wp

echo 'alias wp="wp --allow-root"' >> /root/.bashrc

# Install nvm and node
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.35.3/install.sh | bash
export NVM_DIR="$HOME/.nvm" && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" && nvm install --lts

# Install dependencies
cd /var/www/html/wp-content/${PROJECT_TYPE}s/${SLUG}/
npm i && npm run build
composer i

# Install WordPress and activate the plugin/theme.
cd /var/www/html/
echo "Setting up WordPress at $SITE_HOST"
wp --allow-root db reset --yes
wp --allow-root core install --url="$SITE_HOST" --title="$(getTitleFromSlug) Development" --admin_user="admin" --admin_email="admin@example.com" --admin_password="password" --skip-email

echo "Activate $SLUG"
wp --allow-root $PROJECT_TYPE activate $SLUG

# Install Xdebug
pecl install "xdebug" || true && docker-php-ext-enable xdebug

exit 0
