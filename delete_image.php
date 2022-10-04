<?php

session_start();

require_once './config/new_conn.php';

$agent = $_SESSION['logged_in_user_id'];
$imageToDelete = $_POST['imageId'];

$sql = "USE `rex`";
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