#! /bin/bash

touch /root/.bashrc

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

# Install Xdebug
pecl install "xdebug" || true && docker-php-ext-enable xdebug

exit 0
