<?php

function checkPasswordStrength($password)
{
   $strengthPoints = 0;
   if (strlen($password) < 8 || strlen($password) > 30) {
      return FALSE;
   }
   // The password is not allowed to have whitespace characters.
   if (preg_match("/\s/", $password)) {
      return FALSE;
   }
   // Check if the password contains a numeric character.
   if (preg_match("/\d/", $password)) {
      $strengthPoints++;
   }
   if (preg_match("/[A-Z]/", $password)) {
      $strengthPoints++;
   }
   if (preg_match("/[a-z]/", $password)) {
      $strengthPoints++;
   }
   // The password should contain at least one special character or an underscore.
   if (preg_match("/\W/", $password) || (preg_match("/_/", $password))) {
      $strengthPoints++;
   }
   if ($strengthPoints < 3) {
      return FALSE;
   } else {
      return TRUE;
   }
}

function checkEmailStrength($email)
{
   $length = strlen($email);
   if ($length < 6) {
      return FALSE;
   }
   // Check that there is only on '@' and that it's surrounded by non-@ characters.
   if (!preg_match("/^[^@]*@[^@]*$/", $email)) {
      return FALSE;
   }
   // Check for a certain pattern: there must be at least one char before and after '@', and the string must end with a period and 2 or 3 alphabetical characters (i.e. .fi, .com, .net, etc.).
   if (!preg_match("/^.+@.+\.[a-z]{2,3}$/i", $email)) {
      return FALSE;
   }
   return TRUE;
}

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

function generateRandomString2($scope, $dbConn)
{
    $randomString = '';
    while ($randomString == '') {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $scope; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        $randomString = hash('whirlpool', $randomString);
        $sql = "SELECT * FROM password_requests WHERE reset_link_url=?";
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

// returns TRUE if the email exists in Camagru, and FALSE if there's no user with the email provided.
function createNewPasswordRequest($email, $reset_link_url, $dbConn)
{
    $sql = "SELECT *
            FROM users
            WHERE email=?
            ;";
    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$email]);
    if ($stmt->rowCount() < 1) {
        return FALSE;
    } else {
        $sql2 = "INSERT INTO password_requests (`email`, `reset_link_url`, `active_bool`)
                VALUES (?, ?, ?);";
        $stmt = $dbConn->prepare($sql2);
        $stmt->execute([$email, $reset_link_url, 1]);
        return TRUE;
    }
}

function sendPasswordResetEmail($email, $reset_link_url, $dbConn)
{
    echo "Made it to the function.";
    $to = $email;

    $sql = "USE `joonasja_camagru`";
    $dbConn->exec($sql);

    $link = "<a href='http://localhost:8080/camagru/password_reset.php?reset_url=" . $reset_link_url . "'>Click here to reset your password.</a>";

    // Check if we need to create a new password reset request, and if yes, create it.
    $requestCreated = createNewPasswordRequest($email, $reset_link_url, $dbConn);

    // Checking if the request was created and tailoring the message based on that.
    if ($requestCreated) {
        $subject = 'Here is a link to reset your password';
        $message = '<html><body>';
        $message .= '<h1 style="color:#f40;">Here is a link to reset your password.</h1>';
        $message .= '<p style="color:#080;font-size:18px;">Click on the link below to be moved to the password reset page, where you can set yourself a new password.</p>';
        $message .= $link;
        $message .= '<p style="color:#080;font-size:18px;">If you did not request to reset your password, you can ignore this message. The link will automatically expire in 24 hours.</p>';
        $message .= '</body></html>';
    } else {
        $subject = 'Regarding your request to reset your password';
        $message = '<html><body>';
        $message .= '<h1 style="color:#f40;">Regarding your request to reset your password</h1>';
        $message .= '<p style="color:#080;font-size:18px;">You have requested a new password for your account with this email. Unfortunately, this email address was not found in our database. We suggest you try again with another email address.</p>';
        $message .= '<p style="color:#080;font-size:18px;">If you did not request to reset your password, you can ignore this message.</p>';
        $message .= '</body></html>';
    }

    $from = 'no-reply@camagru.fi';

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Create email headers
    $headers .= 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Sending email. These will need to be removed!! Not sure where this will appear. 
    if (mail($to, $subject, $message, $headers)) {
        echo 'Your mail has been sent successfully.';
    } else {
        echo 'Unable to send email. Please try again.';
    }
}
