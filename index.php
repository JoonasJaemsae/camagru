<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

include_once './config/new_conn.php';

session_start();

$_SESSION['signupSuccessPersist'] = FALSE;
// ^ If you want to make it so that the user cannot return to the signup success page after returning to login screen.
// Currently the back button will return the user to the signup page, though. Not sure if that's good either.

if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
	header('Location: welcome.php');
	return;
}

// if (isset($_POST['login'])) {
// 	$_SESSION['login'] = $_POST['login'];

// 	$user = $_POST['username'];
// 	$_SESSION['username'] = $_POST['username'];

// 	$sql = "SELECT password FROM syottotesti WHERE username='$user';";
// 	$stmt = $dbConn->query($sql);

// 	if ($stmt->rowCount() > 0) {
// 		$_SESSION['loginErrorMessage'] = 'Correct username and/or password!' . '<br>';
// 		while ($array = $stmt->fetch(PDO::FETCH_ASSOC)) {
// 			$pass = $array['password'];
// 		}
// 		if ($_POST['password'] != $pass) {
// 			$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
// 			echo $_SESSION['loginErrorMessage'];
// 		} else {
// 			echo 'Correctamundo!' . '<br>';
// 			$sql = "SELECT id FROM syottotesti WHERE username='$user';";
// 			$stmt = $dbConn->query($sql);
// 			$_SESSION['logged_in_user_id'] = $stmt->fetch(PDO::FETCH_COLUMN);
// 			$_SESSION['loginSuccess'] = TRUE;
// 		}
// 	}

// 	echo 'End of if (isset) ' . '<br>';
// 	header('Location: index.php');
// 	return;
// }

if (isset($_POST['login'])) {
	$_SESSION['login'] = $_POST['login'];
	$_SESSION['username'] = $_POST['username'];

	$sql = "SELECT * FROM syottotesti WHERE username=:username AND password=:password;";
	$stmt = $dbConn->prepare($sql);
	$stmt->execute(
		array(
			'username' => $_POST['username'],
			'password' => $_POST['password']
		)
	);
	$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
	if ($stmt->rowCount() > 0) {
		$_SESSION['loginErrorMessage'] = 'Correct username and/or password!' . '<br>';
		echo 'Correctamundo!' . '<br>';
		$sql = "SELECT id FROM syottotesti WHERE username=?;";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute(array($_POST['username']));
		$_SESSION['logged_in_user_id'] = $stmt->fetch(PDO::FETCH_COLUMN);
		$_SESSION['loginSuccess'] = TRUE;
	} else {
		$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
		echo $_SESSION['loginErrorMessage'];
	}
	echo 'End of if (isset) ' . '<br>';
	header('Location: index.php');
	return;
}

// if (isset($_POST['login'])) {
// 	$_SESSION['login'] = $_POST['login'];

// 	$user = $_POST['username'];
// 	$_SESSION['username'] = $_POST['username'];

// 	$sql = "SELECT * FROM syottotesti WHERE username=?;";
// 	$stmt = $dbConn->prepare($sql);
// 	$stmt->execute(array($user, $password));

// 	$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
// 	if ($stmt->rowCount() > 0) {
// 		$_SESSION['loginErrorMessage'] = 'Correct username and/or password!' . '<br>';
// 		$pass = $stmt->fetch(PDO::FETCH_COLUMN);
// 		// while ($array = $stmt->fetch(PDO::FETCH_ASSOC)) {
// 		//  	$pass = $array['password'];
// 		// }
// 		if ($_POST['password'] != $pass) {
// 			$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
// 			echo $_SESSION['loginErrorMessage'];
// 		} else {
// 			echo 'Correctamundo!' . '<br>';
// 			$sql = "SELECT id FROM syottotesti WHERE username=?;";
// 			$stmt = $dbConn->prepare($sql);
// 			$stmt->execute(array($user));
// 			$_SESSION['logged_in_user_id'] = $stmt->fetch(PDO::FETCH_COLUMN);
// 			$_SESSION['loginSuccess'] = TRUE;
// 		}
// 	}
// 	echo 'End of if (isset) ' . '<br>';
// 	header('Location: index.php');
// 	return;
// }

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
				<a href="./forgot.php" class="form__link">Forgot your password?</a>
			</div>

		</div>
		<div class="flex-container1">
			<div class="subBoxLeft">
				<a href="./setup_db.php" class="form__link">Set up DB</a>
			</div>
			<div class="subBoxRight">
				<a href="./select_all.php" class="form__link">SQL retrieval test</a>
			</div>
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