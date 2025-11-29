<?php

session_start();
require_once __DIR__ . '/src/helpers.php';

if (!isset($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');

$pdo = getDB();
$idUser = $_SESSION['user']['id'];
if ($idUser == '') {
	header("Location: /");
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $idUser]);
$attend = $stmt->fetch(PDO::FETCH_ASSOC);

if ($attend) {
	$login = htmlspecialchars($attend['login'], ENT_QUOTES, 'UTF-8');
	$password = $attend['password']; // пароль не выводится, можно не трогать
	$email = htmlspecialchars($attend['email'], ENT_QUOTES, 'UTF-8');
	$avatar = $attend['avatar'] ?? '';
}
else {
	header("Location: /");
	exit;
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="assets/styles/style.css">
		<title>Profile</title>
	</head>
	<body>
		<main>
			<h2>Personal account</h2>
			<div style="margin-bottom: 20px;">
				<img src="<?= $avatar ? 'uploads/'.$avatar : 'uploads/default.png' ?>" alt="Avatar" style="width:120px;height:120px;border-radius:50%;object-fit:cover;">
			</div>	
			<p>Welcome, <?= $login ?>!</p>
			<a href="src/logout.php">Log out</a>
		</main>
		<main>
			<h2>Change personal data</h2>
			<form action="src/editPassword.php" method="post">
				<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
				<span>Password:</span>
				<input name="password" type="text" placeholder="Enter your new password">
				<span>Login:</span>
				<input name="login" type="text" value="<?= $login ?>">
				<span>Email:</span>
				<input name="email" type="text" value="<?= $email ?>">
				<button type="submit">Save data</button>
				<a href="/src/deleteProfile.php">Delete profile</a>
			</form>
		</main>
		<main>
			<h2>Avatar settings</h2>
			<form action="src/uploadAvatar.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
				<input type="file" name="avatar" accept="image/*" required>
				<button type="submit">Upload avatar</button>
				<a href="src/deleteAvatar.php">Delete avatar</a>
			</form>
		</main>
	</body>
</html>
