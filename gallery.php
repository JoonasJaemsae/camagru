<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

session_start();
// ob_start(); // if logout redirection doesn't work.

include_once './config/new_conn.php';
require 'gallery_functions.php';

if ($_SESSION['loginSuccess'] === TRUE) {

    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['loginPersist'] = TRUE;
    $_SESSION['loginErrorMessage'] = FALSE;
}
if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

    header("Location: index.php");
    exit();
}

?>
<html>

<head>
    <title>Camagru - Upload a picture</title>

    <style>
        <?php include "style.css"; ?>
    </style>
</head>
<?php

if (isset($_SESSION['loginPersist'])) {
?>

    <body id="gradient">
        <?php

        include_once 'navbar.php';

        ?>
        <h1 style="margin-top: 100px; font: 700 2rem 'Quicksand', sans-serif;">
            <?php
            echo 'Welcome, ' .  $_SESSION["username"] . '!';
            ?>
        </h1>
        <div class="galleryPhotoArea">
            <div class="galleryPhotos" id="galleryPhotos">
                <?php
                foreach ($images as $key => $value) {
                    $base64 = $value['image_data'];
                    $image = 'data:image/jpeg;base64,' . $base64;
                ?>
                    <div class="galleryElement">
                        <div class="handleElement"><?php echo $value['username']; ?></div>
                        <img class="photoElement" src="<?php echo $image; ?>"></img>
                        <div class="iconElement">
                            <a href="delete_photo.php" class="nav__icon">
                                <img src="./icons/trash32.png" title="Delete picture"></img>
                            </a>
                            <a href="like.php" class="nav__icon">
                                <img src="./icons/heartempty32.png" title="Like"></img>
                            </a>
                        </div>
                        <div class="commentElement">
                            
                        </div>
                    </div>
                    <!-- <?php echo $value['image_id']; ?> -->
                <?php
                }
                // <?php echo $value['image_id'];
                ?>

            </div>
        </div>
        <div style="text-align: center;">
            <a href="logout.php">Click here to log out.</a>
        </div>

        <div style="text-align: center;">
            <?php
            if ($_SESSION['loginPersist'] == TRUE) {
                echo '$_SESSION["loginPersist"] is TRUE' . '<br>';
            } else if ($_SESSION['loginPersist'] == FALSE) {
                echo '$_SESSION["loginPersist"] is FALSE' . '<br>';
            }

            if ($_SESSION['loginSuccess'] == TRUE) {
                echo '$_SESSION["loginSuccess"] is TRUE' . '<br>';
            } else if ($_SESSION['loginSuccess'] == FALSE) {
                echo '$_SESSION["loginSuccess"] is FALSE' . '<br>';
            }
            ?>
        </div>
    </body>

    <script src="gallery_features.js"></script>

</html>
<?php

}

?>