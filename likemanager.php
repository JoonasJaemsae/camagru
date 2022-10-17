<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_POST['likedImage'])) {
	header('Location: gallery.php');
	return;
}

if (isset($_SESSION['logged_in_user_id']) && $_SESSION["logged_in_user_id"] !== '') {
	$agent = $_SESSION['logged_in_user_id'];
	$likedImage = $_POST['likedImage'];

	$sql = "USE `joonasja_camagru`";
	$dbConn->exec($sql);

	$sql = "SELECT * FROM likes WHERE `user_id`=? AND `image_id`=?;";
	$stmt = $dbConn->prepare($sql);
	$stmt->execute([$agent, $likedImage]);
	$result = $stmt->fetch();
	if ($stmt->rowCount() > 0) {
		$sql = "DELETE FROM likes WHERE `user_id`=? AND `image_id`=?;";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute([$agent, $likedImage]);
	} else {
		$sql = "INSERT INTO likes (`user_id`, `image_id`)
			VALUES (?, ?);";
		$stmt = $dbConn->prepare($sql);
		$stmt->execute([$agent, $likedImage]);
	}
}