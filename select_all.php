<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
   <a href="index.php">Back to login</a>
   <br></br>
</body>
</html>

<?php

include_once './includes/dbh.php';

echo 'Site load successful. Your query results should appear below this line if the query was successful:' . '<br>';

$sql = "SELECT * FROM testusers;";
$result = mysqli_query($link, $sql);
$resultCheck = mysqli_num_rows($result);
if ($resultCheck > 0) {
   while ($row = mysqli_fetch_assoc($result)) {
      print_r($row);
      echo '<br>';
      echo $row['username'] . '<br>';
      echo $row['password'] . '<br>';
      echo $row['email'] . '<br>';
      echo '<br>';
   }
}

echo 'Second query should appear below:' . '<br>';

// "SELECT * FROM testusers;"
// "SELECT COUNT(username) FROM syottotesti WHERE username = 'bb';"
$sql2 = "SELECT username FROM syottotesti WHERE username = 'asdf';";
$result2 = mysqli_query($link, $sql2); 
$resultCheck2 = mysqli_num_rows($result2);
echo $resultCheck2 . ' <-- How many results were found.' .'<br>';
if ($resultCheck2 > 0) {
   while ($row = mysqli_fetch_assoc($result2)) {
      print_r($row);
      echo '<br>';
      echo $row['COUNT(username)'] . '<br>';
   }
}

?>