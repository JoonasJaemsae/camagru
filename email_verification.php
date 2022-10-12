<?php

session_start();

include_once './config/new_conn.php';

$message = '';
$verifCode = $_GET['verifCode'];

if ($verifCode == '') {
    $message = '<p style="color: red;">This page is for verifying your email address. The verification url you tried to access is not a valid one.</p>';
} else {
    $verifCode = $_GET['verifCode'];
    $sql = "SELECT * FROM users WHERE verification_code=?";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$verifCode]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stmt->rowCount() < 1) {
        $message = '<p style="color: red;">The verification url you tried to access is not a valid one.</p>';
    } else if ($user_data['email_is_verified'] == 1) {
        $message = '<p style="color: red;">Your email has already been verified.</p>';
    } else if ($stmt->rowCount() > 0) {
        $message = '<p style="color: blue;">Your email has been verified successfully!</p>';
        $sql2 = "UPDATE users SET email_is_verified=? WHERE verification_code=?";
        $stmt = $dbConn->prepare($sql2);
        $stmt->execute(['1', $verifCode]);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Email verification</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="index">
    <div class="messageBox" style="text-align: center;">
        <?php
        echo $message;
        ?>
        <a href="./index.php" class="message__link">Click here to move to the login screen.</a>
    </div>

</body>

</html>