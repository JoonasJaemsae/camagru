<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_POST['likedImage'])) {
    header('Location: index.php');
    return;
}

$agent = $_SESSION['logged_in_user_id'];
$likedImageId = $_POST['likedImage'];

$sql = "SELECT images.user_id as userid, email, username, notifications
    FROM images
    INNER JOIN users
    ON images.user_id = users.id
    WHERE image_id=?;
    ";
$stmt = $dbConn->prepare($sql);
$stmt->execute([$likedImageId]);
$pictureOwner = $stmt->fetch(PDO::FETCH_ASSOC);

if ($pictureOwner['notifications'] == 0) {
    echo "The recipient's notifications were set to zero. No notification was sent.";
    return;
}

$sql2 = "SELECT username
    FROM users
    WHERE id=?;
    ";
$stmt = $dbConn->prepare($sql2);
$stmt->execute([$agent]);
$liker = $stmt->fetch(PDO::FETCH_ASSOC);

// Don't send an email, if the user liked their own picture. Some sad people do that.
if ($agent == $pictureOwner['userid']) {
    echo "Liker was the same as the picture owner. No email was sent.";
    return;
}

$sql = "USE `joonasja_camagru`";
$dbConn->exec($sql);

$to = $pictureOwner['email'];
$subject = 'You have received a new like!';
$from = 'no-reply@camagru.fi';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Create email headers
$headers .= 'From: ' . $from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

// Compose a simple HTML email message
$message = '<html><body>';
$message .= '<h1 style="color:#f40;">You have received a new like!</h1>';
$message .= '<p style="color:#080;font-size:18px;">' . $liker['username'] . ' has liked your picture!</p>';
$message .= '</body></html>';

// Sending email
if (mail($to, $subject, $message, $headers)) {
    echo 'Your mail has been sent successfully.';
} else {
    echo 'Unable to send email. Please try again.';
}
