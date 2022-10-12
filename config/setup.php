<?php

require_once 'database.php';

try {
    $dbConn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection from new_conn.php successful!" . "<br>";
} catch (PDOException $errorMessage) {
    echo $errorMessage . "<br>";
    echo "Something went wrong when trying to connect to the database." . "<br>";
}

$sql = "CREATE DATABASE IF NOT EXISTS `joonasja_camagru`";
$dbConn->exec($sql);

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `users` (
	`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
    `verification_code` VARCHAR(255) NOT NULL,
    `email_is_verified` INT(11) NOT NULL,
    `notifications` INT(11) NOT NULL
)";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `images` (
	`image_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` VARCHAR(255) NOT NULL,
    `image_data` LONGTEXT NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `likes` (
	`like_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` INT(11) NOT NULL,
    `image_id` INT(11) NOT NULL
)";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `password_requests` (
	`request_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`email` VARCHAR(255) NOT NULL,
    `reset_link_url` VARCHAR(255) NOT NULL,
    `active_bool` INT(11) NOT NULL,
    `creation_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `comments` (
	`comment_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL,
    `image_id` INT(11) NOT NULL,
	`content` VARCHAR(255) NOT NULL,
    `creation_datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)";
$dbConn->exec($sql);

echo "Setup has finished running. See above message to learn if the setup was successful or not." . "<br>";

?>