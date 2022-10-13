<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_POST['imageId'])) {
    header('Location: index.php');
	exit ;
}

$agent = $_SESSION['logged_in_user_id'];
$imageToDelete = $_POST['imageId'];

if ($userid != $logged_in_id) {
    echo "We were here!";
    header('Location: index.php');
    exit ;
}

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

$sql = "SELECT * FROM images WHERE `user_id`=? AND `image_id`=?;";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$agent, $imageToDelete]);
if ($stmt->rowCount() > 0) {
    $sql = "DELETE FROM images WHERE `user_id`=? AND `image_id`=?;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$agent, $imageToDelete]);
    $sql = "DELETE FROM likes WHERE `image_id`=?;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$imageToDelete]);
    $sql = "DELETE FROM comments WHERE `image_id`=?;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$imageToDelete]);
} else {
    echo "The image couldn't be deleted because the user doesn't own the image.";
}