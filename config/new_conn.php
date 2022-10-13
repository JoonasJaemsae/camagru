<?php

require_once 'database.php';

try {
    $dbConn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $errorMessage) {
    echo $errorMessage . "<br>";
}
