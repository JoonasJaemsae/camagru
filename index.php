<?php

include_once './includes/dbh.php';
session_start();

$_SESSION['signupSuccessPersist'] = FALSE;
// ^ If you want to make it so that the user cannot return to the signup success page after returning to login screen.
// Currently the back button will return the user to the signup page, though. Not sure if that's good either.

//  function findUser($user) {
// include_once './includes/dbh.php';
// $sql2 = "SELECT COUNT(username) FROM syottotesti WHERE username = 'bb';";
// $result2 = mysqli_query($link, $sql2);
// $resultCheck2 = mysqli_num_rows($result2);
// if ($resultCheck2 > 0) {
// while ($row = mysqli_fetch_assoc($result2)) {
// print_r($row);
// echo '<br>';
// echo $row['COUNT(username)'] . '<br>';
// echo $row['COUNT(username)'] . '<br>';
// }
// return TRUE;
// }
// return FALSE;
// }

if ($_SESSION['loginSuccess'] === TRUE) {
	header('Location: welcome.php');
	return;
}

if (isset($_POST['login'])) {
	$_SESSION['login'] = $_POST['login'];

	$user = $_POST['username'];
	$_SESSION['username'] = $_POST['username'];

	$sql2 = "SELECT password FROM syottotesti WHERE username = '$user';";
	$result2 = mysqli_query($link, $sql2);
	$resultCheck2 = mysqli_num_rows($result2);
	echo $resultCheck2 . ' <-- How many results were found.' . '<br>';
	$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
	if ($resultCheck2 == 0)
		$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
	if ($resultCheck2 > 0) {
		while ($row = mysqli_fetch_assoc($result2)) {
			print_r($row);
			echo '<br>';
			echo $row['password'] . ' <-- Password that was fetched with sql' . '<br>';
			$pass = $row['password'];
			echo 'End of Loop' . '<br>';
		}

		if ($_POST['password'] != $pass) {
			$_SESSION['loginErrorMessage'] = 'Wrong username and/or password!' . '<br>';
			echo $_SESSION['loginErrorMessage'];
		} else {
			echo 'Correctamundo!' . '<br>';
			$_SESSION['loginSuccess'] = TRUE;
		}
	}
	echo 'End of if (isset) ' . '<br>';
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
				<a href="./forgot.php" class="form__link">Forgot your password?</a>
			</div>

		</div>
		<div class="flex-container1">
			<div class="subBoxLeft"></div>
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