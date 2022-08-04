<?php

include_once './includes/dbh.php';

echo 'Site load successful. Your query results should appear below this line if the query was successful:' . '<br>';

$path = './data/';
$file_path = $path."accounts";

if ($_POST['password'] == $_POST['passwordAgain']) {
    if ($_POST['username'] && $_POST['password'] && $_POST['passwordAgain']) {
        if (!file_exists($path) && !mkdir($path)) {
            // Mieti viela tata mkdiria.
            exit();
        }
        if (!file_exists($file_path)) {
            file_put_contents($file_path, 'ankka');
        }

    } else {
        echo "ERROR. A field was left empty.";
    }
} else {
    echo "Passwords didn't match. Please try again!";
}

$sql = "SELECT * FROM testusers;";
$result = mysqli_query($link, $sql);
$resultCheck = mysqli_num_rows($result);
if ($resultCheck > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
      print_r($row);
      echo '<br>';
      echo $row['login'] . '<br>';
      echo $row['password'] . '<br>';
      echo $row['email'] . '<br>';
      echo '<br>';
   }
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