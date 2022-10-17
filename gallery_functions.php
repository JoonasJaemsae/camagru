<?php

if (!isset($dbConn)) {
    return;
}

$sql2 = "SELECT comment_id, comments.image_id, content, users.id AS userid, users.username AS username
        FROM comments
        INNER JOIN users
        ON comments.user_id = users.id
        ORDER BY comments.creation_datetime ASC;";

$stmt = $dbConn->prepare($sql2);
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['logged_in_user_id']) && $_SESSION["logged_in_user_id"] !== '') {
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
    // The page shouldn't contain special characters or underscores.
    if (preg_match("/\W/", $get_page) || (preg_match("/_/", $get_page))) {
        return FALSE;
    }
    return TRUE;
}

function checkUsersLike($image_id, $dbConn)
{
    if (isset($_SESSION['logged_in_user_id']) && $_SESSION['logged_in_user_id'] !== '') {
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
        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}

function getNotifStatusAsText($dbConn)
{
    if (isset($_SESSION['logged_in_user_id']) && $_SESSION['logged_in_user_id'] !== '') {
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
}

function checkUsersNotificationsPref($dbConn)
{
    if (isset($_SESSION['logged_in_user_id']) && $_SESSION['logged_in_user_id'] !== '') {
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
    if ($count['total']) {
        return $count['total'];
    } else {
        return (0);
    }
}
