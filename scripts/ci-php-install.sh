#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

apt-get update \
&& apt-get install -y \
&& apt-get autoremove -y \
&& apt-get install libpq-dev -y \
&& docker-php-ext-install postgresql pdo pdo_pgsql \
&& apt-get install git -y\
&& apt-get install zip -y\
&& curl -sS https://get.symfony.com/cli/installer | bash \
&& mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
&& curl -sS https://getcomposer.org/installer | php \
&& ls
