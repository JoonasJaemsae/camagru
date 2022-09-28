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
let selected = '';
const upload = document.querySelector("#upload");
var uploaded_image = "";
let draggable = false;

var rect = preview1.getBoundingClientRect();
var x_width = rect.right - rect.left;
var y_height = rect.bottom - rect.top;
// let currentX = x_width / 2;
// let currentY = y_height / 2;
let currentX = 0;
let currentY = 0;
var positionX = 0;
var positionY = 0;
// var hori_offset = 0;
// var vert_offset = 0;


var stickerArray = '';

const constraints = { audio: false, video: true }      // CHANGE video TO TRUE TO SEE VIDEO!!!

async function startWebCam() {
    try {
        let stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
        window.stream = stream; // This will allow to interact with the stream in our browser, I hear. Possibly unnecessary.
    } catch (error) {
        console.log(error.toString());
    }
}

snap.addEventListener('click', function () {
    canvas.width = 640;
    canvas.height = 480;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height); // 0 0 is top left position we want the video to be.
    // The last two are the canvas width and height which we'll want to match perfectly.
    // When our div is clicked, we want to draw onto the canvas the image that is displayed on our video element
    let image_data_url = canvas.toDataURL('image/jpeg');
    // let image_base64 = document.querySelector("#canvas").toDataURL().replace(/^data:image\/png;base64,/, "");

    console.log(image_data_url); // You can find the image data in Inspect -> Console
    save.disabled = false;
});

save.addEventListener('click', function () {

    stickerArray += selected.src + ',' + finalPositionX + ',' + finalPositionY + ',';
    lockedPreview1.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
    lockedPreview2.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);

    let image_data_url = canvas.toDataURL('image/jpeg');
    let xml = new XMLHttpRequest();
    var url = './test_save.php';
    // console.log(image_data_url);
    xml.open('POST', url, true);
    xml.onload = function () {
        alert("Image saved successfully!");
        // console.log("PHP Response: ", this.response);
        appendPhoto(this.response);
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('new_image=' + image_data_url + '&stickerData=' + stickerArray);
});

// upload.addEventListener('change', function () {
//     console.log(upload.value);
//     const reader = new FileReader();
//     reader.addEventListener("load", () => {
//         uploaded_image = reader.result;
//         document.querySelector("#canvas").style.backgroundImage = `url(${uploaded_image})`;
//         uploaded_image = '';
//     });
//     canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
//     reader.readAsDataURL(this.files[0]);
//     uploaded_image = '';
// });

document.getElementById('upload').onchange = function (e) {
    var img = new Image();
    img.onload = draw;
    img.onerror = failed;
    try {
        img.src = URL.createObjectURL(this.files[0]);
    } catch (error) {
        console.log(error.message);
    }
};
function draw() {
    var canvas = document.getElementById('canvas');
    if (this.width <= 640 && this.height <= 480) {
        canvas.width = this.width;
        canvas.height = this.height;
    } else {
        canvas.width = 640;
        canvas.height = 480;
    }
    var ctx = canvas.getContext('2d');
    ctx.drawImage(this, 0, 0);
}
function failed() {
    console.error("The provided file couldn't be loaded as an Image media"); // Think about removing this.
}

function drawSticker(sticker, hori_offset, vert_offset, width, height, flag) {

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
            // You're always looking for the one below.
            //
            stickerArray += selected.src + ',' + finalPositionX + ',' + finalPositionY + ',';
            //
            // ^ This way.
            console.log("selected.src on ", selected.src);
            console.log("selected.width on ", selected.width);
            console.log("selected.height on ", selected.height);
            console.log("Mitas tasta tulostuu: ", stickerArray[0]);
            console.log("Mitas tasta tulostuu: ", stickerArray[1]);
            console.log("Entas tasta: ", stickerArray);
        }

        console.log("x_width:", x_width);
        console.log("y_height:", y_height);

        console.log("scaleX:", scaleX);
        console.log("scaleY:", scaleY);

        console.log("Selected Sticker at first:", selected);
        selected = document.getElementById(sticker.id);
        console.log("Selected Sticker:", selected);
        console.log("Selected Sticker src:", selected.src);

        console.log("Selected Sticker width:", selected.width);
        console.log("Selected Sticker height:", selected.height);

        final_width = selected.width * scaleX;
        final_height = selected.height * scaleY;

        currentX = final_width / 2;
        currentY = final_height / 2;

        rect = preview1.getBoundingClientRect();

        positionX = 0;
        positionY = 0;
        finalPositionX = positionX * (640 / x_width)
        finalPositionY = positionY * (480 / y_height)

        // preview1.getContext('2d').drawImage(sticker, currentX - (selected.width * scaleX / 2), currentY - (selected.height * scaleY / 2));
        // preview1.getContext('2d').drawImage(sticker, currentX - (selected.width / 2), currentY - (selected.height / 2));
        preview1.getContext('2d').drawImage(sticker, finalPositionX, finalPositionY);
        preview2.getContext('2d').drawImage(sticker, finalPositionX, finalPositionY);

    }
    if (flag == "move") {
        final_width = selected.width * scaleX;
        final_height = selected.height * scaleY;
        preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
        preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
        console.log("hori_offset:", hori_offset);
        console.log("vert_offset:", vert_offset);
        console.log("final_width / 2:", final_width / 2);
        console.log("final_height / 2:", final_height / 2);
        positionX = hori_offset - final_width / 2;
        positionY = vert_offset - final_height / 2;
        console.log("positionX:", positionX);
        console.log("positionY:", positionY);
        finalPositionX = positionX * (640 / x_width)
        finalPositionY = positionY * (480 / y_height)
        preview1.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
        preview2.getContext('2d').drawImage(selected, finalPositionX, finalPositionY);
    }
    if (flag == 'empty') {
        preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
        preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
        lockedPreview1.getContext('2d').clearRect(0, 0, lockedPreview1.width, lockedPreview1.height);
        lockedPreview2.getContext('2d').clearRect(0, 0, lockedPreview2.width, lockedPreview2.height);
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height); // Reflect on this one still.
        selected = '';
        stickerArray = '';
        save.disabled = true;
        snap.disabled = true;
    }
}

preview1.onmousedown = (e) => {

    rect = preview1.getBoundingClientRect();  // get element's abs. position

    var x_width = rect.right - rect.left;
    var y_height = rect.bottom - rect.top;

    final_width = selected.width * scaleX;
    final_height = selected.height * scaleY;

    console.log("selected.width", selected.width);
    console.log("selected.height", selected.height);
    console.log("final_width", final_width);
    console.log("final_height", final_height);
    console.log("e.layerX", e.layerX);
    console.log("e.layerY", e.layerY);
    console.log("currentX", currentX);
    console.log("currentY", currentY);
    console.log("x_width", x_width);
    console.log("y_height", y_height);
    console.log("e.clientX", e.clientX);
    console.log("e.clientY", e.clientY);
    console.log("currentX + final_width / 2", currentX + final_width / 2);
    console.log("currentX + final_width / 2", currentX - final_width / 2);
    console.log("currentY + final_height / 2", currentY + final_height / 2);
    console.log("currentY - final_height / 2", currentY - final_height / 2);
    if (e.layerX <= (currentX + final_width / 2) &&
        e.layerX >= (currentX - final_width / 2) &&
        e.layerY <= (currentY + final_height / 2) &&
        e.layerY >= (currentY - final_height / 2)) {
        draggable = true;
        console.log("The image was clicked.");
    } else {
        console.log("Didn't click the image.");
    }
}

preview1.onmousemove = (e) => {
    if (draggable == true) {
        currentX = e.layerX;
        currentY = e.layerY;
        drawSticker(selected, currentX, currentY, 0, 0, "move");
    }
}

preview1.onmouseup = (e) => {
    draggable = false;
    // console.log("Mouse up!");
}

preview1.onmouseout = (e) => {
    draggable = false;
    // console.log("Mouse out!");
}

// adjustParamsOnResize();
// window.addEventListener('resize', adjustParamsOnResize());
window.addEventListener('resize', function(e) {
    if (selected == '') {
        return ;
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
    drawSticker(selected, currentX, currentY, 0, 0, "move");
    drawSticker(selected, currentX, currentY, 0, 0, "move");
}

function appendPhoto(savedImage) {

    let display = document.getElementById('photoDisplayBar');
    let add = document.createElement('img');

    add.id = 'barPhoto';
    add.src = savedImage;
    display.appendChild(add);
}

startWebCam();

// The below works if you remove the other onmousedown event below this one.
// preview1.onmousedown = (e) => {
//     console.log("The image was clicked.");
// }