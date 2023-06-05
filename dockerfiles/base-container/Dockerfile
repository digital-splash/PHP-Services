FROM php:8.0-apache
RUN apt-get clean && \
	apt-get update && \
	apt-get install -y --no-install-recommends \
		git

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
