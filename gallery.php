<?php

session_start();

include_once './config/new_conn.php';
require 'gallery_functions.php';

if (!isset($_SESSION['loginSuccess'])) {
    $_SESSION['loginSuccess'] = FALSE;
}
if (!isset($_SESSION['loginPersist'])) {
    $_SESSION['loginPersist'] = FALSE;
}
if (!isset($_SESSION['loginErrorMessage'])) {
    $_SESSION['loginErrorMessage'] = FALSE;
}
if (!isset($_SESSION['logged_in_user_id'])) {
    $_SESSION['logged_in_user_id'] = FALSE;
}
if ($_SESSION['loginSuccess'] === TRUE) {

    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['loginPersist'] = TRUE;
    $_SESSION['loginErrorMessage'] = FALSE;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - View everyone's pictures</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="gradient">
    <?php

    include_once 'navbar.php';

    ?>
    <h1 style="margin-top: 100px;"></h1>
    <div class="galleryPhotoArea">
        <div class="galleryPhotos" id="galleryPhotos">
            <?php

            $results_per_page = 5;

            $sql = "SELECT * FROM images;";
            $stmt = $dbConn->prepare($sql);
            $stmt->execute();
            $number_of_pages = CEIL($stmt->rowCount() / $results_per_page);

            if (!isset($_GET['page'])) {
                $page = 1;
                $_GET['page'] = 1;
            } else {
                if (
                    $_GET['page'] <= 0 || $_GET['page'] > $number_of_pages
                    || !getPageRegexCheck($_GET['page']) || !is_numeric($_GET['page'])
                ) {
                    $page = 1;
                    header('Location: ./gallery.php?page=1');
                    return;
                } else {
                    $page = $_GET['page'];
                }
            }

            $page_first_result = ($page - 1) * $results_per_page;

            $sql = "SELECT image_id, image_data, username, users.id AS userid
                    FROM images
                    INNER JOIN users
                    ON images.user_id = users.id
                    ORDER BY image_id DESC
                    LIMIT " . $page_first_result . "," . $results_per_page;

            $stmt = $dbConn->prepare($sql);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($images as $key => $value) {
                $base64 = $value['image_data'];
                $image = 'data:image/jpeg;base64,' . $base64;
            ?>
                <div class="galleryElement">
                    <div class="handleElement"><?php echo htmlspecialchars($value['username']) ?></div>
                    <img class="photoElement" src="<?php echo $image; ?>"></img>
                    <div class="iconElement">
                        <div class="iconElementLeft">
                            <div id="likeAmount<?php echo $value['image_id'] ?>">Likes: <?php echo getImageLikeCount($value['image_id'], $dbConn); ?></div>
                        </div>
                        <div class="iconElementRight">
                            <?php if ($value['userid'] == $_SESSION['logged_in_user_id']) { ?>
                                <img class="likeIcon" id="delete<?php echo $value['image_id'] ?>" src="./icons/trash32.png" title="Delete the picture" onclick=confirmDelete(<?php echo $value['image_id'] ?>)></img>
                            <?php } else { ?>

                            <?php } ?>
                            <?php if (checkUsersLike($value['image_id'], $dbConn) == true) { ?>
                                <img class="likeIcon" id="like<?php echo $value['image_id'] ?>" src="./icons/heartfull32.png" title="Like the picture" onclick="adjustLikeStatus(this.id, <?php echo $_SESSION['logged_in_user_id'] ?>)"></img>
                            <?php } else { ?>
                                <img class="likeIcon" id="like<?php echo $value['image_id'] ?>" src="./icons/heartempty32.png" title="Like the picture" onclick="adjustLikeStatus(this.id, <?php echo $_SESSION['logged_in_user_id'] ?>)"></img>
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
                            <form action="gallery.php" id="formElement<?= $value['image_id'] ?>" method="POST" class="form">
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
            ?>
        </div>
    </div>
    <div class="pageArea">
        <?php
        $currentPage = $page;
        if ($page > 1) {
        ?>
            <a href="gallery.php?page=<?php echo ($page - 1); ?>">
                < <?php
                }
                for ($page = 1; $page <= $number_of_pages; $page++) {
                    ?> <a href="gallery.php?page=<?php echo $page; ?>"><?php echo $page; ?>
            </a>
        <?php
                }
                if ($_GET['page'] < $number_of_pages) {
        ?>
            <a href="gallery.php?page=<?php echo ($currentPage + 1); ?>">></a>
        <?php
                }
        ?>
    </div>
    <script src="gallery_features.js"></script>
</body>

</html>