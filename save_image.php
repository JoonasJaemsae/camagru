<?php

session_start();

require_once './config/new_conn.php';

$image_url = $_POST['new_image'];
$image_url = preg_replace('/^data:image\/jpeg;base64,/', '', $image_url);
$image_url = str_replace(' ','+', $image_url);

$image_base64_decoded = base64_decode($image_url);
$user_id = $_SESSION['logged_in_user_id'];

$sql = "USE `rex`";
$dbConn->exec($sql);

$sql = "INSERT INTO images (`user_id`, `image_data`, `timestamp`)
    VALUES ('1', '2', now());";
$dbConn->exec($sql);

$sql = "INSERT INTO syottotesti (`username`, `password`, `email`)
    VALUES ('testi', 'testi', 'testi');";
$dbConn->exec($sql);

echo 'data:image/jpeg;base64,' . $image_url;
