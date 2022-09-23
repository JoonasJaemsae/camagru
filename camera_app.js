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
let selected_sticker = '';
const upload = document.querySelector("#upload");
var uploaded_image = "";
let draggable = false;

var rect = preview1.getBoundingClientRect();
var x_width = rect.right - rect.left;
var y_height = rect.bottom - rect.top;
let currentX = x_width / 2;
let currentY = y_height / 2;

var stickerArray = '';

const constraints = { audio: false, video: true }      // CHANGE THIS TO TRUE TO SEE VIDEO!!!

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
});

save.addEventListener('click', function () {
    let image_data_url = canvas.toDataURL('image/jpeg');
    let xml = new XMLHttpRequest();
    var url = './save_image.php';
    // console.log(image_data_url);
    xml.open('POST', url, true);
    xml.onload = function () {
        alert("Image saved successfully!");
        console.log("PHP Response: ", this.response);
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('new_image=' + image_data_url + '&stickers' + stickerArray);
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

// console.log("Selected Sticker at first:", selected_sticker);
// selected_sticker = document.getElementById(sticker.id);
// console.log("Selected Sticker:", selected_sticker);

function drawSticker(sticker, h_offset, v_offset, width, height, flag) {
    if (flag == "new") {
        if (selected_sticker != '') {
            lockedPreview1.getContext('2d').drawImage(selected_sticker, currentX - selected_sticker.width / 2, currentY - selected_sticker.height / 2);
            lockedPreview2.getContext('2d').drawImage(selected_sticker, currentX - selected_sticker.width / 2, currentY - selected_sticker.height / 2);
            stickerArray += selected_sticker.src+','+currentX - selected_sticker.width / 2+','+currentY - selected_sticker.height / 2;
            console.log(stickerArray[0]);
            console.log(stickerArray);
        }
        console.log("Selected Sticker at first:", selected_sticker);
        selected_sticker = document.getElementById(sticker.id);
        console.log("Selected Sticker:", selected_sticker);
        console.log("Selected Sticker src:", selected_sticker.src);
        
        rect = preview1.getBoundingClientRect();
        x_width = rect.right - rect.left;
        y_height = rect.bottom - rect.top;
        currentX = x_width / 2;
        currentY = y_height / 2;

        preview1.getContext('2d').drawImage(sticker, currentX - selected_sticker.width / 2, currentY - selected_sticker.height / 2);
        // preview1.getContext('2d').drawImage(sticker, 0, 0);
        // preview1.getContext('2d').drawImage(sticker, currentX, currentY);
    }
    if (flag == "move") {
        preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
        preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
        preview1.getContext('2d').drawImage(selected_sticker, h_offset - selected_sticker.width / 2, v_offset - selected_sticker.height / 2);
        preview2.getContext('2d').drawImage(selected_sticker, h_offset - selected_sticker.width / 2, v_offset - selected_sticker.height / 2);
    }
    if (flag == 'empty') {
        preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
        preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
        lockedPreview1.getContext('2d').clearRect(0, 0, lockedPreview1.width, lockedPreview1.height);
        lockedPreview2.getContext('2d').clearRect(0, 0, lockedPreview2.width, lockedPreview2.height);
        selected_sticker = '';
    }
}
// The below works if you remove the other onmousedown event below this one.
// preview1.onmousedown = (e) => {
//     console.log("The image was clicked.");
// }

preview1.onmousedown = (e) => {

    var rect = preview1.getBoundingClientRect();  // get element's abs. position
    var x = e.clientX - rect.left;              // get mouse x and adjust for el.
    var y = e.clientY - rect.top;               // get mouse y and adjust for el.

    var x_width = rect.right - rect.left;
    var y_height = rect.bottom - rect.top;

    console.log("selected_sticker.width", selected_sticker.width);
    console.log("e.layerX", e.layerX);
    console.log("e.layerY", e.layerY);
    console.log("currentX", currentX);
    console.log("currentY", currentY);
    console.log("x_width", x_width);
    console.log("y_height", y_height);
    console.log("e.clientX", e.clientX);
    console.log("e.clientY", e.clientY);
    console.log("x_width / 2 + selected_sticker.width / 2", x_width / 2 + selected_sticker.width / 2);
    console.log("x_width / 2 - selected_sticker.width / 2", x_width / 2 - selected_sticker.width / 2);
    console.log("y_height / 2 + selected_sticker.height / 2", y_height / 2 + selected_sticker.height / 2);
    console.log("y_height / 2 - selected_sticker.height / 2", y_height / 2 - selected_sticker.height / 2);

    if (e.layerX <= (currentX + selected_sticker.width / 2) &&
        e.layerX >= (currentX - selected_sticker.width / 2) &&
        e.layerY <= (currentY + selected_sticker.height / 2) &&
        e.layerY >= (currentY - selected_sticker.height / 2)) {
        draggable = true;
        console.log("The image was clicked.");
    } else {
        console.log("Didn't click the image.");
    }
}

preview1.onmousemove = (e) => {
    if (draggable == true) {
        currentX = e.layerX
        currentY = e.layerY
        drawSticker(selected_sticker, currentX, currentY, 0, 0, "move");
    }
}

preview1.onmouseup = (e) => {
    draggable = false;
    console.log("Mouse up!");
}

preview1.onmouseout = (e) => {
    draggable = false;
    console.log("Mouse out!");
}

startWebCam();