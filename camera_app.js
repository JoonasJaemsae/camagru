// The below is needed if we want to toggle the camera on with a button press.
// let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video");
let snap = document.querySelector("#snap");
let canvas = document.querySelector("#canvas");
let save = document.querySelector("#save");

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

snap.addEventListener('click', function () {
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
        console.log("PHP Response: ", this.response)
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('new_image='+image_data_url);
});

// Doing the whole thing in Javascript like an idiot:
// urlToFile(image_data_url); // this one goes inside capture or save or somewhere like that.

// let urlToFile = (url) => {

//     let arr = url.split(",");
//     let mime = arr[0].match(/:(.*?);/)[1]; //Takes the image/jpeg part of the string.
//     let data = arr[1];
//     console.log("mime:", mime);
//     console.log("data:", data);

//     let dataString = atob(data);

//     let dataArr = new Uint8Array(daya)
// }

// How to create a downloadable blob. Interesting and works but not what was asked:
// save.addEventListener('click', function () {
//     canvas.toBlob((blob) => {
//         const timestamp = Date.now().toString();
//         const a = document.createElement('a');
//         document.body.append(a);
//         a.download = `export-${timestamp}.png`;
//         a.href = URL.createObjectURL(blob);
//         a.click();
//         a.remove();
//     });
// });

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

// The below is needed if we want to toggle the camera on with a button press.
// camera_button.addEventListener('click', async function () {
//     let stream = await navigator.mediaDevices.getUserMedia(constraints);
//     video.srcObject = stream;
// });