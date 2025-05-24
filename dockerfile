# Gunakan image dasar PHP + Apache
FROM php:8.2-apache

# Install ekstensi PHP yang diperlukan (misalnya MySQLi)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy file aplikasi ke direktori Apache
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Set direktori kerja
WORKDIR /var/www/html
