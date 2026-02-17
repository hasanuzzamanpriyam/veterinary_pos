# Stage 1: Composer dependencies
FROM composer AS composer-build
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader --no-progress --ignore-platform-reqs

# Stage 2: NPM dependencies and build
FROM node:20-alpine AS npm-build
WORKDIR /var/www/html
COPY package.json package-lock.json vite.config.js ./
RUN npm ci --silent
COPY resources/ ./resources/
COPY public/ ./public/
RUN npm run build


# Stage 3: Final image
FROM php:8.2-fpm
WORKDIR /var/www/html
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_mysql gd mbstring exif pcntl bcmath zip intl \
    && docker-php-ext-enable intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
COPY docker/php/conf.d/ $PHP_INI_DIR/conf.d/
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
COPY . .
COPY --from=composer-build /var/www/html/vendor/ ./vendor/
COPY --from=npm-build /var/www/html/public/build/ ./public/build/
RUN composer dump-autoload --optimize
RUN chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 755 vendor
COPY docker/php/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
