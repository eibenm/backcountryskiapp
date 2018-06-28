FROM debian:stretch

# Installing apache2
RUN apt-get update \
    && apt-get install -y --no-install-recommends apache2 nano \
    && rm -rf /var/lib/apt/lists/*

# Suppressing apache 2 warning:
# 
# Could not reliably determine the server's fully qualified domain name, 
# using 172.17.0.2. Set the 'ServerName' directive globally to suppress this message
RUN { \
    echo ''; \
    echo 'ServerName localhost'; \
    echo ''; \
} | tee -a /etc/apache2/apache2.conf

# Setup apache
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

# Registering package directory
RUN echo "deb http://ftp.de.debian.org/debian stretch main" >> /etc/apt/sources.list

# Install php 7.0
RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y \
        php7.0 \
        php7.0-curl \
        php7.0-mbstring \
        php7.0-mcrypt \
        php7.0-odbc \
        php7.0-sqlite3 \
        php7.0-xml \
        php7.0-zip \
        php-xdebug

# Configuring xdebug for remote debugging
RUN { \
    echo 'xdebug.remote_enable = 1'; \
    echo 'xdebug.remote_autostart = 1'; \
    echo 'xdebug.remote_connect_back = 0'; \
    echo 'xdebug.remote_host = 192.168.1.178'; \
    echo 'xdebug.remote_port=9000'; \
} | tee -a /etc/php/7.0/apache2/conf.d/20-xdebug.ini

# Apache config
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]