// likeButton.addEventListener('click', function(likeButton) {
//     likeButton.target.classList.toggle('heartFilledIcon');
// });

function adjustLikeStatus(likeId, likerId) {
    console.log("User ID that got passed onto the function: ", likerId);
    if (!likerId) {
        alert("You need to log in first to be able to like pictures!");
        return;
    }
    var likeIcon = document.getElementById(likeId);
    console.log(likeIcon);
    var imageId = likeId.replace("like", "");
    console.log("imageId ", imageId);
    console.log("likeIcon.src ", likeIcon.src);
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
        console.log("likes: ", likes);

        let xmlLikeNotification = new XMLHttpRequest();
        var urlLikeNotification = './like_notification.php';
        xmlLikeNotification.open('POST', urlLikeNotification, true);
        xmlLikeNotification.onload = function () {
            console.log("PHP Response: ", this.response);
        }
        xmlLikeNotification.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlLikeNotification.send('likedImage=' + imageId);
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

function toggleNotifications(destValue) {
    var notifDescrBody = "By having this set to ON, you will receive notifications in your email when someone likes your pictures\. Currently set to ";
    var notifIcon = document.getElementById('notifIcon');
    console.log("notifIcon", notifIcon);
    console.log("notifIcon.src", notifIcon.src);
    if (notifIcon.src.match("yes32.png")) {
        document.getElementById('notifDescr').innerHTML = notifDescrBody + "OFF.";
        notifIcon.src = "./icons/no32.png"
    } else if (notifIcon.src.match("no32.png")) {
        document.getElementById('notifDescr').innerHTML = notifDescrBody + "ON.";
        notifIcon.src = "./icons/yes32.png"
    }
    let xml = new XMLHttpRequest();
    var url = './toggle_notifications.php';
    xml.open('POST', url, true);
    xml.onload = function () {
        console.log("PHP Response: ", this.response);
        document.location.reload();
    }
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('destValue=' + destValue);
}

function confirmDelete(image_id) {
    if (confirm("Confirm you want to proceed with deleting the picture.") == true) {
        let xml = new XMLHttpRequest();
        var url = './delete_image.php';
        xml.open('POST', url, true);
        xml.onload = function () {
            console.log("PHP Response: ", this.response);
            document.location.reload();
        }
        xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xml.send('imageId=' + image_id);
    }
}

