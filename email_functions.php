<?php

function generateRandomString($scope, $dbConn)
{
    $randomString = '';
    while ($randomString == '') {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $scope; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        $randomString = hash('whirlpool', $randomString);
        $sql = "SELECT * FROM users WHERE verification_code=?";
        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$randomString]);
        if ($stmt->rowCount() > 0) {
            $randomString = '';
            continue;
        } else {
            return $randomString;
        }
    }
}

function sendVerificationEmail($email, $verifCode, $dbConn)
{
    echo "Made it to the function.";
    $to = $email;
    $to = 'aiden.leung555@protonmail.com';

    $sql = "USE `joonasja_camagru`";
    $dbConn->exec($sql);

    $subject = 'Verify your email address';
    $from = 'no-reply@camagru.fi';

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $link = "<a href='http://localhost:8080/camagru/email_verification.php?verifCode=" . $verifCode . "'>Verify your email address</a>";

    // Compose a simple HTML email message
    $message = '<html><body>';
    $message .= '<h1 style="color:#f40;">Your email address needs to be verified</h1>';
    $message .= '<p style="color:#080;font-size:18px;">Click on the link below to verify your email address.</p>';
    $message .= $link;
    $message .= '</body></html>';
 
    // Sending email
    if (mail($to, $subject, $message, $headers)) {
        echo 'Your mail has been sent successfully.';
    } else {
        echo 'Unable to send email. Please try again.';
    }


}
