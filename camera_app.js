// The below is needed if we want to toggle the camera on with a button press.
// let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video");
let snap = document.querySelector("#snap");
let canvas = document.querySelector("#canvas");
let save = document.querySelector("#save");
let preview1 = document.querySelector('#stickerPreview1');
let preview2 = document.querySelector('#stickerPreview2');
let lockedPreview1 = document.querySelector('#lockedPreview1');
let lockedPreview2 = document.querySelector('#lockedPreview2');
let lockedPreview3 = document.querySelector('#lockedPreview3');
let selected = '';
const upload = document.querySelector("#upload");
var uploaded_image = "";
let draggable = false;

var rect = preview1.getBoundingClientRect();
var x_width = rect.right - rect.left;
var y_height = rect.bottom - rect.top;
let currentX = 0;
let currentY = 0;
var positionX = 0;
var positionY = 0;
var finalPositionX = 0;
var finalPositionY = 0;
var stickerArray = '';

const constraints = { audio: false, video: true }

async function startWebCam() {
    try {
        let stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
        window.stream = stream;
    } catch (error) {
        console.log(error.toString());
    }
}

snap.addEventListener('click', function () {
    canvas.width = 640;
    canvas.height = 480;
    // Empty the upload canvas.
    lockedPreview3.getContext('2d').clearRect(0, 0, lockedPreview3.width, lockedPreview3.height);
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height); // 0 0 is top left position we want the video to be.
    // The last two are the canvas width and height which we'll want to match perfectly.
    // When our div is clicked, we want to draw onto the canvas the image that is displayed on our video element
    let image_data_url = canvas.toDataURL('image/jpeg');
    // let image_base64 = document.querySelector("#canvas").toDataURL().replace(/^data:image\/png;base64,/, "");

    console.log(image_data_url); // You can find the image data in Inspect -> Console
    save.disabled = false;
});

save.addEventListener('click', function () {

    if (selected != '') {
        stickerArray += selected.src + ',' + finalPositionX + ',' + finalPositionY + ',';
        lockedPreview1.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
        lockedPreview2.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
    }
    let image_data_url = canvas.toDataURL('image/jpeg');
    let xml = new XMLHttpRequest();
    var url = './save_image.php';
    xml.open('POST', url, true);
    xml.onload = function () {
        alert("Image saved successfully!");
        appendPhoto(this.response);
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('new_image=' + image_data_url + '&stickerData=' + stickerArray);
});

function emptyCanvas() {
    preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
    preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
    lockedPreview1.getContext('2d').clearRect(0, 0, lockedPreview1.width, lockedPreview1.height);
    lockedPreview2.getContext('2d').clearRect(0, 0, lockedPreview2.width, lockedPreview2.height);
    lockedPreview3.getContext('2d').clearRect(0, 0, lockedPreview3.width, lockedPreview3.height);
    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height); // Reflect on this one still.
    selected = '';
    stickerArray = '';
}

document.getElementById('upload').onclick = function (e) {
    snap.disabled = true;
    save.disabled = true;
    emptyCanvas();
    var img = new Image();
    img.onload = draw;
    img.onerror = failed;
    try {
        img.src = URL.createObjectURL(this.files[0]);
    } catch (error) {

    }
}
document.getElementById('upload').onchange,
    document.getElementById('upload').oninput = function (e) {
        var img = new Image();
        img.onload = draw;
        img.onerror = failed;
        try {
            emptyCanvas();
            img.src = URL.createObjectURL(this.files[0]);
        } catch (error) {
            if (img.src == '') {
                save.disabled = true;
            }
        }
    };
function draw() {
    save.disabled = false;
    var canvas = document.getElementById('canvas');

    x_width = rect.right - rect.left;
    y_height = rect.bottom - rect.top;

    scaleX = x_width / 640;
    scaleY = y_height / 480;

    canvas.width = 640;
    canvas.height = 480;
    if (this.width >= 640 || this.height >= 480) {
        final_width = this.width * scaleX;
        final_height = this.height * scaleY;
    } else {
        final_width = this.width * scaleX;
        final_height = this.height * scaleY;
    }
    positionX = x_width / 2 - final_width / 2;
    positionY = y_height / 2 - final_height / 2;
    finalPositionX = positionX * (640 / x_width);
    finalPositionY = positionY * (480 / y_height);
    lockedPreview3.getContext('2d').drawImage(this, finalPositionX, finalPositionY);
    canvas.getContext('2d').drawImage(this, finalPositionX, finalPositionY);
}
function failed() {
    save.disabled = true;
}

function drawSticker(sticker, hori_offset, vert_offset, flag) {

    x_width = rect.right - rect.left;
    y_height = rect.bottom - rect.top;
    scaleX = x_width / 640;
    scaleY = y_height / 480;
    if (flag == "new") {
        snap.disabled = false;
        if (selected != '') {
            positionX = currentX - final_width / 2;
            positionY = currentY - final_height / 2;
            finalPositionX = positionX * (640 / x_width)
            finalPositionY = positionY * (480 / y_height)
            lockedPreview1.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
            lockedPreview2.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
            stickerArray += selected.src + ',' + finalPositionX + ',' + finalPositionY + ',';
        }
        selected = document.getElementById(sticker.id);
        final_width = selected.width * scaleX;
        final_height = selected.height * scaleY;
        currentX = final_width / 2;
        currentY = final_height / 2;
        rect = preview1.getBoundingClientRect();
        positionX = 0;
        positionY = 0;
        finalPositionX = positionX * (640 / x_width)
        finalPositionY = positionY * (480 / y_height)
        preview1.getContext('2d').drawImage(sticker, finalPositionX, finalPositionY);
        preview2.getContext('2d').drawImage(sticker, finalPositionX, finalPositionY);

    }
    if (flag == "move") {
        final_width = selected.width * scaleX;
        final_height = selected.height * scaleY;
        preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
        preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
        positionX = hori_offset - final_width / 2;
        positionY = vert_offset - final_height / 2;
        finalPositionX = positionX * (640 / x_width)
        finalPositionY = positionY * (480 / y_height)
        preview1.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
        preview2.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
    }
    if (flag == 'empty') {
        emptyCanvas();
        save.disabled = true;
        snap.disabled = true;
    }
}

preview1.onmousedown = (e) => {

    rect = preview1.getBoundingClientRect();
    var x_width = rect.right - rect.left;
    var y_height = rect.bottom - rect.top;
    final_width = selected.width * scaleX;
    final_height = selected.height * scaleY;
    if (e.layerX <= (currentX + final_width / 2) &&
        e.layerX >= (currentX - final_width / 2) &&
        e.layerY <= (currentY + final_height / 2) &&
        e.layerY >= (currentY - final_height / 2)) {
        draggable = true;
    } else {
        currentX = e.layerX;
        currentY = e.layerY;
        drawSticker(selected, currentX, currentY, "move");
    }
}

preview1.onmousemove = (e) => {
    if (draggable == true) {
        currentX = e.layerX;
        currentY = e.layerY;
        drawSticker(selected, currentX, currentY, "move");
    }
}

preview1.onmouseup = (e) => {
    draggable = false;
}

preview1.onmouseout = (e) => {
    draggable = false;
}

window.addEventListener('resize', function (e) {
    if (selected == '') {
        return;
    } else {
        adjustParamsOnResize(selected, preview1, preview2);
    }
});

function adjustParamsOnResize(selected, preview1, preview2) {
    rect = preview1.getBoundingClientRect();
    x_width = rect.right - rect.left;
    y_height = rect.bottom - rect.top;
    scaleX = x_width / 640;
    scaleY = y_height / 480;
    final_width = selected.width * scaleX;
    final_height = selected.height * scaleY;
    currentX = 0 + final_width / 2;
    currentY = 0 + final_height / 2;
    preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
    preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
    drawSticker(selected, currentX, currentY, "move");
}

function appendPhoto(savedImage) {

    let display = document.getElementById('photoDisplayBar');
    let add = document.createElement('img');

    add.id = 'barPhoto';
    add.src = savedImage;
    display.appendChild(add);
}

startWebCam();