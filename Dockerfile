FROM php:8.4-cli

# Install system dependencies, PostgreSQL drivers, and prepare Node.js repository
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy the NEXORA project files
COPY . .

# Install PHP dependencies safely
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Install Node dependencies and compile frontend assets
RUN npm install && npm run build

# Set directory permissions for Laravel
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Start the server using Render's dynamic port
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=$PORT"]