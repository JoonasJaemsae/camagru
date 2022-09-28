<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <div class="navbar">
        <a href="gallery.php" style="font-size: 1.4rem; font-family: 'Brush Script MT'; font-style: bold; border-right: 1px solid black; text-decoration: none; color: black;">
            Camagru
        </a>
        
        <a style="font-size: min(max(10px, 2vw), 18px);">
            <?php
            echo 'Welcome, ' . $_SESSION["username"] . " " . $_SESSION["logged_in_user_id"] . '!';
            ?>
        </a>
        <a href="webcam.php" class="nav__link" style="font-size: min(max(10px, 2vw), 18px)">Upload a picture</a>
        <div class="navbar-right" style="border-right: none;">
            <a href="logout.php" class="nav__link" style="font-size: min(max(10px, 2vw), 18px)">Profile</a>
            <a href="logout.php" class="nav__link" style="text-align: right; font-size: min(max(10px, 2vw), 18px)">Log out</a>
        </div>
    </div>

</body>

</html>