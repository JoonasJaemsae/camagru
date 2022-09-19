<?php

include_once 'database.php';

try {
    $dbConn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection from new_conn.php successful!" . "<br>";
} catch (PDOException $errorMessage) {
    echo $errorMessage . "<br>";
    echo "Something went wrong when trying to connect to the database." . "<br>";
}
