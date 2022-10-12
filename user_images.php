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
                        <div class="handleElement"><?php echo htmlspecialchars($value['username']) ?></div>
                        <img class="photoElement" src="<?php echo $image; ?>"></img>
                        <div class="iconElement">
                            <div class="iconElementLeft">
                                <div id="likeAmount<?php echo $value['image_id']; ?>">Likes: <?php echo getImageLikeCount($value['image_id'], $dbConn); ?></div>
                            </div>
                            <div class="iconElementRight">
                                <img class="likeIcon" id="delete<?php echo $value['image_id'] ?>" src="./icons/trash32.png" title="Delete the picture" onclick=confirmDelete(<?php echo $value['image_id'] ?>)></img>
                                <?php if (checkUsersLike($value['image_id'], $dbConn) == true) { ?>
                                    <img class="likeIcon" id="like<?php echo $value['image_id'] ?>" src="./icons/heartfull32.png" title="Like the picture" onclick="adjustLikeStatus(this.id, <?php echo $_SESSION['logged_in_user_id'] ?> )"></img>
                                <?php } else { ?>
                                    <img class="likeIcon" id="like<?php echo $value['image_id'] ?>" src="./icons/heartempty32.png" title="Like the picture" onclick="adjustLikeStatus(this.id, <?php echo $_SESSION['logged_in_user_id'] ?> )"></img>
                                <?php } ?>
                            </div>
                        </div>
                        <div class=" commentElement">
                            <?php
                            foreach ($comments as $key => $comment) {
                                if ($value['image_id'] == $comment['image_id']) { ?>
                                    <div>
                                        <div><?php echo htmlspecialchars($comment['username']) ?></div>
                                        <div><?php echo htmlspecialchars($comment['content']) . '<br>'; ?></div>
                                    </div>
                            <?php
                                }
                            } ?>
                        </div>
                        <?php if ($_SESSION['logged_in_user_id'] == TRUE) { ?>
                            <div class="formElement">
                                <form action="user_images.php" id="formElement<?= $value['image_id'] ?>" method="POST" class="form">
                                    <input type="hidden" class="form__input2" name="comment_image_id" value="<?= $value['image_id'] ?>">
                                    <div class="form__input-group">
                                        <input type="text" class="form__input2" name="comment" required>
                                    </div>
                                    <button class="form__button2" type="submit" name="submitComment" onclick="postComment(<?= $value['image_id'] ?>)">Post comment</button>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                <?php
                }
                // <?php echo $value['image_id'];
                ?>

            </div>
        </div>
        <script src="gallery_features.js"></script>
    </body>



</html>
<?php

}

?>