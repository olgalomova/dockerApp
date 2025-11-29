#!/bin/bash
set -e

echo "=== Инициализация базы данных ==="

# Создаём директорию для БД
mkdir -p /var/www/html/backend/data

# Создаём файл базы данных
DB_FILE="/var/www/html/backend/data/database.sqlite"

if [ ! -f "$DB_FILE" ]; then
    touch "$DB_FILE"
    echo "✓ Файл БД создан: $DB_FILE"
else
    echo "✓ Файл БД уже существует"
fi

# Даём полные права на директорию и файл
chmod -R 777 /var/www/html/backend/data
echo "✓ Права установлены"

# Создаём директорию uploads если её нет
mkdir -p /var/www/html/uploads
chmod -R 777 /var/www/html/uploads
echo "✓ Директория uploads готова"

echo "=== Запуск PHP-FPM ==="
php-fpm
