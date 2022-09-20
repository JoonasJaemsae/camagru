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
            <div class="editables">
                <div class="videoAndCanvas">
                    <div class="video">
                        <video id="video" width="640" height="480" autoplay></video>
                    </div>
                    <div class="canvas">
                        <canvas id="canvas" width="640" height="480"></canvas>
                    </div>
                </div>
                <div class="photoButtons">
                    <button id="snap">Let's Snap Time!</button>
                    <button id="save">Save photo</button>
                    <input type="file" id="image_input" accept="image/png, image/jpg">Upload a photo</button>
                </div>
                <div class="stickerBar">
                    <div class="stickerSlot">

                    </div>
                    <div class="stickerSlot">
                        
                    </div>
                </div>
            </div>
            <div class="photoDisplayBar">
            
            </div>

            <!-- The below is needed if we want to toggle the camera on with a button press. -->
            <!-- <button id="start-camera">Start Camera</button> -->



        </div>

        <div style="text-align: center;">
            <?php
            // if ($_SESSION['loginPersist'] == TRUE) {
            //     echo '$_SESSION["loginPersist"] is TRUE' . '<br>';
            // } else if ($_SESSION['loginPersist'] == FALSE) {
            //     echo '$_SESSION["loginPersist"] is FALSE' . '<br>';
            // }

            // if ($_SESSION['loginSuccess'] == TRUE) {
            //     echo '$_SESSION["loginSuccess"] is TRUE' . '<br>';
            // } else if ($_SESSION['loginSuccess'] == FALSE) {
            //     echo '$_SESSION["loginSuccess"] is FALSE' . '<br>';
            // }
            ?>
        </div>
    </body>

    <script src="camera_app.js"></script>

    </html>
<?php

}

?>