<?php

session_start();

if (!isset($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf = htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8');

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
		<title>Registration</title>
	</head>
	<body>
		<main>
			<h2>Registration</h2>
			<form action="src/registration.php" method="post">
				<input type="hidden" name="csrf_token" value="<?= $csrf ?>">
				<input required type="text" placeholder="Login" name="login">
				<input required type="text" placeholder="Email" name="email">
				<input required type="password" placeholder="Password" name="password">
				<button type="submit">Register</button>
			</form>
		</main>
	</body>
</html>
