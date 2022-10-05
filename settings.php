<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

session_start();
// ob_start(); // if logout redirection doesn't work.

include_once './config/new_conn.php';
require 'gallery_functions.php';

if (!isset($_SESSION['pwChangeErrorMessage'])) {
    $_SESSION['pwChangeErrorMessage'] = FALSE;
}
if (!isset($_SESSION['pwChangeSuccessMessage'])) {
    $_SESSION['pwChangeSuccessMessage'] = FALSE;
}
if (!isset($_SESSION['usernameChangeErrorMessage'])) {
    $_SESSION['usernameChangeErrorMessage'] = FALSE;
}
if (!isset($_SESSION['emailChangeErrorMessage'])) {
    $_SESSION['emailChangeErrorMessage'] = FALSE;
}
if ($_SESSION['loginSuccess'] === TRUE) {

    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['loginPersist'] = TRUE;
    $_SESSION['loginErrorMessage'] = FALSE;
}
if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

    header("Location: index.php");
    exit();
}

if (isset($_POST['submitPwChange'])) {
    $oldPw = $_POST['oldPw'];
    $newPw = $_POST['newPw'];
    $newPwAgain = $_POST['newPwAgain'];
    if ($newPw != $newPwAgain) {
        $_SESSION['pwChangeErrorMessage'] = "The new passwords you entered didn't match each other. Try again!";
        header('Location: settings.php');
        return;
    }
    if ($oldPw == $newPw) {
        $_SESSION['pwChangeErrorMessage'] = "Your old password can't be the same as your new password.";
        header('Location: settings.php');
        return;
    }
    $username = $_SESSION['username'];
    $password = hash('whirlpool', $oldPw);
    $sql = "SELECT * FROM users WHERE username=:username AND password=:password;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute(
        array(
            'username' => $username,
            'password' => $password
        )
    );
    if ($stmt->rowCount() > 0) {
        $_SESSION['pwChangeSuccessMessage'] = "You password was changed successfully!";
        $verifiedPw = hash('whirlpool', $newPw);
        $sql = "UPDATE users SET `password`=? WHERE username=?;";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$verifiedPw, $username]);
    } else {
        $_SESSION['pwChangeErrorMessage'] = 'The old password you entered was incorrect! Try again.' . '<br>';
    }
    header('Location: settings.php');
    return;
}

?>

<html>

<head>
    <title>Camagru - Adjust your settings and preferences</title>

    <style>
        <?php include "style.css"; ?>
    </style>
</head>
<?php

if (isset($_SESSION['loginPersist'])) {
?>

    <body id="gradient" style="display: flex; flex-direction: column;">
        <?php

        include_once 'navbar.php';

        ?>
        <div class="settingsArea">
            <div class="settingsBox">
                <div class="settingsBoxLeftSide">
                    <div class="subBoxLeft" style="font: 500 1rem 'Quicksand', sans-serif;">Change your password</div>
                    <div class="subBoxLeft" style="font: 500 1rem 'Quicksand', sans-serif;">Change your username</div>
                    <div class="subBoxLeft" style="font: 500 1rem 'Quicksand', sans-serif;">Change your email</div>
                    <div class="subBoxLeft" style="font: 500 1rem 'Quicksand', sans-serif;">Toggle notifications</div>
                </div>
                <div class="settingsBoxRightSide">
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        You know you want to.
                        <form action="settings.php" method="POST" class="form">
                            <div class="form__input-group" style>
                                <input type="password" class="form__input" name="oldPw" style="margin-top: 25px;" placeholder="Old password" required>
                            </div>
                            <div class="form__input-group">
                                <input type="password" class="form__input" name="newPw" placeholder="New password" required>
                            </div>
                            <div class="form__input-group">
                                <input type="password" class="form__input" name="newPwAgain" placeholder="New password again" required>
                            </div>
                            <button class="form__button" type="submit" name="submitPwChange">Confirm</button>
                        </form>
                        <div class="errorMessageBox" ;>
                            <span class="errorText">
                                <?php
                                echo $_SESSION['pwChangeErrorMessage'] . '<br>';
                                $_SESSION['pwChangeErrorMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                        <div class="successMessageBox" ;>
                            <span class="successText">
                                <?php
                                echo $_SESSION['pwChangeSuccessMessage'] . '<br>';
                                $_SESSION['pwChangeSuccessMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        You know you want to.
                    </div>
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        You know you want to.
                    </div>
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        By having this set to ON, you will receive notifications in your email when someone likes your pictures.
                        Currently set to ON.
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>

<?php

}

?>