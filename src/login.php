<?php

ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // если HTTPS
ini_set('session.cookie_samesite', 'Strict');

session_start();
require_once __DIR__ . '/helpers.php';

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
	http_response_code(403);
	exit("CSRF validation failed");
}

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if ($login === '' || $password === '') {
	echo "Login and password are required!";
	exit;
}

$pdo = getDB(); // Получаем PDO
// Подготовленный запрос для выбора пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
$stmt->execute([':login' => $login]);

$attend = $stmt->fetch(PDO::FETCH_ASSOC);

if ($attend && password_verify($password, $attend['password'])) {
	$_SESSION['user']['id'] = $attend['id'];
	header("Location: /profile.php");
	exit;
}
else {
	echo "Invalid login or password!";
}
