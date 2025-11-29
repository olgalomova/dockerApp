<?php

session_start();
require_once __DIR__ . '/helpers.php';

$idUser = $_SESSION['user']['id'];
$password = trim($_POST['password']);
$login = trim($_POST['login']);
$email = trim($_POST['email']);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql;
$pdo = getDB();

// Проверяем уникальность логина
$stmt = $pdo->prepare("SELECT id FROM users WHERE login = :login AND id != :id");
$stmt->execute([':login' => $login, ':id' => $idUser]);
if ($stmt->fetch()) {
	die("This login is already taken!");
}

// Проверяем уникальность email
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
$stmt->execute([':email' => $email, ':id' => $idUser]);
if ($stmt->fetch()) {
	die("This email is already taken!");
}

// Проверка email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email!");
}

// Проверка длины пароля
if (strlen($password) < 6 && $password !== '') {
    die("Password too short!");
}

// Запрещённые символы в логине
if (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
    die("Login contains invalid characters!");
}

if ($idUser == '') {
	header("Location: /");
}
else {
	if ($password !== '') {
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
		$sql = "UPDATE users SET password = :password, login = :login, email = :email WHERE id = :id";
		$params = [':password' => $hashedPassword, ':login' => $login, ':email' => $email, ':id' => $idUser];
	}
	else {
		$sql = "UPDATE users SET login = :login, email = :email WHERE id = :id";
		$params = [':login' => $login, ':email' => $email, ':id' => $idUser];
	}
	$stmt = $pdo->prepare($sql);
	if ($stmt->execute($params)) {
		header("Location: /profile.php");
		exit;
	}
	else {
		echo 'Error!';
	}
}
