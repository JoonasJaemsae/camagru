<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_SESSION['logged_in_user_id'])) {
    $_SESSION['logged_in_user_id'] = FALSE;
}
if ($_SESSION['logged_in_user_id'] == FALSE) {
    header('Location: index.php');
	return;
}

$agent = $_SESSION['logged_in_user_id'];
$imageToDelete = $_POST['imageId'];

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

// Add a check that checks if the user owns the image. The below checks for that one! \/

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