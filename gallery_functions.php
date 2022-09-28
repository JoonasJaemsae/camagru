<?php

session_start();

require_once './config/new_conn.php';

$user_id = $_SESSION['logged_in_user_id'];
$image_id = $_POST['imageId'];

$sql = "SELECT image_data FROM images WHERE image_id=?;";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$image_id]);
$image_data = $stmt->fetch(PDO::FETCH_COLUMN);
echo  "data:image/jpeg;base64," . $image_data;
