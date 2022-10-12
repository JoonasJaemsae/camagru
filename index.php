<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

include_once './config/new_conn.php';

session_start();

if (!isset($_SESSION["username"])) {
	$_SESSION["username"] = FALSE;
}
if (!isset($_SESSION['logged_in_user_id'])) {
	$_SESSION['logged_in_user_id'] = FALSE;
}
if (!isset($_SESSION['loginSuccess'])) {
	$_SESSION['loginSuccess'] = FALSE;
}
if (!isset($_SESSION['loginPersist'])) {
	$_SESSION['loginPersist'] = FALSE;
}
if (!isset($_SESSION['signupSuccess']) || !isset($_SESSION['signupSuccessPersist'])) {
	$_SESSION['signupSuccess'] = FALSE;
	$_SESSION['signupSuccessPersist'] = FALSE;
}
if (!isset($_SESSION['loginErrorMessage'])) {
	$_SESSION['loginErrorMessage'] = FALSE;
}
if (!isset($_SESSION['emailChangeSuccessMessage'])) {
	$_SESSION['emailChangeSuccessMessage'] = FALSE;
}
$_SESSION['signupSuccessPersist'] = FALSE;
// ^ If you want to make it so that the user cannot return to the signup success page after returning to login screen.
// Currently the back button will return the user to the signup page, though. Not sure if that's good either.

if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
	header('Location: gallery.php');
	return;
}

if (isset($_POST['login'])) {
	$_SESSION['login'] = $_POST['login'];
	$_SESSION['username'] = $_POST['username'];
	$password = hash('whirlpool', $_POST['password']);

	$sql = "SELECT * FROM users WHERE username=:username AND password=:password;";
	$stmt = $dbConn->prepare($sql);
	$stmt->execute(
		array(
			'username' => $_POST['username'],
			'password' => $password
		)
	);
	$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
	if ($stmt->rowCount() > 0) {
		$sql = "SELECT id, email_is_verified FROM users WHERE username=?;";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute(array($_POST['username']));
		$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($user_data['email_is_verified'] == 0) {
			$_SESSION['loginErrorMessage'] = 'Verify your email address before trying to log in!';
			header('Location: index.php');
			return;
		}
		$_SESSION['logged_in_user_id'] = $user_data['id'];
		$_SESSION['loginSuccess'] = TRUE;
	} else {
		$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
	}
	header('Location: index.php');
	return;
}

?>

<html>

<head>
	<title>Camagru</title>
	<link rel="stylesheet" href="./style.css">

</head>

<body id="index">
	<div class="loginBox">
		<div class="logo">Camagru</div>
		<form action="index.php" method="POST" class="form">
			<div class="form__input-group">
				<input type="text" class="form__input" name="username" autofocus placeholder="Username">
			</div>
			<!-- 'autofocus' selects the field automatically on page load so you can input text without having to click on the field. -->
			<div class="form__input-group">
				<input type="password" class="form__input" name="password" placeholder="Password">
			</div>
			<button class="form__button" type="submit" name="login">Log in</button>
		</form>
		<div class="flex-container1">
			<div class="subBoxLeft">
				<a href="./signup.php" class="form__link">Sign up</a>
			</div>
			<div class="subBoxRight">
				<a href="./password_request.php" class="form__link">Forgot your password?</a>
			</div>
		</div>
		<div class="flex-container1">
			<div class="subBoxLeft">
				<a href="./setup_db.php" class="form__link">Set up DB</a>
			</div>
			<div class="subBoxRight">
				<a href="./gallery.php" class="form__link">To Gallery</a>
			</div>
		</div>
		<div class="successMessageBox" ;>
			<span class="successText">
				<?php
				echo $_SESSION['emailChangeSuccessMessage'] . '<br>';
				$_SESSION['emailChangeSuccessMessage'] = FALSE;
				?>
			</span>
		</div>
		<div class="loginErrorMessageBox" ;>
			<span class="loginErrorText">
				<?php
				echo $_SESSION['loginErrorMessage'] . '<br>';
				$_SESSION['loginErrorMessage'] = FALSE;
				?>
			</span>
		</div>
	</div>
</body>

</html>