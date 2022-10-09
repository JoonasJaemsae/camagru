<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

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

    // Need to add minimal checks for valid email.

    $_SESSION['pwRequestSuccessMessage'] = "Thank you! Instructions on how to proceed with the resetting your password have been sent to the provided email address.";
    $reset_link_url = generateRandomString2(10, $dbConn);
    $email = $_POST['pwRequestEmail'];
    sendPasswordResetEmail($email, $reset_link_url, $dbConn);
    header('Location: password_request.php');
    return;
}

// SELECT * FROM password_requests INNER JOIN users ON password_requests.email = users.email WHERE reset_link_url = '932800c2d3ce98a01465f153ab4ee9e293475b7ab6dbc54a23e0ccd9c1c4ee3771666c54e9f70b5c21e5b46bae71648a1b818e40ffdfd5cab413c28540d9c636';

?>

<html>

<head>
    <title>Camagru - Request a password reset</title>
    <link rel="stylesheet" href="./style.css">

</head>

<body id="index">
    <div class="loginBox">
        <div class="logo">Camagru</div>
        <form action="password_request.php" method="POST" class="form">
            <div class="form__input-group">
                <input type="text" class="form__input" name="pwRequestEmail" autofocus placeholder="Email address" required>
            </div>
            <!-- 'autofocus' selects the field automatically on page load so you can input text without having to click on the field. -->
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