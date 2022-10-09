<?php

session_start();

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

include_once './config/new_conn.php';
require 'email_functions.php';
// ob_start();

if ($_SESSION['loginSuccess'] === TRUE || $_SESSION['loginPersist'] === TRUE) {
    header('Location: gallery.php');
    return;
}
if ($_SESSION['signupSuccess'] == TRUE) {    // Comparing to true with === produces a warning message sometimes for some reason.
    header("Location: signup_success.php");
    return;
}

function checkPasswords($password, $passwordAgain)
{
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
    $user = $stmt->fetch(); // If fetch takes as a parameter PDO::FETCH_COLUMN, we will get a blank screen with an error message.
    if (!checkPasswords($_POST['password'], $_POST['passwordAgain'])) {
        $_SESSION['signupErrorMessage'] = 'The passwords you entered did not match each other. Please try again!';
        // echo $_SESSION['message'] . '<br>' . 'Tamako nakyy tyhjalla sivulla?';
    } else if ($stmt->rowCount() > 0) {    // $user['username'] is not FALSE if it was found with the SQL query i.e. it exist in the database.
        $_SESSION['signupErrorMessage'] = 'This username is not available. Please try another one.';
    } else {
        $_SESSION['signupSuccess'] = TRUE;
        $password = hash('whirlpool', $password);
        $verifCode = generateRandomString(10, $dbConn);
        $sql = "INSERT INTO users (`username`, `password`, `email`, `verification_code`, `email_is_verified`, `notifications`)
                VALUES ('$username', '$password', '$email', '$verifCode', 0, 1);";
        $dbConn->exec($sql);
        sendVerificationEmail($email, $verifCode, $dbConn);
        $_SESSION['signupErrorMessage'] = '';
    }
    $_POST['submit'] = FALSE; // This might not be good actually, because we're looking for isset in this bigger if clause, and FALSE is still true for isset, afaik.
    header("Location: signup.php");
    return;
}
// $user['username'] != FALSE

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Create an account</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="index">
    <!-- <-- If id is index, then background will be image. With signup it's linear gradient. -->
    <?php
    $message = isset($_SESSION['signupErrorMessage']) ? $_SESSION['signupErrorMessage'] : FALSE;
    // if $_SESSION['signupErrorMessage'] is set, copy that to $message. Otherwise copy FALSE.
    // echo 'Yläkulman viesti alla:';
    // echo '<br>';
    // echo $message . ' <-- message vasemmalla.';
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
                <input type="text" class="form__input" name="email" placeholder="Email" required>
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
                echo $message . ' on the same row' . '<br>';
            }
            if ($message == TRUE) {
                echo 'Message is TRUE';
                $_SESSION['signupErrorMessage'] = FALSE; // This is to make it so that the message doesn't show up again on reloading the page.
            } else if ($message == FALSE) {
                // echo "Falsehan se lopuksikin." . '<br>';
                echo 'Message is FALSE';
            }
            ?>
        </div>
    </div>
</body>

</html>

<?php
// function display()
//             {
//                 echo "Hello " . $_POST['login'];
//             }
// if (isset($_POST['submit'])) {
//     display();
// }

// INSERT INTO testusers (`username`, `password`, `email`)
// VALUES ('jjamsa', 'root', 'jjamsa@geemail.com');
// INSERT INTO testusers (`username`, `password`, `email`)
// VALUES ('lsalmi', 'test123', 'lsalmi@geemail.com');
// INSERT INTO testusers (`username`, `password`, `email`)
// VALUES ('mapostol', 'test1234', 'mapostol@geemail.com');
// INSERT INTO testusers (`username`, `password`, `email`)
// VALUES ('plehtika', 'test12345', 'plehtika@geemail.com');


// } else {
    // echo '<p style="color: red; text-align: center">Please fill in all the fields!</p>';
// }
