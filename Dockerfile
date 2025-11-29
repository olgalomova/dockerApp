FROM php:8.2-fpm

# Устанавливаем SQLite и расширения PHP
RUN apt-get update && apt-get install -y \
	libsqlite3-dev \
	sqlite3 \
	&& docker-php-ext-install pdo pdo_sqlite \
	&& rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Копируем entrypoint скрипт
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Запускаем entrypoint который создаст БД, затем php-fpm
CMD ["/usr/local/bin/entrypoint.sh"]
