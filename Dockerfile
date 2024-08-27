# Используем официальный образ PHP с поддержкой FPM
FROM php:8.1-fpm

# Устанавливаем зависимости, необходимые для PostgreSQL и других PHP-расширений
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настраиваем рабочую директорию
WORKDIR /var/www

# Копируем файлы проекта в контейнер
COPY . /var/www

# Настройка прав доступа к файлам
RUN chown -R www-data:www-data /var/www

# Устанавливаем точку входа для запуска PHP-FPM
CMD ["php-fpm"]
