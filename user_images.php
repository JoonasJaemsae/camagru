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
    <title>Camagru - Your pictures</title>

    <style>
        <?php include "style.css"; ?>
    </style>
</head>
<?php

if ($_GET['page'] < 0 || $_GET['page'] > $number_of_pages) {
    header("Location: ./user_images.php?page=1");
} 

if (isset($_SESSION['loginPersist'])) {
?>

    <body id="gradient">
        <?php

        include_once 'navbar.php';

        ?>
        <div style="width: 100%; height: 40px;"></div>
        <div class="galleryPhotoArea">
            <div class="galleryPhotos" id="galleryPhotos">
                <?php
                foreach ($usersImages as $key => $value) {
                    $base64 = $value['image_data'];
                    $image = 'data:image/jpeg;base64,' . $base64;
                ?>
                    <div class="galleryElement">
                        <div class="handleElement"><?php echo $value['username'] . ' ' . $value['image_id'] ?></div>
                        <img class="photoElement" src="<?php echo $image; ?>"></img>
                        <div class="iconElement">
                            <div class="iconElementLeft">
                                <div id="likeAmount<?php echo $value['image_id']; ?>">Likes: <?php echo getImageLikeCount($value['image_id'], $dbConn);?></div>
                            </div>
                            <div class="iconElementRight">
                                <img class="likeIcon" id="delete<?php echo $value['image_id'] ?>" src="./icons/trash32.png" title="Delete the picture" onclick=confirmDelete(<?php echo $value['image_id'] ?>)></img>       
                                <?php if (checkUsersLike($value['image_id'], $dbConn) == true) { ?>
                                    <img class="likeIcon" id="like<?php echo $value['image_id'] ?>" src="./icons/heartfull32.png" title="Like the picture" onclick="adjustLikeStatus(this.id, <?php echo $_SESSION['logged_in_user_id']?> )"></img>
                                <?php } else { ?>
                                    <img class="likeIcon" id="like<?php echo $value['image_id'] ?>" src="./icons/heartempty32.png" title="Like the picture" onclick="adjustLikeStatus(this.id, <?php echo $_SESSION['logged_in_user_id']?> )"></img>
                                <?php } ?>
                            </div>
                            <div class="commentElement">
                            </div>
                        </div>
                    </div>
                <?php
                }
                // <?php echo $value['image_id'];
                ?>

            </div>
        </div>
        <div class="pageArea">
            <?php
            if ($page > 1) {
            ?>
                <a href="gallery.php?page=<?php echo ($page - 1); ?>"><</a>
            <?php
            }
            for ($page = 1; $page <= $number_of_pages; $page++) {
            ?>
                <a href="gallery.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
            <?php
            }
            if ($_GET['page'] < $number_of_pages) {
            ?>
                <a href="gallery.php?page=<?php echo ($_GET['page'] + 1); ?>">></a>
            <?php
            }
            ?>
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
        <script src="gallery_features.js"></script>
    </body>



</html>
<?php

}

?>