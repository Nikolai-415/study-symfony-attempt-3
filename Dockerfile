FROM php:8.0.9-fpm-buster

# Корень проекта
ARG APP_ROOT="/var/www/html"
WORKDIR ${APP_ROOT}
COPY . ${APP_ROOT}

# Исправление исключений "Unable to create..." / "Unable to write..." и т.п.
RUN chmod -R 777 ${APP_ROOT}

# Копируем настройки ini
COPY ./config/ini /usr/local/etc/php

# Скачивание и включение XDebug
RUN pecl install xdebug-3.0.4

# Необходимо для скачиваний расширений
RUN apt-get update

# Необходимо для установки расширений для PostgreSQL
RUN apt-get install -y libpq-dev

# Установка необходимых расширений для PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Установка intl
RUN apt-get install -y libicu-dev
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Установка Composer'а
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --version=2.1.5 --filename=composer --install-dir=/usr/local/bin
RUN php -r "unlink('composer-setup.php');"

# Необходимо для распаковки пакетов при выполнении команды "RUN composer install"
RUN apt install unzip

# Проверка и установка пакетов
RUN composer install && composer dump-autoload

WORKDIR ${APP_ROOT}/public
