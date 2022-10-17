<?php

session_start();

require_once './config/new_conn.php';

if (!isset($_POST['image_id'])) {
    header('Location: index.php');
    return;
}


function sendCommentNotification($agent, $image_id, $dbConn)
{
    $sql = "SELECT images.user_id as userid, email, username, notifications
    FROM images
    INNER JOIN users
    ON images.user_id = users.id
    WHERE image_id=?;
    ";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$image_id]);
    $pictureOwner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pictureOwner['notifications'] == 0) {
        echo "The recipient's notifications were set to zero. No notification sent. " . '$pictureOwner["notifications"]: ' . $pictureOwner['notifications'];
        return;
    }

    $sql2 = "SELECT username
    FROM users
    WHERE id=?;
    ";
    $stmt = $dbConn->prepare($sql2);
    $stmt->execute([$agent]);
    $commenter = $stmt->fetch(PDO::FETCH_ASSOC);

    // Don't send an email, if the user left a comment on their own picture.
    if ($agent == $pictureOwner['userid']) {
        echo "Commenter was the same as the picture owner. " . '$pictureOwner["userid"]: ' . $pictureOwner['userid'];
        return;
    }

    $sql = "USE `joonasja_camagru`";
    $dbConn->exec($sql);

    $to = $pictureOwner['email'];
    $subject = 'You have received a new comment!';
    $from = 'no-reply@camagru.fi';

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html;    charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Compose a simple HTML email message
    $message = '<html><body>';
    $message .= '<h1 style="color:#f40;">You have received a comment!</h1>';
    $message .= '<p style="color:#080;font-size:18px;">' . $commenter['username'] . ' has left a comment on your picture!</p>';
    $message .= '</body></html>';

    // Sending email
    if (mail($to, $subject, $message, $headers)) {
        echo 'Your mail has been sent successfully.';
    } else {
        echo 'Unable to send email. Please try again.';
    }
}

if (isset($_POST['image_id']) && isset($_POST['content'])) {
    if (isset($_SESSION['logged_in_user_id']) && $_SESSION['logged_in_user_id'] !== '') {
        $agent = $_SESSION['logged_in_user_id'];
        $image_id = $_POST['image_id'];
        $content = $_POST['content'];
        if (strlen($content) > 160 || strlen($content) == 0) {
            return;
        }
        $sql = "USE `joonasja_camagru`";
        $dbConn->exec($sql);
        $sql2 = "INSERT INTO `comments` (`user_id`, `image_id`, `content`)
        VALUES (?, ?, ?);
        ";
        $stmt = $dbConn->prepare($sql2);
        $stmt->execute([$agent, $image_id, $content]);
        sendCommentNotification($agent, $image_id, $dbConn);
        return;
    }
}
