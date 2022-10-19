<?php

include_once './config/new_conn.php';
require 'email_functions.php';

session_start();

if (!isset($_SESSION['loginSuccess'])) {
    $_SESSION['loginSuccess'] = FALSE;
}
if (!isset($_SESSION['loginPersist'])) {
    $_SESSION['loginPersist'] = FALSE;
}
if (!isset($_SESSION['pwRequestErrorMessage'])) {
    $_SESSION['pwRequestErrorMessage'] = FALSE;
}
if (!isset($_SESSION['pwRequestSuccessMessage'])) {
    $_SESSION['pwRequestSuccessMessage'] = FALSE;
}
if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
    header('Location: gallery.php');
    return;
}

if (isset($_POST['pwRequestSubmit'])) {
    if (!isset($_POST['pwRequestEmail'])) {
        $_SESSION['pwRequestErrorMessage'] = "Please fill in the required field!";
        header("Location: password_request.php");
        return;
    }
    $email = $_POST['pwRequestEmail'];
    if (!checkEmailStrength($email)) {
        $_SESSION['pwRequestErrorMessage'] = 'Your email address is not a valid one. Please try again.';
    } else {
        $_SESSION['pwRequestSuccessMessage'] = "Thank you! Instructions on how to proceed with resetting your password have been sent to the email address provided.";
        $reset_link_url = generateRandomString2(10, $dbConn);
        sendPasswordResetEmail($email, $reset_link_url, $dbConn);
    }
    header('Location: password_request.php');
    return;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Request a password reset</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="index">
    <div class="loginBox">
        <div class="logo">Camagru</div>
        <form action="password_request.php" method="POST" class="form">
            <div class="form__input-group">
                <input type="email" class="form__input" name="pwRequestEmail" autofocus placeholder="Email address" required>
            </div>
            <button class="form__button" type="submit" name="pwRequestSubmit">Request a new password</button>
        </form>
        <div class="flex-container1">
            <div class="subBoxLeft2">
                <a href="./index.php" class="form__link">Back to login</a>
            </div>
        </div>
        <div class="errorMessageBox">
            <span class="errorText">
                <?php
                echo $_SESSION['pwRequestErrorMessage'] . '<br>';
                $_SESSION['pwRequestErrorMessage'] = FALSE;
                ?>
            </span>
        </div>
        <div class="successMessageBox">
            <span class="successText">
                <?php
                echo $_SESSION['pwRequestSuccessMessage'] . '<br>';
                $_SESSION['pwRequestSuccessMessage'] = FALSE;
                ?>
            </span>
        </div>
    </div>
</body>

</html>