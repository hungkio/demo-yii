FROM php:8.2-apache

ARG APP_NAME
ENV APP_NAME=$APP_NAME

RUN apt-get update && apt-get install -y \
    git \
    curl \
    nano \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    apache2 \
    unzip

RUN a2enmod rewrite
RUN service apache2 restart

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt-get update && apt-get install -y nodejs
RUN apt-get install -y npm

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN apt-get update && \
    apt-get install -y \
    libmagickwand-dev --no-install-recommends && \
    pecl install imagick && \
    docker-php-ext-enable imagick
RUN apt-get install -y ghostscript

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY default.conf /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/${APP_NAME}
COPY . /var/www/html/

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]

EXPOSE 80
