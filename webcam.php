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
                            <canvas id="stickerPreview" width="640" height="480"></canvas>
                        </div>
                        <div class="snapArea">
                            <button id="snap">Let's Snap Time!</button>
                        </div>
                    </div>
                    <div class="canvasArea">
                        <div class="canvas">
                            <canvas id="canvas"></canvas>
                        </div>
                        <div class="buttonArea">
                            <button id="save">Save photo</button>
                            <input type="file" id="upload" accept="image/png, image/jpg"></button>
                        </div>
                    </div>
                    <div class="stickerBarArea">
                        <div class="stickerBar">
                            <img class="sticker" src="stickers/crown.png" onclick="drawSticker(this, 30, 30, 128, 95)">
                            <img class="sticker" src="stickers/42.png" onclick="drawSticker(this, 60, 60, 128, 78)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="captureElementsRightSide">
                <div class="photoDisplayBar">

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