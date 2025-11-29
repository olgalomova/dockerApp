<?php
session_start();
require_once __DIR__ . '/helpers.php';

if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
	http_response_code(403);
	exit("CSRF validation failed");
}

$connect = getDB();
$idUser = $_SESSION['user']['id'];
$uploadDir = __DIR__ . '/../uploads/';

if (!isset($_FILES['avatar'])) {
	header("Location: /profile.php");
	exit;
}

$avatar = $_FILES['avatar'];

// Проверка MIME и расширения
$allowedMime = ['image/jpeg', 'image/png', 'image/webp'];
$ext = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
$allowedExt = ['jpg','jpeg','png','webp'];

if (!in_array($avatar['type'], $allowedMime) || !in_array($ext, $allowedExt)) {
	die("Invalid image format!");
}

// Генерируем безопасное имя файла
$filename = uniqid() . '.' . $ext;
$path = $uploadDir . $filename;

// Переносим файл
if (!move_uploaded_file($avatar['tmp_name'], $path)) {
	die("Upload error");
}

// Получаем старый аватар через подготовленный запрос
$stmt = $connect->prepare("SELECT avatar FROM users WHERE id = :id");
$stmt->execute([':id' => $idUser]);
$oldAvatarRow = $stmt->fetch(PDO::FETCH_ASSOC);
$oldAvatar = $oldAvatarRow['avatar'] ?? '';

// Удаляем старый аватар, если он не дефолтный
if ($oldAvatar && $oldAvatar != 'default.png') {
	$filePath = $uploadDir . $oldAvatar;
	if (file_exists($filePath)) {
		unlink($filePath);
	}
}

// Записываем новое имя файла через подготовленный запрос
$stmt = $connect->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
$stmt->execute([':avatar' => $filename, ':id' => $idUser]);

// Возврат на профиль
header("Location: /profile.php");
exit;
