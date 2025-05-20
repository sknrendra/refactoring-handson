FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y git unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHPUnit
RUN composer global require phpunit/phpunit

# Add Composer's bin directory to PATH
ENV PATH="/root/.composer/vendor/bin:$PATH"

# Copy custom PHP config
COPY php.ini /usr/local/etc/php/php.ini
