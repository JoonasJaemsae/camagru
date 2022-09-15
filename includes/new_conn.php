<?php

include_once 'dbh.inc.php';

try {
    $dbConn = new PDO($dsn, $username, $password);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!" . "<br>";
} catch (PDOException $errorMessage) {
    echo $errorMessage . "<br>";
    echo "Jotain meni pieleen" . "<br>";
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