<?php

session_start();

include_once './config/new_conn.php';
require 'gallery_functions.php';

if ((!isset($_SESSION['loginSuccess']) || !isset($_SESSION['loginPersist']))
    || ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE)
) {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Upload your own pictures</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body id="gradient">
    <?php

    include_once 'navbar.php';

    ?>
    <h1 style="margin-top: 100px">Create your own pictures</h1>
    <div class="captureElements">
        <div class="videoContainer">
            <div class="videoScreens">
                <div class="halfContainer">
                    <div class="videoScreenArea">
                        <video id="video" class="video" autoplay></video>
                        <canvas id="stickerPreview1" width="640" height="480"></canvas>
                        <canvas id="lockedPreview1" width="640" height="480"></canvas>
                    </div>
                </div>
            </div>
            <div class="halfContainer">
                <div class="buttonArea">
                    <button id="snap" disabled>Let's Snap Time!</button>
                </div>
            </div>
        </div>
        <div class="canvasContainer">
            <div class="canvasScreens">
                <div class="halfContainer">
                    <div class="canvasScreenArea">
                        <canvas id="canvas"></canvas>
                        <canvas id="stickerPreview2" width="640" height="480"></canvas>
                        <canvas id="lockedPreview2" width="640" height="480"></canvas>
                        <canvas id="lockedPreview3" width="640" height="480"></canvas>
                    </div>
                </div>
            </div>
            <div class="halfContainer">
                <div class="saveButtonArea">
                    <button id="save" disabled>Save photo</button>
                    <input type="file" id="upload" accept="image/png, image/jpg"></button>
                </div>
            </div>
        </div>
        <div class="photoDisplayBar" id='photoDisplayBar'>
            <?php
            foreach ($usersImages as $key => $value) {
                $base64 = $value['image_data'];
                $image = 'data:image/jpeg;base64,' . $base64;
            ?>
                <img id="barPhoto" src="<?php echo $image; ?>"></img>
            <?php } ?>
        </div>
        <div class="stickerBarArea">
            <div class="stickerBar">
                <img sticker class="sticker" src="stickers/empty.png" onclick="drawSticker(this, 0, 0, 'empty')">
                <img class="sticker" id="crown.png" width="128" height="95" src="stickers/crown.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="42.png" width="128" height="78" , src="stickers/42.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="confetti.png" width="128" height="131" src="stickers/confetti.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="confettirev.png" width="128" height="131" src="stickers/confettirev.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="rainbow.png" width="640" height="480" src="stickers/rainbow.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="salmon.png" width="640" height="480" src="stickers/salmon.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="stars.png" width="640" height="480" src="stickers/stars.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="chicken.png" width="640" height="480" src="stickers/chicken.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="sakura.png" width="640" height="480" src="stickers/sakura.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="balloons3.png" width="128" height="146" src="stickers/balloons3.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="balloons5.png" width="128" height="114" src="stickers/balloons5.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="propelhat.png" width="640" height="480" src="stickers/propelhat.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="blackhat.png" width="128" height="105" src="stickers/blackhat.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="policehat.png" width="128" height="79" src="stickers/policehat.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="grayhat.png" width="128" height="117" src="stickers/grayhat.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="halloweenhat.png" width="137" height="117" src="stickers/halloweenhat.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="groucho.png" width="137" height="117" src="stickers/groucho.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="birdbrown.png" width="128" height="120" src="stickers/birdbrown.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="birdpurple.png" width="128" height="93" src="stickers/birdpurple.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="birdwhite.png" width="128" height="120" src="stickers/birdwhite.png" onclick="drawSticker(this, 0, 0, 'new')">
                <img class="sticker" id="birdyellow.png" width="128" height="126" src="stickers/birdyellow.png" onclick="drawSticker(this, 0, 0, 'new')">
            </div>
        </div>
    </div>
</body>

<script src="camera_app.js"></script>

</html>