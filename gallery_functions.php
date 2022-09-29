<?php

require_once './config/new_conn.php';

$sql =
"SELECT image_id, image_data, username
FROM images
LEFT JOIN syottotesti
ON images.user_id = syottotesti.id
ORDER BY image_id DESC;
";

$stmt = $dbConn->prepare($sql);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $user_id = $_SESSION['logged_in_user_id'];
// $image_id = $_POST['imageId'];
// echo  "data:image/jpeg;base64," . $image_data;
