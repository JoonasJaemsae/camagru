<?php

include_once 'dbh.inc.php';

try {
    $dbConn = new PDO($dsn, $username, $password);
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection from new_conn.php successful!" . "<br>";
} catch (PDOException $errorMessage) {
    echo $errorMessage . "<br>";
    echo "Something went wrong when trying to connect to the database." . "<br>";
}
