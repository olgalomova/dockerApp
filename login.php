<?php

session_start();

// создаём токен, если его нет
if (!isset($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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
		<title>Log in</title>
	</head>
	<body>
		<main>
			<h2>Log in</h2>
			<form action="src/login.php" method="post">
				<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
				<input required type="text" placeholder="Login" name="login">
				<input required type="password" placeholder="Password" name="password">
				<button type="submit">Sign in</button>
			</form>
		</main>
	</body>
</html>
