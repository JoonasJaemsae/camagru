<?php

session_start();

if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

    header("Location: index.php");
    exit();
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        <?php include "style.css"; ?>
    </style>
</head>

<body id="gradient">
    <?php

    include_once 'navbar.php';

    ?>
    <div class="profileMenuArea">
        <div class="profileMenu">
            <div class="optionElement">
                <a href="settings.php" class="nav__icon">
                    <img src="./icons/settings128.png" title="Settings"></img>
                </a>
            </div>
            <div class="optionElement">
                <a href="user_images.php" class="nav__icon">
                    <img src="./icons/image128.png" title="Your images"></img>
                </a>
            </div>
        </div>
    </div>

</body>

</html>