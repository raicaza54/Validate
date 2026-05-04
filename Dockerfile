FROM php:7.4-apache

RUN apt-get update && apt-get install -y \
    libmemcached-dev \
    libgearman-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    zlib1g-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) mysqli pdo_mysql zip gd

RUN pecl install memcached \
    && docker-php-ext-enable memcached

RUN pecl install gearman-2.1.0 \
    && docker-php-ext-enable gearman

RUN a2enmod rewrite \
    && sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

RUN { \
    echo "upload_max_filesize=256M"; \
    echo "post_max_size=256M"; \
    echo "memory_limit=512M"; \
    echo "max_execution_time=600"; \
    echo "max_input_time=600"; \
} > /usr/local/etc/php/conf.d/uploads.ini
