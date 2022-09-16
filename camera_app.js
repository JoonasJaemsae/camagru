// The below is needed if we want to toggle the camera on with a button press.
// let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video");
let snap = document.querySelector("#snap");
let canvas = document.querySelector("#canvas");

const constraints = { audio: false, video: true }

async function startWebCam() {
    try {
        let stream = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject = stream;
        window.stream = stream; // This will allow to interact with the stream in our browser, I hear. Possibly unnecessary.
    } catch (e) {
        console.log(e.toString());
    }
}

// The below is needed if we want to toggle the camera on with a button press.
// camera_button.addEventListener('click', async function () {
//     let stream = await navigator.mediaDevices.getUserMedia(constraints);
//     video.srcObject = stream;
// });

snap.addEventListener('click', function () {
    canvas.getContext('2d').drawImage(video, 0, 0, 640, 480); // 0 0 is top left position we want the video to be.
    // The last two are the canvas width and height which we'll want to match perfectly.
    // When our div is clicked, we want to draw onto the canvas the image that is displayed on our video element
    let image_data_url = canvas.toDataURL('image/jpeg');

    console.log(image_data_url); // You can find the image data in Inspect -> Console
});

startWebCam();


// <?php

// // data url string that was uploaded
// $data_url = 'data:image/jpeg;base64,/9j/4AAQSkZJRgKL93W5//Z';

// list($type, $data) = explode(';', $data_url);
// list(, $data)      = explode(',', $data);
// $data = base64_decode($data);
// Erotetaankohan tassa ensin tama osa tuosta nimesta: base64,/9j/4AAQSkZJRgKL93W5//Z
// Ja sitten viela tama osa: /9j/4AAQSkZJRgKL93W5//Z

// file_put_contents('test.jpg', $data);

// ?>