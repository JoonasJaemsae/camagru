<?php

session_start();

require_once './config/new_conn.php';

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
    VALUES ('$user_id', '$image');";
$dbConn->exec($sql);

// The below is if you want to test if any SQL command is run at all.
// $sql = "INSERT INTO users (`username`, `password`, `email`)
//     VALUES ('testi', 'testi', 'testi');";
// $dbConn->exec($sql);

echo "data:image/jpeg;base64," . $image;
// echo "Data that came through to PHP: " . $_POST['stickerData'];
// echo 'data:image/jpeg;base64,' . $image_url;


// Below you'll find wip stuff from the process.

// $image_width = imagesx($image_php);  // Returns width of image as int.
// $image_height = imagesy($image_php);  // Returns height of image as int.
// $sx = imagesx($sticker);
// $sy = imagesy($sticker);
// $margin_right = ($image_width/2)-($sx/2);
// $margin_bottom = ($image_height/2)-($sy/2);

// imagecopy($image_php, $sticker, 230, 230, 0, 0, $sx, $sy);

// $image_base64_decoded = base64_decode($image_url);
// $image_php = imagecreatefromstring($image_base64_decoded); // Returns a GDImage instance.
// $sticker = imagecreatefrompng("stickers/confetti.png");

// $image_width = imagesx($image_php);  // Returns width of image as int.
// $image_height = imagesy($image_php);  // Returns height of image as int.
// $sx = imagesx($sticker);
// $sy = imagesy($sticker);
// $margin_right = ($image_width/2)-($sx/2);
// $margin_bottom = ($image_height/2)-($sy/2);
// imagealphablending($image_php, true);
// imagesavealpha($image_php, true);
// imagecopy($image_php, $sticker, imagesx($image_php) - $sx - $margin_right, imagesy($image_php) - $sy - $margin_bottom, 0, 0, $sx, $sy);
// margin_right is the distance from the left side of dest to the left side of the sticker.
// When we subtract both sx and and margin_right from the final image width, we get the middle image coords for the sticker. This is useful when we want to superpose a sticker in the middle.