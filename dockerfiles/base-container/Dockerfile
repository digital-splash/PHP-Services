FROM php:8.1-apache
RUN apt-get clean && \
	apt-get update && \
	apt-get install -y --no-install-recommends \
	# Install Other Extensions
	git \
	curl \
	make \
	cmake \
	unzip \
	zlib1g-dev \
	libpng-dev

# Install Docker PHP Extensions
RUN apt-get clean && \
  apt-get update && \
  apt-get install -y \
	libjpeg-dev \
	libzip-dev \
	libwebp-dev \
	zip && \
  docker-php-ext-configure \
	gd --with-jpeg --with-webp && \
  docker-php-ext-install \
	pdo_mysql \
	gd \
	mysqli \
	zip

# Install xdebug
RUN pecl install -o -f xdebug-3.2.1 \
    && docker-php-ext-enable xdebug

# Copy php.ini
COPY ./php.ini /usr/local/etc/php/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
