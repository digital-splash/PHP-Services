FROM php:8.1-apache
RUN apt-get clean && \
	apt-get update && \
	apt-get install -y --no-install-recommends \
		git

# Install xdebug
RUN pecl install -o -f xdebug-3.2.1 \
    && docker-php-ext-enable xdebug

# Copy php.ini
COPY ./php.ini /usr/local/etc/php/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
