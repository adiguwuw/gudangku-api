FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    libicu-dev \
    && docker-php-ext-install intl zip

# Set working directory
WORKDIR /app

# Copy semua file
COPY . .

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
    && php composer.phar install --no-dev --optimize-autoloader

# Expose port
EXPOSE 8080

# Run Laravel
CMD php artisan serve --host=0.0.0.0 --port=8080