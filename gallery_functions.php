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
    if ($_GET['page'] <= 0 || $_GET['page'] > $number_of_pages || !getPageRegexCheck($_GET['page'])) {
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

$sql2 = "SELECT comment_id, comments.image_id, content, users.id AS userid, users.username AS username
        FROM comments
        INNER JOIN users
        ON comments.user_id = users.id
        ORDER BY comments.creation_datetime ASC;";

$stmt = $dbConn->prepare($sql2);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

function getPageRegexCheck($get_page)
{
    // The page is not allowed to have whitespace characters.
    if (preg_match("/\s/", $get_page)) {
        return FALSE;
    }
    if (preg_match("/[A-Z]/", $get_page)) {
        return FALSE;
    }
    if (preg_match("/[a-z]/", $get_page)) {
        return FALSE;
    }
    // The password should contain at least one special character or an underscore.
    if (preg_match("/\W/", $get_page) || (preg_match("/_/", $get_page))) {
        return FALSE;
    }
    return TRUE;
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

function getNotifStatusAsText($dbConn)
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

if (isset($_SESSION['logged_in_user_id']) && isset($_POST['gallery'])) {
}
