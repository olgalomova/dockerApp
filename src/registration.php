<?php

require_once __DIR__ . '/helpers.php';
session_start();

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
	http_response_code(403);
	exit("CSRF validation failed");
}

$pdo = getDB();
$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';

if ($login === '' || $password === '' || $email === '') {
	die('All fields are required!');
}

// Проверка email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email!");
}

// Проверка длины пароля
if (strlen($password) < 6) {
    die("Password too short!");
}

// Запрещённые символы в логине
if (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
    die("Login contains invalid characters!");
}

// Хешируем пароль
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
try {
	$stmt = $pdo->prepare("INSERT INTO users (login, password, email) VALUES (:login, :password, :email)");
	$stmt->execute([':login' => $login, ':password' => $hashedPassword, ':email' => $email]);
	header("Location: /login.php");
	exit;
}
catch (PDOException $e) {
	if ($e->getCode() == 23000) { // UNIQUE constraint failed
		echo 'This user or email already exists!';
	}
	else {
		echo 'Registration error: ' . $e->getMessage();
	}
}
