FROM --platform=linux/amd64 php:7.4.13-fpm as eh

LABEL maintainer="Dũng Nguyễn <dung.nguyen@brickmate.vn>"

# install plugins
RUN apt-get update && apt-get install -y autoconf pkg-config libssl-dev libpng-dev libzip-dev zip apt-utils libxml2-dev gnupg apt-transport-https

RUN docker-php-ext-install bcmath
RUN docker-php-ext-install sockets
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install gd
RUN docker-php-ext-install zip

# INSTALL MS ODBC Driver for SQL Server
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/debian/9/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update
RUN ACCEPT_EULA=Y apt-get -y --no-install-recommends install msodbcsql17 unixodbc-dev
RUN pecl install sqlsrv-5.9.0
RUN pecl install pdo_sqlsrv-5.9.0
RUN echo "extension=pdo_sqlsrv.so" >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-pdo_sqlsrv.ini
RUN echo "extension=sqlsrv.so" >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-sqlsrv.ini
RUN apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# INSTALL SUPERVISORD
RUN apt-get update && apt-get install -y nginx supervisor cron

# INSTALL COMPOSER
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# INSTALL XDEBUG
RUN yes | pecl install xdebug-2.9.2 \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini

# INSTALL OPCACHE
RUN docker-php-ext-install opcache

# Install APCu extension
RUN pecl install apcu-5.1.18

# configure and install
RUN docker-php-ext-enable apcu

# Install Exif extension
RUN docker-php-ext-install exif

# Install APCu-BC extension
ADD https://pecl.php.net/get/apcu_bc-1.0.3.tgz /tmp/apcu_bc.tar.gz
RUN mkdir -p /usr/src/php/ext/apcu-bc\
  && tar xf /tmp/apcu_bc.tar.gz -C /usr/src/php/ext/apcu-bc --strip-components=1

# configure and install
RUN docker-php-ext-configure apcu-bc && docker-php-ext-install apcu-bc

RUN rm -rd /usr/src/php/ext/apcu-bc && rm /tmp/apcu_bc.tar.gz

#Load APCU.ini before APC.ini
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini
RUN echo extension=apcu.so > /usr/local/etc/php/conf.d/20-php-ext-apcu.ini

RUN rm /usr/local/etc/php/conf.d/docker-php-ext-apc.ini
RUN echo extension=apc.so > /usr/local/etc/php/conf.d/21-php-ext-apc.ini

# INSTALL PLUGIN FOR PROJECT

# IMAGE
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# COPY FILE TO FOLDER
COPY ./ /var/www/html

COPY docker/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/mime.types /etc/nginx/mime.types
COPY docker/config/php.ini /usr/local/etc/php/php.ini
COPY docker/config/supervisord.conf /etc/supervisor/supervisord.conf

WORKDIR "/var/www/html"
RUN composer self-update --1

# open port 80 443
EXPOSE 80 443

# run supervisord
CMD /usr/bin/supervisord -n -c /etc/supervisor/supervisord.conf
