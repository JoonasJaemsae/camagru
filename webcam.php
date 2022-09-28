<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

session_start();

if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

    header("Location: index.php");
    exit();
}

?>

<html>

<head>
    <title>Camagru</title>

    <style>
        <?php include "style.css"; ?>
    </style>
</head>

</html>

<?php

if (isset($_SESSION['loginPersist'])) {
?>
    <html>

    <body id="gradient">
        <?php

        include_once 'navbar.php';

        ?>
        <h1 style="margin-top: 100px"> Take a picture of yourself</h1>
        <div class="captureElements">
            <div class="captureElementsLeftSide">
                <div class="editables">
                    <div class="videoArea">
                        <div class="video">
                            <video id="video" autoplay></video>
                            <canvas id="stickerPreview1" width="640" height="480"></canvas>
                            <canvas id="lockedPreview1" width="640" height="480"></canvas>
                        </div>
                        <div class="snapArea">
                            <button id="snap" disabled>Let's Snap Time!</button>
                        </div>
                    </div>
                    <div class="canvasArea">
                        <div class="canvas">
                            <canvas id="canvas"></canvas>
                            <canvas id="stickerPreview2" width="640" height="480"></canvas>
                            <canvas id="lockedPreview2" width="640" height="480"></canvas>
                        </div>
                        <div class="buttonArea">
                            <button id="save" disabled>Save photo</button>
                            <input type="file" id="upload" accept="image/png, image/jpg"></button>
                        </div>
                    </div>
                    <div class="stickerBarArea">
                        <div class="stickerBar">
                            <img sticker class="sticker" src="stickers/empty.png" onclick="drawSticker(this, 0, 0, 128, 96, 'empty')">
                            <img class="sticker" id="crown.png" width="128" height="95" src="stickers/crown.png" onclick="drawSticker(this, 30, 30, 128, 95, 'new')">
                            <img class="sticker" id="42.png" width="128" height="78" , src="stickers/42.png" onclick="drawSticker(this, 0, 0, 128, 78, 'new')">
                            <img class="sticker" id="confetti.png" width="128" height="131" src="stickers/confetti.png" onclick="drawSticker(this, 30, 30, 128, 131, 'new')">
                            <img class="sticker" id="confettirev.png" width="128" height="131" src="stickers/confettirev.png" onclick="drawSticker(this, 0, 0, 128, 105, 'new')">
                            <img class="sticker" id="kanagawawave.png" width="640" height="480" src="stickers/kanagawawave.png" onclick="drawSticker(this, 0, 0, 640, 480, 'new')">
                            <img class="sticker" id="balloons3.png" width="128" height="146" src="stickers/balloons3.png" onclick="drawSticker(this, 30, 30, 128, 146, 'new')">
                            <img class="sticker" id="balloons5.png" width="128" height="114" src="stickers/balloons5.png" onclick="drawSticker(this, 30, 30, 128, 114, 'new')">
                            <img class="sticker" id="blackhat.png" width="128" height="105" src="stickers/blackhat.png" onclick="drawSticker(this, 0, 0, 128, 105, 'new')">
                            <img class="sticker" id="policehat.png" width="128" height="79" src="stickers/policehat.png" onclick="drawSticker(this, 0, 0, 128, 105, 'new')">
                            <img class="sticker" id="grayhat.png" width="128" height="117" src="stickers/grayhat.png" onclick="drawSticker(this, 30, 30, 128, 117, 'new')">
                            <img class="sticker" id="halloweenhat.png" width="137" height="117" src="stickers/halloweenhat.png" onclick="drawSticker(this, 30, 30, 128, 117, 'new')">
                            <img class="sticker" id="birdbrown.png" width="128" height="120" src="stickers/birdbrown.png" onclick="drawSticker(this, 0, 0, 128, 120, 'new')">
                            <img class="sticker" id="birdpurple.png" width="128" height="93" src="stickers/birdpurple.png" onclick="drawSticker(this, 0, 0, 128, 93, 'new')">
                            <img class="sticker" id="birdwhite.png" width="128" height="120" src="stickers/birdwhite.png" onclick="drawSticker(this, 0, 0, 128, 123, 'new')">
                            <img class="sticker" id="birdyellow.png" width="128" height="126" src="stickers/birdyellow.png" onclick="drawSticker(this, 0, 0, 128, 126, 'new')">
                        </div>
                    </div>
                </div>
            </div>
            <div class="captureElementsRightSide">
                <div class="photoDisplayBar" id='photoDisplayBar'>

                </div>
            </div>
        </div>


        <!-- The below is needed if we want to toggle the camera on with a button press. -->
        <!-- <button id="start-camera">Start Camera</button> -->
    </body>

    <script src="camera_app.js"></script>

    </html>
<?php

}

?>