# Use the official PHP Apache image
FROM php:8.0-apache

# Install Main Extensions
RUN apt-get clean && \
  apt-get update && \
  apt-get install -y --no-install-recommends \
  # Install Database Clients
  mariadb-server-core-10.5 \
  mariadb-server-10.5 \
  mariadb-client-10.5 \
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
RUN pecl install -o -f xdebug-3.2.1 && docker-php-ext-enable xdebug
# RUN cd /tmp && \
#     git clone https://github.com/xdebug/xdebug.git && \
#     cd xdebug && \
#     git checkout xdebug_3_1 && \
#     phpize && \
#     ./configure --enable-xdebug && \
#     make && \
#     make install && \
#     rm -rf /tmp/xdebug
# RUN docker-php-ext-enable xdebug

# Copy php.ini
COPY ./php.ini /usr/local/etc/php/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
