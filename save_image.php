<?php

session_start();

require_once './config/new_conn.php';

$image_url = $_POST['new_image'];
$stickerArray = $_POST['stickers'];
$image_url = preg_replace('/^data:image\/jpeg;base64,/', '', $image_url);
$image_url = str_replace(' ','+', $image_url);
// For some reason, the dataURL is littered with spaces, and they need to be filled in with +'s in order
// to be able to save the URL in an SQL table properly.

$image_base64_decoded = base64_decode($image_url);
$user_id = $_SESSION['logged_in_user_id'];

// $i = 0;

$image_php = imagecreatefromstring($image_base64_decoded);
$stamp = imagecreatefrompng($stickerArray[$i]);
list($width, $height) = getimagesize($image_base64_decoded); // list() is used to assign a list of variables in one operation.
list($width_small, $height_small) = getimagesize($stickerArray[$i]);
// In the return values of getimagesize(), index 0 and 1 contain the width and the height, respectively.
$marge_right = ($width/2)-($width_small/2);
$marge_bottom = ($height/2)-($height_small/2);
$sx = imagesx($stamp);  // Returns width of image as int.
$sy = imagesy($stamp);  // Returns height of image as int.
imagealphablending($image_php, true);
imagesavealpha($image_php, true);
imagecopy($image_php, $stamp, imagesx($image_php) - $sx - $marge_right, imagesy($image_php) - $sy - $marge_bottom, 0, 0, $width_small, $height_small); // marge_right is the distance from the left side of dest to the left side of the stamp.
// When we subtract both sx and and marge_right from the final image width, we get the left side coords of the stamp. This is useful when we want to superpose a stamp in the middle.
// Source Image, Overlay Image,x,y For placing the overlay image on center,0,0 and width and height for play button image
//imagepng($image_1, "image_3.png");
imagejpeg($image_php, $dest);

$image = ($image_php, $dest);

// $i = $i + 3;

imagecopy(
    GdImage $dst_image,
    GdImage $src_image,
    int $dst_x,
    int $dst_y,
    int $src_x,
    int $src_y,
    int $src_width,
    int $src_height
): bool

$sql = "USE `rex`";
$dbConn->exec($sql);

$sql = "INSERT INTO images (`user_id`, `image_data`)
    VALUES ('$user_id', '$image_url');";
$dbConn->exec($sql);

$sql = "INSERT INTO syottotesti (`username`, `password`, `email`)
    VALUES ('testi', 'testi', 'testi');";
$dbConn->exec($sql);

// echo 'data:image/jpeg;base64,' . $image_url;
// echo 'Sticker Array: ' . $stickerArray;
print_r($stickerArray);
// echo $image_base64_decoded;
