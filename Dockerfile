FROM php:8.3-cli

# Install system dependencies, zip tools, and PostgreSQL drivers for NeonDB
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (Added 'zip' here)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy the NEXORA project files
COPY . .

# Install dependencies safely: ignore platform strictness and skip premature scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Set directory permissions for Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Start the server using Render's dynamic port
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=$PORT"]