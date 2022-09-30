<?php

session_start();

require_once './config/new_conn.php';

$agent = $_SESSION['logged_in_user_id'];
$likedImage = $_POST['likedImage'];

$sql = "USE `rex`";
$dbConn->exec($sql);

$sql = "SELECT * FROM likes WHERE `user_id`=? AND `image_id`=?;";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$agent, $likedImage]);
$result = $stmt->fetch();
if ($stmt->rowCount() > 0) {
    echo "if" . '<br>';
    $sql = "DELETE FROM likes WHERE `user_id`=? AND `image_id`=?;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$agent, $likedImage]);
} else {
    echo "Not if" . '<br>';
    $sql = "INSERT INTO likes (`user_id`, `image_id`)
        VALUES ('$agent', '$likedImage');";
    $dbConn->exec($sql);
}

// $sql = "INSERT INTO likes (`user_id`, `image_id`)
//     VALUES ('$user_id', '$image_url');";
// $dbConn->exec($sql);

// echo "Made it here!";