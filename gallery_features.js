// likeButton.addEventListener('click', function(likeButton) {
//     likeButton.target.classList.toggle('heartFilledIcon');
// });



function adjustLikeStatus(likeId) {
    var likeIcon = document.getElementById(likeId);
    console.log(likeIcon);
    var imageId = likeId.replace("like", "");
    console.log(imageId);
    console.log(likeIcon.src);
    let likesInnerHtml = document.getElementById('likeAmount' + imageId).innerHTML
    console.log("likesInnerHTML: ", likesInnerHtml);
    let likes = 0;
    likes = parseInt(document.getElementById('likeAmount' + imageId).innerHTML);
    if (likeIcon.src.match("heartempty32.png")) {
        likeIcon.src = "./icons/heartfull32.png"
        console.log("Yees");
        likes = parseInt(document.getElementById('likeAmount' + imageId).innerHTML.replace("Likes: ", ""));
        likes++;
        document.getElementById('likeAmount' + imageId).innerHTML = "Likes: " + (likes);
    } else if (likeIcon.src.match("heartfull32.png")) {
        likeIcon.src = "./icons/heartempty32.png"
        likes = parseInt(likesInnerHtml.replace("Likes: ", ""));
        likes--;
        document.getElementById('likeAmount' + imageId).innerHTML = "Likes: " + (likes);
        console.log("Nahgh");
    }
    console.log("likes: ", likes);
    let xml = new XMLHttpRequest();
    var url = './likemanager.php';
    xml.open('POST', url, true);
    xml.onload = function () {
        console.log("PHP Response: ", this.response);
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('likedImage=' + imageId);
}

