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

$sql = "CREATE DATABASE IF NOT EXISTS `rex`";
$dbConn->exec($sql);

$sql = "USE `rex`";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `syottotesti` (
	`id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL
)";
$dbConn->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS `images` (
	`image_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`user_id` VARCHAR(255) NOT NULL,
    `image_data` LONGTEXT NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
)";
$dbConn->exec($sql);

echo "Setup has finished running. See above message to learn if the setup was successful or not." . "<br>";

?>