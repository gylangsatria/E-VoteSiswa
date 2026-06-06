FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable mysqli

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Set permissions for CodeIgniter
RUN chown -R www-data:www-data /var/www/html/application/cache && \
    chown -R www-data:www-data /var/www/html/application/logs && \
    chmod -R 755 /var/www/html/application/cache && \
    chmod -R 755 /var/www/html/application/logs && \
    chmod -R 755 /var/www/html/asset/file && \
    chmod -R 755 /var/www/html/application/third_party/fpdf && \
    chown -R www-data:www-data /var/www/html/application/third_party/fpdf

# Create uploads directory for mass DPT import
RUN mkdir -p /var/www/html/uploads && \
    chown -R www-data:www-data /var/www/html/uploads && \
    chmod -R 755 /var/www/html/uploads

# Create session directory (outside of bind mount)
RUN mkdir -p /tmp/sessions && chown -R www-data:www-data /tmp/sessions && chmod 755 /tmp/sessions

# Configure Apache to allow .htaccess (for CodeIgniter URL rewriting if used)
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80
