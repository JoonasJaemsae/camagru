<html>

<head>
    <title>Camagru</title>

    <style>
        <?php

        session_start();

        include "style.css";
        include_once './includes/dbh.php';

        if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

            header("Location: index.php");
        }
        if ($_SESSION['loginSuccess'] === TRUE) {

            $_SESSION['loginSuccess'] = FALSE;
            $_SESSION['loginPersist'] = TRUE;
            $_SESSION['loginErrorMessage'] = FALSE;
        }

        ?>
    </style>
</head>

</html>

<?php

if (isset($_SESSION['loginPersist'])) {
?>
    <html>

    <body id="gradient">
        <div class="navbar">
            <a style="font-size: 1.4rem; font-family: 'Brush Script MT'; font-style: bold; border-right: 1px solid black;">Camagru</a>
            <a><?php
                echo 'Welcome, ' . $_SESSION["username"] . '!';
                ?></a>
            <a href="logout.php" class="nav__link">Upload a picture</a>
            <div class="navbar-right" style="border-right: none;">
                <a href="logout.php" class="nav__link">Profile</a>
                <a href="logout.php" class="nav__link" style="text-align: right;">Log out</a>
            </div>
        </div>
        <h1 style="margin-top: 100px">
            Welcome, master!
        </h1>
        <div>
            <img src="http://media2.s-nbcnews.com/i/streams/2013/June/130617/6C7911377-tdy-130617-leo-toasts-1.jpg" alt="Congrats!">
        </div>
        <div style="text-align: center;">
            <a href="logout.php">Click here to log out.</a>
        </div>
    </body>

    </html>
<?php

}

?>