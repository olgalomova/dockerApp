<?php
const DB_FILE = __DIR__ . '/../backend/data/database.sqlite';

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        // Убеждаемся что директория существует
        $dir = dirname(DB_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Создаём файл если его нет
        if (!file_exists(DB_FILE)) {
            file_put_contents(DB_FILE, '');
            chmod(DB_FILE, 0666);
        }
        
        $pdo = new PDO('sqlite:' . DB_FILE);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        
        // Инициализируем таблицы
        initDatabase($pdo);
    }
    return $pdo;
}

function initDatabase($pdo) {
    // Создаём таблицу users с колонкой login
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            login TEXT UNIQUE NOT NULL,
            email TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            avatar TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Добавьте другие таблицы здесь если нужно
}
