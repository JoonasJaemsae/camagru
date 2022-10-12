<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_SESSION['logged_in_user_id'])) {
    $_SESSION['logged_in_user_id'] = FALSE;
}
if ($_SESSION['logged_in_user_id'] == FALSE) {
    header('Location: index.php');
    return;
}

$user_id = $_SESSION['logged_in_user_id'];
$image_url = $_POST['new_image'];
$stickerData = explode(',', $_POST['stickerData']);
$image_url = preg_replace('/^data:image\/jpeg;base64,/', '', $image_url); // Replacing the prefix with '' i.e. effectively deletes it.
$image_url = str_replace(' ', '+', $image_url);
// For some reason, the dataURL is littered with spaces, and they need to be filled in with +'s in order
// to be able to save the URL in an SQL table properly.
$image_base64_decoded = base64_decode($image_url);
$image_php = imagecreatefromstring($image_base64_decoded); // Returns a GDImage instance.
imagealphablending($image_php, true);   // Think about the necessity of these.
imagesavealpha($image_php, true);
$i = 0;

while ($stickerData[$i] != "") {

    $stickerSrc = $stickerData[$i];
    $sticker = imagecreatefrompng($stickerSrc);
    $offsetWidth = $stickerData[$i + 1];
    $offsetHeight = $stickerData[$i + 2];
    $stickerWidth = imagesx($sticker); // Returns width of image as int.
    $stickerHeight = imagesy($sticker); // Returns height of image as int.
    imagecopy($image_php, $sticker, $offsetWidth, $offsetHeight, 0, 0, $stickerWidth, $stickerHeight);
    $i = $i + 3;
}

ob_start(); // Think about the necessity of these. Harmless.
imagejpeg($image_php);
$image_php = ob_get_clean(); // Think about the necessity of these. Harmless.
$image = base64_encode($image_php);

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

$sql = "INSERT INTO images (`user_id`, `image_data`)
    VALUES (?, ?);";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$user_id, $image]);

echo "data:image/jpeg;base64," . $image;
