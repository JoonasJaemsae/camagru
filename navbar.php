<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

    <div class="navbar">
        <div class="navbarElements">
            <div class="navbar-left">
                <a href="gallery.php" class="nav__iconText">Camagru</a>
                <a class="nav__welcomeText">
                    <?php
                    if ($_SESSION['logged_in_user_id'] == TRUE) {
                        echo 'Welcome, ' .  $_SESSION["username"] . ' ' . $_SESSION['logged_in_user_id'] . '!';
                    }
                    ?>
                </a>
                <div class="optionElement">
                    <a href="webcam.php" class="nav__icon">
                        <img src="./icons/camera32.png" title="Upload a picture"></img>
                    </a>
                </div>
            </div>
            <div class="navbar-right">
                <div class="optionElement">
                    <a href="profile.php" class="nav__icon">
                        <img src="./icons/profile32.png" title="Profile"></img>
                    </a>
                </div>
                <div class="optionElement">
                    <?php
                    if ($_SESSION['logged_in_user_id'] == TRUE) {
                    ?>
                        <a href="logout.php" class="nav__icon">
                            <img src="./icons/logout32.png" title="Log out"></img>
                        </a>
                    <?php
                    } else {
                    ?>
                        <a href="index.php" class="nav__icon">
                            <img src="./icons/login32.png" title="Log in"></img>
                        </a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

</body>

<!-- <a href="webcam.php" class="nav__link" style="font-size: min(max(10px, 2vw), 18px)">Upload a picture</a> -->
<!-- <a href="logout.php" class="nav__link" style="font-size: min(max(10px, 2vw), 18px)">Profile</a> -->
<!-- <a href="logout.php" class="nav__link" style="text-align: right; font-size: min(max(10px, 2vw), 18px)">Log out</a> -->

</html>