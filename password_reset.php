<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

session_start();

include_once './config/new_conn.php';
require 'email_functions.php';

if (!isset($_SESSION['loginSuccess'])) {
    $_SESSION['loginSuccess'] = FALSE;
}
if (!isset($_SESSION['loginPersist'])) {
    $_SESSION['loginPersist'] = FALSE;
}
if (!isset($_SESSION['pwResetErrorMessage'])) {
    $_SESSION['pwResetErrorMessage'] = FALSE;
}
if (!isset($_SESSION['pwResetSuccessMessage'])) {
    $_SESSION['pwResetSuccessMessage'] = FALSE;
}
if (!isset($_SESSION['passwordResetSuccessful'])) {
    $_SESSION['passwordResetSuccessful'] = FALSE;
}
if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
    header('Location: gallery.php');
    return;
}

$reset_link_url = $_GET['reset_url'];

if (isset($_POST['newPwResetSubmit'])) {
    $newPw = $_POST['newPwReset'];
    $newPwAgain = $_POST['newPwResetAgain'];
    echo "Went here!" . '<br>';
    if ($newPw != $newPwAgain) {
        $_SESSION['pwResetErrorMessage'] = "The passwords you entered did not match each other. Please try again.";
    } else if (!checkPasswordStrength($newPw)) {
        $_SESSION['pwResetErrorMessage'] = 'Please enter a stronger password. Your password should have at least 8 characters and contain at least three of the following:' . '<br>'
            . '- a lowercase alphabetic character.' . '<br>'
            . '- an uppercase alphabetic character.' . '<br>'
            . '- a numeric character.' . '<br>'
            . '- a special character such as "!" or "#".';
    } else {
        $sql = "SELECT *
                FROM password_requests
                INNER JOIN users
                ON password_requests.email = users.email
                WHERE reset_link_url=?;
                ";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$reset_link_url]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $userid = $user_data['id'];
        $email = $user_data['email'];

        $sql2 = "UPDATE password_requests SET `active_bool`=0 WHERE `email`=?;";
        $stmt = $dbConn->prepare($sql2);
        $stmt->execute([$email]);

        $newPw = hash('whirlpool', $newPw);

        $sql3 = "UPDATE users SET `password`=? WHERE `id`=?;";
        $stmt = $dbConn->prepare($sql3);
        $stmt->execute([$newPw, $userid]);
        $_SESSION['pwResetSuccessMessage'] = "Your password has now been reset successfully!";
        $_SESSION['passwordResetSuccessful'] = TRUE;
    }
    header('Location: password_reset.php?reset_url=' . $reset_link_url);
    return;
}

$link_is_active_bool = 0;

if ($reset_link_url == '' && !isset($_POST['newPwResetSubmit'])) {
    $_SESSION['pwResetErrorMessage'] = '<p style="color: red;">The password reset url you tried to access is not a valid one or has expired.</p>';
} else if ($reset_link_url != '') {
    $reset_link_url = $_GET['reset_url'];
    $sql = "SELECT * FROM password_requests WHERE reset_link_url=? AND creation_datetime>=?;";
    $stmt = $dbConn->prepare($sql);
    // The creation date must be later (larger) than time now minus 24 hours.
    date_default_timezone_set('Europe/Helsinki');
    $stmt->execute([$reset_link_url, date("Y-m-d H:i:s", time() - 24 * 60 * 60)]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() < 1) {
        $_SESSION['pwResetErrorMessage'] = '<p style="color: red;">The password reset url you tried to access is not a valid one or has expired.</p>';
    } else if ($user_data['active_bool'] == 0) {
        $_SESSION['pwResetErrorMessage'] = '<p style="color: red;">The password reset url you tried to access is not valid or has expired.</p>';
    } else if ($stmt->rowCount() > 0) {
        $link_is_active_bool = 1;
    }
}

// echo '$message: ' . $message . '<br>';
// echo '$_SESSION["passwordResetSuccessful"]: ' . $_SESSION['passwordResetSuccessful'] . '<br>';
// echo '$reset_link_url: ' . $reset_link_url . '<br>';
// echo '$link_is_active_bool: ' . $link_is_active_bool . '<br>';

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
    <?php if ($link_is_active_bool == 1) { ?>
        <div class="loginBox">
            <div class="logo">Camagru</div>
            <form action="password_reset.php?reset_url=<?php echo $reset_link_url ?>" method="POST" class="form">
                <div class="form__input-group">
                    <input type="password" class="form__input" name="newPwReset" autofocus placeholder="New password" required>
                </div>
                <div class="form__input-group">
                    <input type="password" class="form__input" name="newPwResetAgain" placeholder="New password again" required>
                </div>
                <button class="form__button" type="submit" name="newPwResetSubmit">Submit</button>
            </form>
            <div class="flex-container1">
                <div class="subBoxLeft2">
                    <a href="./index.php" class="form__link">Back to login</a>
                </div>
            </div>
            <div class="errorMessageBox">
                <span class="errorText">
                    <?php
                    echo $_SESSION['pwResetErrorMessage'] . '<br>';
                    $_SESSION['pwResetErrorMessage'] = FALSE;
                    ?>
                </span>
            </div>
        </div>

    <?php
    } else if ($link_is_active_bool == 0 || $_SESSION['passwordResetSuccessful'] == TRUE) {
    ?>
        <div class="messageBox" style="text-align: center; margin-top: 10px; margin-bottom: 20px; padding-bottom: 30px;">
            <div class="successMessageBox">
                <span class="successText">
                    <?php
                    if ($_SESSION['pwResetSuccessMessage'] != '') {
                        $_SESSION['pwResetErrorMessage'] = FALSE;
                    }
                    echo $_SESSION['pwResetSuccessMessage'] . '<br>';
                    $_SESSION['pwResetSuccessMessage'] = FALSE;
                    $_SESSION['passwordResetSuccessful'] = FALSE;
                    ?>
                </span>
            </div>
            <div class="errorMessageBox">
                <span class="errorText">
                    <?php
                    echo $_SESSION['pwResetErrorMessage'] . '<br>';
                    $_SESSION['pwResetErrorMessage'] = FALSE;
                    ?>
                </span>
            </div>
            <a href="./index.php" class="message__link">Click here to move to the login screen.</a>
        </div>
    <?php
    }
    ?>
</body>

</html>