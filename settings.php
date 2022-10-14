<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

session_start();

include_once './config/new_conn.php';
require 'gallery_functions.php';
require 'email_functions.php';

if ((!isset($_SESSION['loginSuccess']) || !isset($_SESSION['loginPersist']))
    || ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE)
) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['usernameChangeErrorMessage'])) {
    $_SESSION['usernameChangeErrorMessage'] = FALSE;
}
if (!isset($_SESSION['usernameChangeSuccessMessage'])) {
    $_SESSION['usernameChangeSuccessMessage'] = FALSE;
}
if (!isset($_SESSION['pwChangeErrorMessage'])) {
    $_SESSION['pwChangeErrorMessage'] = FALSE;
}
if (!isset($_SESSION['pwChangeSuccessMessage'])) {
    $_SESSION['pwChangeSuccessMessage'] = FALSE;
}
if (!isset($_SESSION['emailChangeErrorMessage'])) {
    $_SESSION['emailChangeErrorMessage'] = FALSE;
}
if (!isset($_SESSION['emailChangeSuccessMessage'])) {
    $_SESSION['emailChangeSuccessMessage'] = FALSE;
}

if (isset($_POST['submitPwChange'])) {
    $oldPw = $_POST['oldPw'];
    $newPw = $_POST['newPw'];
    if (!checkPasswordStrength($newPw)) {
        $_SESSION['pwChangeErrorMessage'] = 'Please enter a stronger password. Your password should be between 8 and 30 characters and contain at least three of the following:' . '<br>'
        . '- a lowercase alphabetic character.' . '<br>'
        . '- an uppercase alphabetic character.' . '<br>'
        . '- a numeric character.' . '<br>'
        . '- a special character such as "!" or "#".';
        header('Location: settings.php');
        return;
    }
    $newPwAgain = $_POST['newPwAgain'];
    if ($newPw != $newPwAgain) {
        $_SESSION['pwChangeErrorMessage'] = "The new passwords you entered did not match each other. Try again!";
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
        $_SESSION['pwChangeSuccessMessage'] = "Your password was changed successfully!";
        $verifiedPw = hash('whirlpool', $newPw);
        $sql = "UPDATE users SET `password`=? WHERE username=?;";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$verifiedPw, $username]);
    } else {
        $_SESSION['pwChangeErrorMessage'] = 'The old password you entered was incorrect! Try again.';
    }
    header('Location: settings.php');
    return;
}

if (isset($_POST['submitUsernameChange'])) {
    $newUsername = $_POST['newUsername'];
    $pwForUsernameChange = $_POST['pwForUsername'];
    $username = $_SESSION['username'];
    if (strlen($newUsername) > 16 || strlen($newUsername) < 4) {
        $_SESSION['usernameChangeErrorMessage'] = "Username must be between 4 and 16 characters long. Please try another username.";
        header("Location: settings.php");
        return;
    }
    if ($username == $newUsername) {
        $_SESSION['usernameChangeErrorMessage'] = "The new username you entered is the same as your current one. No change was made.";
        header('Location: settings.php');
        return;
    }
    $password = hash('whirlpool', $pwForUsernameChange);
    $sql = "SELECT * FROM users WHERE username=:username AND password=:password;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute(
        array(
            'username' => $username,
            'password' => $password
        )
    );
    if ($stmt->rowCount() < 1) {
        $_SESSION['usernameChangeErrorMessage'] = "The password you entered was incorrect. Try again!";
        header('Location: settings.php');
        return;
    }
    $sql2 = "SELECT * FROM users WHERE username=?;";
    $stmt = $dbConn->prepare($sql2);
    $stmt->execute([$newUsername]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['usernameChangeErrorMessage'] = "The new username you entered is already in use. Try another one.";
        header('Location: settings.php');
        return;
    } else {
        $sql3 = "UPDATE users SET username=? WHERE username=?;";
        $stmt = $dbConn->prepare($sql3);
        $stmt->execute([$newUsername, $username]);
        $_SESSION['username'] = $newUsername;
        $_SESSION['usernameChangeSuccessMessage'] = 'Your username was changed successfully!';
        header('Location: settings.php');
        return;
    }
}

if (isset($_POST['submitEmailChange'])) {
    $newEmail = $_POST['newEmail'];
    if (!checkEmailStrength($newEmail)) {
        $_SESSION['emailChangeErrorMessage'] = 'Your email address is not a valid one. Please try again.';
        header('Location: settings.php');
        return;
    }
    $pwForEmailChange = $_POST['pwForEmail'];
    $username = $_SESSION['username'];
    $password = hash('whirlpool', $pwForEmailChange);
    $sql = "SELECT * FROM users WHERE username=:username AND password=:password;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute(
        array(
            'username' => $username,
            'password' => $password
        )
    );
    if ($stmt->rowCount() < 1) {
        $_SESSION['emailChangeErrorMessage'] = "The password you entered was incorrect. Try again!";
        header('Location: settings.php');
        return;
    }
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user_data['email'] == $newEmail) {
        $_SESSION['emailChangeErrorMessage'] = "The new email you entered is the same as your current one.";
        header('Location: settings.php');
        return;
    }
    $sql2 = "SELECT * FROM users WHERE email=?;";
    $stmt = $dbConn->prepare($sql2);
    $stmt->execute([$newEmail]);
    if ($stmt->rowCount() > 0) {
        $_SESSION['emailChangeErrorMessage'] = "The new email you entered is not available. Try another one.";
        header('Location: settings.php');
        return;
    } else {
        $_SESSION['emailChangeSuccessMessage'] = 'Your email was changed successfully!';
        $verifCode = generateRandomString(10, $dbConn);
        $sql3 = "UPDATE users SET email=?, verification_code=?, email_is_verified=? WHERE username=?;";
        $stmt = $dbConn->prepare($sql3);
        $stmt->execute([$newEmail, $verifCode, '0', $username]);
        $_SESSION['emailChangeSuccessMessage'] = "Your email was changed successfully! Please verify your new email before logging in.";
        sendVerificationEmail($newEmail, $verifCode, $dbConn);
        header('Location: logout.php');
        return;
    }
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
                    <div class="subBoxLeft" style="font: 500 1rem 'Quicksand', sans-serif;">Change your email address</div>
                    <div class="subBoxLeft" style="font: 500 1rem 'Quicksand', sans-serif;">Toggle email alerts</div>
                </div>
                <div class="settingsBoxRightSide">
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        Input your new password twice and your old one to confirm submission.
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
                        <div class="errorMessageBox">
                            <span class="errorText">
                                <?php
                                echo $_SESSION['pwChangeErrorMessage'] . '<br>';
                                $_SESSION['pwChangeErrorMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                        <div class="successMessageBox">
                            <span class="successText">
                                <?php
                                echo $_SESSION['pwChangeSuccessMessage'] . '<br>';
                                $_SESSION['pwChangeSuccessMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        Enter your desired username and your password for confirmation.
                        <form action="settings.php" method="POST" class="form">
                            <div class="form__input-group">
                                <input type="text" class="form__input" name="newUsername" style="margin-top: 25px;" placeholder="New username" required>
                            </div>
                            <div class="form__input-group" style>
                                <input type="password" class="form__input" name="pwForUsername" placeholder="Password" required>
                            </div>
                            <button class="form__button" type="submit" name="submitUsernameChange">Confirm</button>
                        </form>
                        <div class="errorMessageBox" ;>
                            <span class="errorText">
                                <?php
                                echo $_SESSION['usernameChangeErrorMessage'] . '<br>';
                                $_SESSION['usernameChangeErrorMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                        <div class="successMessageBox" ;>
                            <span class="successText">
                                <?php
                                echo $_SESSION['usernameChangeSuccessMessage'] . '<br>';
                                $_SESSION['usernameChangeSuccessMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        Enter your new email address and your password. You will need to verify your new email address in order to be able to log in again.
                        <form action="settings.php" method="POST" class="form">
                            <div class="form__input-group">
                                <input type="email" class="form__input" name="newEmail" style="margin-top: 15px;" placeholder="New email" required>
                            </div>
                            <div class="form__input-group" style>
                                <input type="password" class="form__input" name="pwForEmail" placeholder="Password" required>
                            </div>
                            <button class="form__button" type="submit" name="submitEmailChange">Confirm</button>
                        </form>
                        <div class="errorMessageBox" ;>
                            <span class="errorText">
                                <?php
                                echo $_SESSION['emailChangeErrorMessage'] . '<br>';
                                $_SESSION['emailChangeErrorMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                        <div class="successMessageBox" ;>
                            <span class="successText">
                                <?php
                                echo $_SESSION['emailChangeSuccessMessage'] . '<br>';
                                $_SESSION['emailChangeSuccessMessage'] = FALSE;
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="subBoxRight2" style="font: 500 1rem 'Quicksand', sans-serif;">
                        <div id="notifDescr">
                            By having this set to ON, you will receive notifications in your email when someone likes or comments on your pictures.
                            Currently set to <?php echo getNotifStatusAsText($dbConn); ?>
                        </div>
                        <?php if (checkUsersNotificationsPref($dbConn) == true) { ?>
                            <img class="notifIcon" id="notifIcon" src="./icons/yes32.png" style="margin-top: 25px;" title="Toggle notifications on or off" onclick="toggleNotifications(0)"></img>
                        <?php } else { ?>
                            <img class="notifIcon" id="notifIcon" src="./icons/no32.png" style="margin-top: 25px;" title="Toggle notifications on or off" onclick="toggleNotifications(1)"></img>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="gallery_features.js"></script>
    </body>

</html>

<?php

}

?>