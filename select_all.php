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

include_once './config/new_conn.php';   

echo 'Site load successful.' . '<br>';
// echo 'Your query results should appear below this line if the query was successful:' . '<br>';

// $sql = "SELECT * FROM testusers;";
// $result = mysqli_query($link, $sql);
// $resultCheck = mysqli_num_rows($result);
// if ($resultCheck > 0) {
//    while ($row = mysqli_fetch_assoc($result)) {
//       print_r($row);
//       echo '<br>';
//       echo $row['username'] . '<br>';
//       echo $row['password'] . '<br>';
//       echo $row['email'] . '<br>';
//       echo '<br>';
//    }
// }

// echo 'Second query result should appear below:' . '<br>';

// "SELECT * FROM testusers;"
// "SELECT COUNT(username) FROM syottotesti WHERE username = 'bb';"
// $sql2 = "SELECT username FROM syottotesti WHERE username = 'asdf';";
// $result2 = mysqli_query($link, $sql2); 
// $resultCheck2 = mysqli_num_rows($result2);
// echo $resultCheck2 . ' <-- How many results were found.' .'<br>';
// if ($resultCheck2 > 0) {
//    while ($row = mysqli_fetch_assoc($result2)) {
//       print_r($row);
//       echo '<br>';
//       echo $row['COUNT(username)'] . '<br>';
//    }
// }

echo 'Third query result should appear below:' . '<br>';

$sql3 = "SELECT * FROM syottotesti;";
$stmt = $dbConn->query($sql3);
while ($array = $stmt->fetch(PDO::FETCH_ASSOC)) {
   echo $array['username'] . '<br>';
   echo $array . '<br>';
}

echo '<br>' . 'Fourth query result should appear below:' . '<br>';

$sql4 = "SELECT password FROM syottotesti WHERE username='jjamsa';";
$stmt = $dbConn->query($sql4);
$i = 0;
while ($array = $stmt->fetch(PDO::FETCH_ASSOC)) {
   echo $array['password'] . '<br>';
   echo $array . '<br>' . '<br>';;
   $i++;
}
if ($array['username'] == FALSE && $i == 0) {   // Try looking for a user that doesn't exist, and this if statement should come true.
   echo '$array["username"] is FALSE.' . '<br>';;
}

echo '<br>' . 'Fifth query result should appear below:' . '<br>';

$sql5 = "SELECT id FROM syottotesti WHERE username=?;";
$user = 'bb';
$stmt = $dbConn->prepare($sql5);
$stmt->execute([$user]);
$logged_in_user_id = $stmt->fetch(PDO::FETCH_COLUMN);
echo $logged_in_user_id . '<br>';

echo '<br>' . 'Sixth query result should appear below:' . '<br>';

$sql6 = "SELECT image_data FROM images WHERE image_id=?;";
$image_id = 2;
$stmt = $dbConn->prepare($sql6);
$stmt->execute([$image_id]);
$image_data = $stmt->fetch(PDO::FETCH_COLUMN);
echo $image_data . '<br>';

?>