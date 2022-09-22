// The below is needed if we want to toggle the camera on with a button press.
// let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video");
let snap = document.querySelector("#snap");
let canvas = document.querySelector("#canvas");
let save = document.querySelector("#save");
let preview1 = document.querySelector('#stickerPreview1');
let preview2 = document.querySelector('#stickerPreview2');
var selected_sticker = "empty";
const upload = document.querySelector("#upload");
var uploaded_image = "";
let draggable = false;

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
    xml.send('new_image=' + image_data_url);
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

function drawSticker(sticker, h_offset, v_offset, width, height, flag) {
    if (flag == 'empty') {
        preview1.getContext('2d').clearRect(0, 0, preview1.width, preview1.height);
        preview2.getContext('2d').clearRect(0, 0, preview2.width, preview2.height);
    } else {
        preview1.getContext('2d').drawImage(sticker, h_offset, v_offset, width, height);
        preview2.getContext('2d').drawImage(sticker, h_offset, v_offset, width, height);
        var selected_sticker = "other";
    }

}

preview1.mousedown = (e) => {
    if (e.layerX <= (currentX + stickerImg.width/2) &&
    e.layerX >= (currentX + stickerImg.width/2) &&
    e.layerX <= (currentY + stickerImg.height/2) &&
    e.layerX >= (currentY + stickerImg.height/2)) {
        draggable = TRUE;
    }
}

preview1.onmousemove = (e) => {
    if (draggable == TRUE) {
        currentX = e.layerX
        currentY = e.layerY
    }
}

preview1.onmouseup = (e) => {
    draggable = FALSE;
}

preview1.onmouseout = (e) => {
    draggable = FALSE;
}

startWebCam();