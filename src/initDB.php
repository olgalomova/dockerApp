<?php
require_once __DIR__ . '/helpers.php';

$pdo = getDB();

$pdo->exec("
CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT,
login TEXT UNIQUE, password TEXT, email TEXT UNIQUE, avatar TEXT, wins INTEGER DEFAULT 0, losses INTEGER DEFAULT 0);");

echo "Database and table created successfully.\n";
