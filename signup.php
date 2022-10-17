<?php

session_start();

include_once './config/new_conn.php';
require 'email_functions.php';

if (!isset($_SESSION['loginSuccess']) || !isset($_SESSION['loginPersist'])) {
    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['loginPersist'] = FALSE;
    $_SESSION['logged_in_user_id'] = '';
}
if (!isset($_SESSION['signupSuccess'])) {
    $_SESSION['signupSuccess'] = FALSE;
}
if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
    header('Location: gallery.php');
    return;
}
if ($_SESSION['signupSuccess'] == TRUE) {
    header("Location: signup_success.php");
    return;
}

function checkPasswords($password, $passwordAgain) {
    if ($password === $passwordAgain)
        return TRUE;
    else
        return FALSE;
}

if (isset($_POST['submit'])) {
    $_SESSION['submit'] = $_POST['submit'];
    $_SESSION['signupSuccess'] = FALSE;

    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['passwordAgain'] = $_POST['passwordAgain'];
    $_SESSION['email'] = $_POST['email'];

    $username = $_POST['username'];
    if (strlen($username) > 16 || strlen($username) < 4) {
        $_SESSION['signupErrorMessage'] = "Username must be between 4 and 16 characters long. Please try another username.";
        header("Location: signup.php");
        return;
    }
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Check if someone is already using your desired email.
    $sql = "SELECT * FROM users WHERE email=?;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['signupErrorMessage'] = "This email address is not available. Please try another one.";
        header("Location: signup.php");
        return;
    }
    $sql2 = "SELECT username FROM users WHERE username=?;";
    $stmt = $dbConn->prepare($sql2);
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if (!checkPasswordStrength($password)) {
        $_SESSION['signupErrorMessage'] = 'Please enter a stronger password. Your password should be between 8 and 30 characters and contain at least three of the following:' . '<br>'
            . '- a lowercase alphabetic character.' . '<br>'
            . '- an uppercase alphabetic character.' . '<br>'
            . '- a numeric character.' . '<br>'
            . '- a special character such as "!" or "#".';
    } else if (!checkPasswords($_POST['password'], $_POST['passwordAgain'])) {
        $_SESSION['signupErrorMessage'] = 'The passwords you entered did not match each other. Please try again!';
    } else if (!checkEmailStrength($email)) {
        $_SESSION['signupErrorMessage'] = 'Your email address is not a valid one. Please try again.';
    } else if ($stmt->rowCount() > 0) {
        $_SESSION['signupErrorMessage'] = 'This username is not available. Please try another one.';
    } else {
        $_SESSION['signupSuccess'] = TRUE;
        $password = hash('whirlpool', $password);
        $verifCode = generateRandomString(10, $dbConn);
        $sql = "INSERT INTO users (`username`, `password`, `email`, `verification_code`, `email_is_verified`, `notifications`)
                VALUES (?, ?, ?, ?, ?, ?);";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$username, $password, $email, $verifCode, 0, 1]);
        sendVerificationEmail($email, $verifCode, $dbConn);
        $_SESSION['signupErrorMessage'] = '';
    }
    header("Location: signup.php");
    return;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Create an account</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="index">
    <?php
    $message = isset($_SESSION['signupErrorMessage']) ? $_SESSION['signupErrorMessage'] : FALSE;
    ?>
    <div class="accountCreationBox">
        <div class="logo">Camagru</div>
        <form method="POST" class="form">
            <div class="form__input-group">
                <input type="text" class="form__input" name="username" autofocus placeholder="Username" required>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" name="password" placeholder="Password" required>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" name="passwordAgain" placeholder="Confirm password" required>
            </div>
            <div class="form__input-group">
                <input type="email" class="form__input" name="email" placeholder="Email" required>
            </div>
            <button class="form__button" id="submit" type="submit" name="submit">Sign up</button>
        </form>
        <div class="flex-container1">
            <div class="subBoxLeft2">
                <a href="./index.php" class="form__link">Back to login</a>
            </div>
        </div>
        <div style="color: red;">
            <?php
            if ($message != FALSE) {
                echo $message;
                // The below is to make it so that the message doesn't show up again on reloading the page.
                $_SESSION['signupErrorMessage'] = FALSE;
            }
            ?>
        </div>
    </div>
</body>

</html>