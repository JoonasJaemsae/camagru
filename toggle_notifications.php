<?php

session_start();

require_once './config/new_conn.php';

$agent = $_SESSION['logged_in_user_id'];
$iconToChangeInto = $_POST['destValue'];

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

$sql = "UPDATE users SET notifications=? WHERE id=?";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$iconToChangeInto, $agent]);
if ($iconToChangeInto == 1) {
    echo "Changed to ON.";
} else if ($iconToChangeInto == 0) {
    echo "Changed to OFF.";
}