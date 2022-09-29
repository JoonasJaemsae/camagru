// let xml = new XMLHttpRequest();
// var url = 'gallery_functions.php';

// var id = 170;   // 62, 186, 174
// xml.open('POST', url, true);
// xml.onload = function () {
//     // alert("Something may have happened somewhere!")
//     appendPhoto(this.response);
// }
// xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
// xml.send('imageId=' + id);

// var id = 171;
// xml.open('POST', url, true);
// xml.onload = function () {
//     // alert("Something may have happened somewhere!")
//     appendPhoto(this.response);
// }
// xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
// xml.send('imageId=' + id);

// var id = 174;
// xml.open('POST', url, true);
// xml.onload = function () {
//     // alert("Something may have happened somewhere!")
//     appendPhoto(this.response);
// }
// xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
// xml.send('imageId=' + id);

// function appendPhoto(image) {

//     let gallery = document.getElementById('galleryPhotos');
//     let add = document.createElement('img');

//     add.id = 'galleryPhoto';
//     add.src = image;
//     gallery.appendChild(add);
// }