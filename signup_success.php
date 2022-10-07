<?php

session_start();

if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
	header('Location: gallery.php');
	return;
}
if ($_SESSION['signupSuccess'] === FALSE && $_SESSION['signupSuccessPersist'] === FALSE) { // Seems to be working properly even without ob_start.

    header("Location: signup.php");
    return ;
}
if ($_SESSION['signupSuccess'] === TRUE) {

    $_SESSION['signupSuccess'] = FALSE;
    $_SESSION['signupSuccessPersist'] = TRUE;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your account was created successfully!</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="index">
    <div class="messageBox" style="text-align: center;">
        <?php
            echo '<p style="color: blue;">Registration successful! Follow the link sent to your email address to verify your email address. Click the link below to return
            to the login screen.</p>';
        ?> 
        <a href="./index.php" class="message__link">Back to login</a>
    </div>
    
</body>
</html>