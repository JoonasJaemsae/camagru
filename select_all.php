<?php

$user = 'root';
$password = 'root';
$db = 'testusers';
$host = 'localhost';
$port = 8889;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link,
   $host,
   $user,
   $password,
   $db,
   $port
);

$sql = "select * from testusers";   
$res = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($res);
print_r($row);

?>