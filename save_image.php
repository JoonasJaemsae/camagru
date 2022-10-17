<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_POST['new_image']) || !isset($_POST['stickerData'])) {
    header('Location: gallery.php');
    return;
}

if ($_POST['new_image'] == '') {
    header('Location: gallery.php');
    return;
}

function validateStickerSrc($stickerSrc)
{
    $stickerParts = explode('/', $stickerSrc);
    $arr = array_slice($stickerParts, -2, 2);
    $sticker = '';
    if ($arr[0] != 'stickers') {
        return FALSE;
    }
    $stickerFolder = './stickers/';
    if ($arr[1] == 'crown.png') {
        $sticker = 'crown.png';
    }
    if ($arr[1] == '42.png') {
        $sticker = '42.png';
    }
    if ($arr[1] == 'confetti.png') {
        $sticker = 'confetti.png';
    }
    if ($arr[1] == 'confettirev.png') {
        $sticker = 'confettirev.png';
    }
    if ($arr[1] == 'kanagawawave.png') {
        $sticker = 'kanagawawave.png';
    }
    if ($arr[1] == 'balloons3.png') {
        $sticker = 'balloons3.png';
    }
    if ($arr[1] == 'balloons5.png') {
        $sticker = 'balloons5.png';
    }
    if ($arr[1] == 'blackhat.png') {
        $sticker = 'blackhat.png';
    }
    if ($arr[1] == 'policehat.png') {
        $sticker = 'policehat.png';
    }
    if ($arr[1] == 'grayhat.png') {
        $sticker = 'grayhat.png';
    }
    if ($arr[1] == 'halloweenhat.png') {
        $sticker = 'halloweenhat.png';
    }
    if ($arr[1] == 'birdbrown.png') {
        $sticker = 'birdbrown.png';
    }
    if ($arr[1] == 'birdpurple.png') {
        $sticker = 'birdpurple.png';
    }
    if ($arr[1] == 'birdwhite.png') {
        $sticker = 'birdwhite.png';
    }
    if ($arr[1] == 'birdyellow.png') {
        $sticker = 'birdyellow.png';
    }
    if ($sticker == '') {
        return FALSE;
    }
    $stickerPath = $stickerFolder . $sticker;
    return $stickerPath;
}

$stickerFlag = TRUE;
$saveImageIntoDB = TRUE;
$user_id = $_SESSION['logged_in_user_id'];
$image_url = $_POST['new_image'];
if ($_POST['stickerData'] == '') {
    $stickerFlag = FALSE;
} else {
    $stickerData = explode(',', $_POST['stickerData']);
}

// With proportional dimensions of 4:3, if the image size is 3543 or under, it's either a completely empty image or not an image at all.
if (!preg_match('/^data:image\/jpeg;base64,/', $image_url) || strlen($image_url) < 3544) {
    echo "ImageError";
    return;
}

$image_url = preg_replace('/^data:image\/jpeg;base64,/', '', $image_url); // Replacing the prefix with '' i.e. effectively deletes it.
$image_url = str_replace(' ', '+', $image_url);
// For some reason, the dataURL is littered with spaces, and they need to be filled in with +'s in order
// to be able to save the URL in an SQL table properly.
$image_base64_decoded = base64_decode($image_url);
$image_php = imagecreatefromstring($image_base64_decoded); // Returns a GDImage instance.

// Check if we were not able to create a 640x480 sized image from the url.
if (imagesx($image_php) != 640 || imagesy($image_php) != 480) {
    $saveImageIntoDB = FALSE;
}

imagealphablending($image_php, true);
imagesavealpha($image_php, true);
$i = 0;

if ($stickerFlag === TRUE) {
    while ($stickerData[$i] != "") {
        $stickerSrc = $stickerData[$i];
        $stickerSrc = validateStickerSrc($stickerSrc);
        if ($stickerSrc === FALSE) {
            echo "StickerError";
            return;
        }
        $sticker = imagecreatefrompng($stickerSrc);
        $offsetWidth = $stickerData[$i + 1];
        $offsetHeight = $stickerData[$i + 2];
        if (!is_numeric($offsetWidth) || !is_numeric($offsetHeight)) {
            echo "StickerError";
            return;
        }
        $stickerWidth = imagesx($sticker); // Returns width of image as int.
        $stickerHeight = imagesy($sticker); // Returns height of image as int.
        imagecopy($image_php, $sticker, $offsetWidth, $offsetHeight, 0, 0, $stickerWidth, $stickerHeight);
        $i = $i + 3;
    }
}

ob_start();
imagejpeg($image_php);
$image_php = ob_get_clean();
$image = base64_encode($image_php);

// Check that the image is at most 0.5 MB by checking that there is no more bytes than that.
if ((substr($image, 512000, 1) === "" || !substr($image, 512000, 1)) && $saveImageIntoDB === TRUE) {
    $sql = "USE `joonasja_camagru`";
    $dbConn->exec($sql);
    $sql = "INSERT INTO images (`user_id`, `image_data`)
            VALUES (?, ?);";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$user_id, $image]);
}

echo "data:image/jpeg;base64," . $image;
