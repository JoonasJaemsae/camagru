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
      echo $row['login'] . '<br>';
      echo $row['password'] . '<br>';
      echo $row['email'] . '<br>';
      echo '<br>';
   }
}

?>