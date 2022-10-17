function adjustLikeStatus(likeId, likerId) {
    if (!likerId) {
        alert("You need to log in first to be able to like pictures!");
        return;
    }
    var likeIcon = document.getElementById(likeId);
    var imageId = likeId.replace("like", "");
    let likesInnerHtml = document.getElementById('likeAmount' + imageId).innerHTML
    let likes = 0;
    likes = parseInt(document.getElementById('likeAmount' + imageId).innerHTML);
    if (likeIcon.src.match("heartempty32.png")) {
        likeIcon.src = "./icons/heartfull32.png"
        likes = parseInt(document.getElementById('likeAmount' + imageId).innerHTML.replace("Likes: ", ""));
        likes++;
        document.getElementById('likeAmount' + imageId).innerHTML = "Likes: " + (likes);
        let xmlLikeNotification = new XMLHttpRequest();
        var urlLikeNotification = './like_notification.php';
        xmlLikeNotification.open('POST', urlLikeNotification, true);
        xmlLikeNotification.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlLikeNotification.send('likedImage=' + imageId);
    } else if (likeIcon.src.match("heartfull32.png")) {
        likeIcon.src = "./icons/heartempty32.png"
        likes = parseInt(likesInnerHtml.replace("Likes: ", ""));
        likes--;
        document.getElementById('likeAmount' + imageId).innerHTML = "Likes: " + (likes);
    }
    let xml = new XMLHttpRequest();
    var url = './likemanager.php';
    xml.open('POST', url, true);
    xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xml.send('likedImage=' + imageId);
}

function postComment(imageId) {
    function sendAsXML(form, imageId) {
        let formData = new FormData(form);
        let content = formData.get('comment');
        if (content == '') {
            alert("Your comment cannot be empty.");
            document.location.reload();
        }
        if (content.length > 160) {
            alert("Your comment was too long! Maximum comment size is 160 characters.");
            document.location.reload();
        }
        let xmlComment = new XMLHttpRequest();
        var urlComment = './post_comment.php';
        xmlComment.open('POST', urlComment, true);
        xmlComment.onload = function () {
            document.location.reload();
        }
        xmlComment.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlComment.send('image_id=' + imageId + '&content=' + content);
    }
    let form = document.getElementById('formElement' + imageId);
    form.addEventListener("submit", (event) => {
        // The below prevents the submit button from doing what it would normally do, which is submitting the form.
        event.preventDefault();
        sendAsXML(form, imageId)
    });
}

function toggleNotifications(destValue) {
    var notifDescrBody = "By having this set to ON, you will receive notifications in your email when someone likes your pictures\. Currently set to ";
    var notifIcon = document.getElementById('notifIcon');
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
            document.location.reload();
        }
        xml.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xml.send('imageId=' + image_id);
    }
}

