FROM php:7.1-fpm
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) mbstring
RUN docker-php-ext-install -j$(nproc) tokenizer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

RUN apt-get update && apt-get install -y vim

#RUN sed -e 's/max_execution_time = 30/max_execution_time = 100/' -i /etc/php/7.1/fpm/php.ini