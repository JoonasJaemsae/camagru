// The below is needed if we want to toggle the camera on with a button press.
// let camera_button = document.querySelector("#start-camera");
let video = document.querySelector("#video");
let snap = document.querySelector("#snap");
let canvas = document.querySelector("#canvas");
let save = document.querySelector("#save");
const image_input = document.querySelector("#image_input");
var uploaded_image = "";

const constraints = { audio: false, video: true }      // CHANGE THIS TO TRUE TO SEE VIDEO!!!

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
        console.log("PHP Response: ", this.response);
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('new_image='+image_data_url);
});

image_input.addEventListener('change', function () {
    console.log(image_input.value);
    const reader = new FileReader();
    reader.addEventListener("load", () => {
        uploaded_image = reader.result;
        document.querySelector("#canvas").style.backgroundImage = `url(${uploaded_image})`;
    });
    reader.readAsDataURL(this.files[0]);

});

startWebCam();