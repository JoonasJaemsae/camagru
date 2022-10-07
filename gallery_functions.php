<?php

$results_per_page = 5;

$sql = "SELECT * FROM images;";
$stmt = $dbConn->prepare($sql);
$stmt->execute();
$number_of_pages = CEIL($stmt->rowCount() / $results_per_page);

// Pagination might break for a sec, if user tries to input a string onto ?page=.
// || !is_int($_GET['page'])

// The if in the first else clause guards against situation where the user tries to access page 0 or lower or a page that's beyond the last page.
if (!isset($_GET['page'])) {
    $page = 1;
    $_GET['page'] = 1;
} else {
    if ($_GET['page'] <= 0 || $_GET['page'] > $number_of_pages) {
        $page = 1;
        header('Location: ./gallery.php?page=1');
        return;
    } else {
        $page = $_GET['page'];
    }
}

$page_first_result = ($page - 1) * $results_per_page;

// LIMIT: Starting from page_first_result, a total of results_per_page images per page.
$sql = "SELECT image_id, image_data, username, users.id AS userid
        FROM images
        INNER JOIN users
        ON images.user_id = users.id
        ORDER BY image_id DESC
        LIMIT " . $page_first_result . "," . $results_per_page;

$stmt = $dbConn->prepare($sql);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['logged_in_user_id'])) {
    $user_id = $_SESSION['logged_in_user_id'];
    $sql2 = "SELECT image_id, image_data, username, `users`.`id` AS userid
            FROM images
            INNER JOIN users
            ON images.user_id = users.id
            WHERE `users`.`id` = ?
            ORDER BY image_id DESC;
            ";

    $stmt = $dbConn->prepare($sql2);
    $stmt->execute([$user_id]);
    $usersImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function checkUsersLike($image_id, $dbConn)
{
    if (isset($_SESSION['logged_in_user_id'])) {
        $user_id = $_SESSION['logged_in_user_id'];

        $sql = "SELECT `likes`.`like_id`, `likes`.`user_id`, `likes`.`image_id`, `users`.`username`
                FROM likes
                INNER JOIN users
                ON likes.user_id = users.id
                INNER JOIN images
                ON likes.image_id = images.image_id
                WHERE `likes`.`user_id`=? AND `likes`.`image_id`=?;
                ";

        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$user_id, $image_id]);
        // $likeCheck = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
            // echo "TRUUUU! " . $user_id . ' ' . $image_id  . '<br>'; // for testing.
            return true;
        } else {
            return false;
        }
    }
}

function getNotifStatusAsText($dbConn) {
    $user_id = $_SESSION['logged_in_user_id'];
    $sql = "SELECT notifications
            FROM users
            WHERE id=?;
            ";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data['notifications'] == 1) {
        return "ON.";
    } else {
        return "OFF.";
    }
}

function checkUsersNotificationsPref($dbConn)
{
    $user_id = $_SESSION['logged_in_user_id'];
    $sql = "SELECT notifications
            FROM users
            WHERE id=?;
            ";

    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data['notifications'] == 1) {
        return true;
    } else {
        return false;
    }
}

function getImageLikeCount($image_id, $dbConn)
{
    $sql = "SELECT COUNT(likes.image_id) as total
            FROM likes
            WHERE image_id=?;
            ";

    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$image_id]);
    $count = $stmt->fetch();
    // echo $count['total'];
    if ($count['total']) {
        return $count['total'];
    } else {
        return (0);
    }
}

// The below is for deleting an image

if (isset($_SESSION['logged_in_user_id']) && isset($_POST['delete_action']) && isset($_POST['image_to_delete'])) {
    $agent = $_SESSION['logged_in_user_id'];
    $imageToDelete = $_POST['image_to_delete'];

    $sql = "USE `joonasja_camagru`";
    $dbConn->exec($sql);

    $sql = "SELECT * FROM images WHERE `user_id`=? AND `image_id`=?;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$agent, $imageToDelete]);
    if ($stmt->rowCount() > 0) {
        echo "if" . '<br>';
        $sql = "DELETE FROM images WHERE `user_id`=? AND `image_id`=?;";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$agent, $imageToDelete]);
        $sql = "DELETE FROM likes WHERE `image_id`=?;";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$imageToDelete]);
    } else {
        echo "else " . $agent . ' ' . $imageToDelete . '<br>';
    }
    if ($_POST['delete_action'] == 'delete') {
        header("Location: gallery.php", true, 303);
    } else {
        header("Location: user_images.php", true, 303);
    }
}
