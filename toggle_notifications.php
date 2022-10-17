<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_POST['destValue'])) {
    header('Location: gallery.php');
    return;
}

$agent = $_SESSION['logged_in_user_id'];
$destValue = $_POST['destValue'];

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

$sql = "SELECT notifications FROM users WHERE id=?;";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$agent]);
$notif = $stmt->fetch(PDO::FETCH_ASSOC);
if ($notif['notifications'] === 0) {
    $destValue = 1;
} else if ($notif['notifications'] === 1) {
    $destValue = 0;
}

$sql = "UPDATE users SET notifications=? WHERE id=?";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$destValue, $agent]);
if ($destValue === 1) {
    echo "Changed to ON.";
} else if ($destValue === 0) {
    echo "Changed to OFF.";
}