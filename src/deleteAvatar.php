<?php

session_start();
require_once __DIR__ . '/helpers.php';

$pdo = getDB();
$idUser = $_SESSION['user']['id'] ?? '';

if ($idUser === '') {
	header("Location: /");
	exit;
}

$stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = :id");
$stmt->execute([':id' => $idUser]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$oldAvatar = $row['avatar'] ?? '';

if ($oldAvatar && $oldAvatar != 'default.png') {
	$filePath = __DIR__ . '/../uploads/' . $oldAvatar;
	if (file_exists($filePath)) {
		unlink($filePath); // удаляем файл
	}
}

$stmt = $pdo->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
$stmt->execute([':avatar' => '', ':id' => $idUser]);

header("Location: /profile.php");
exit;
