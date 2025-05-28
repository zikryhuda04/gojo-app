# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Salin semua file ke direktori web Apache
COPY . /var/www/html/

EXPOSE 80

# Install ekstensi PHP jika diperlukan (contoh: mysqli)
RUN docker-php-ext-install mysqli

# Set permission agar tidak error akses
RUN chown -R www-data:www-data /var/www/html

# Buka port 80 untuk akses aplikasi
EXPOSE 80
