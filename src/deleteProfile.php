<?php

session_start();
require_once __DIR__ . '/helpers.php';

$idUser = $_SESSION['user']['id'];

if ($idUser == '') {
	header("Location: /");
}
else {
        //delete account
	$pdo = getDB();

	$stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = :id");
	$stmt->execute([':id' => $idUser]);
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$oldAvatar = $row['avatar'] ?? '';

	if ($oldAvatar && $oldAvatar != 'default.png') {
		$filePath = __DIR__ . '/../uploads/' . $oldAvatar;
		if (file_exists($filePath)) {
			unlink($filePath);
		}
	}

	$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
	if ($stmt->execute([':id' => $idUser])) {
		session_unset();
		session_destroy();
		header("Location: /");
		exit;
	}
	else {
		echo 'Error!';
	}
}
