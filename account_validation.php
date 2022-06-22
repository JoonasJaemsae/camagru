<?php

$path = './data/';
$file_path = $path."accounts";

if ($_POST['password'] == $_POST['passwordAgain']) {
    if ($_POST['username'] && $_POST['password'] && $_POST['password']) {
        if (!file_exists($path) && !mkdir($path)) {
            // Mieti viela tata mkdiria.
            exit();
        }
        if (!file_exists($file_path)) {
            file_put_contents($file_path, NULL);
        }

    } else {
        echo "ERROR. A field was left empty.";
    }
} else {
    echo "Passwords didn't match. Please try again!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru - Validating account. Hold on a moment!</title>
</head>
<body>
    
</body>
</html>