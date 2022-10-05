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
// "SELECT COUNT(username) FROM users WHERE username = 'bb';"
// $sql2 = "SELECT username FROM users WHERE username = 'asdf';";
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

$sql3 = "SELECT * FROM users;";
$stmt = $dbConn->query($sql3);
while ($array = $stmt->fetch(PDO::FETCH_ASSOC)) {
   echo $array['username'] . '<br>';
   echo $array . '<br>';
}

echo '<br>' . 'Fourth query result should appear below:' . '<br>';

$sql4 = "SELECT password FROM users WHERE username='jjamsa';";
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

$sql5 = "SELECT id FROM users WHERE username=?;";
$user = 'gg';
$stmt = $dbConn->prepare($sql5);
$stmt->execute([$user]);
$logged_in_user_id = $stmt->fetch(PDO::FETCH_COLUMN);
echo $logged_in_user_id . '<br>';

echo '<br>' . 'Sixth query result should appear below:' . '<br>';

$sql6 = "SELECT image_data FROM images WHERE image_id=?;";
$image_id = 187;
$stmt = $dbConn->prepare($sql6);
$stmt->execute([$image_id]);
$image_data = $stmt->fetch(PDO::FETCH_COLUMN);
echo $image_data . '<br>';

echo '<br>' . 'Seventh query result should appear below:' . '<br>';

// The below returns seven rows with three liked pictures
// $sql7 = "SELECT `likes`.`like_id`, `likes`.`user_id`, `likes`.`image_id`, `images`.`image_id` as toinen
// FROM likes
// INNER JOIN users
// ON likes.user_id = users.id
// INNER JOIN images
// ON users.id = images.user_id;
// ";

// $stmt = $dbConn->prepare($sql7);
// $stmt->execute();
// $likes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// foreach ($likes as $key => $value) {
//    echo $value['like_id'] . '<br>';
//    echo $value['user_id'] . '<br>';
//    echo $value['image_id'] . '<br>';
//    echo $value['toinen'] . '<br>';
// }

echo '<br>' . '<br>';

// while ($likes) {
//    echo $likes['like_id'] . '<br>';
//    echo $likes . '<br>';
// }

// The below returns three rows with three liked pictures
$sql8 = "SELECT `likes`.`like_id`, `likes`.`user_id`, `likes`.`image_id`
FROM likes
INNER JOIN users
ON likes.user_id = users.id
INNER JOIN images
ON likes.image_id = images.image_id;
";

// The below returns three rows with three liked pictures and all other data on the user and image on the same row if with star.
$sql9 = "SELECT `likes`.`like_id`, `likes`.`user_id`, `likes`.`image_id`, `users`.`username`
FROM likes
INNER JOIN users
ON likes.user_id = users.id
INNER JOIN images
ON likes.image_id = images.image_id;
";

// The below returns three rows with three liked pictures and all other data on the user and image on the same row if with star.
$sql10 = "SELECT *
FROM likes
INNER JOIN users
ON likes.user_id = users.id
INNER JOIN images
ON likes.image_id = images.image_id;
";

echo '<br>' . 'Eleventh query result should appear below:' . '<br>';

$agent = 73;
$likedImageId = 217;

$sql11 = "SELECT images.user_id as userid FROM images WHERE `image_id`=?;";
$stmt = $dbConn->prepare($sql11);
$stmt->execute([$likedImageId]);
$pictureOwner = $stmt->fetch(PDO::FETCH_ASSOC);

echo '$_SESSION["logged_in_user_id"]: ' . $agent . '<br>';
echo '$pictureOwner: ' . $pictureOwner . '<br>';
echo '$pictureOwner["userid"]: ' . $pictureOwner['userid'] . '<br>';

if ($agent == $pictureOwner['userid']) {
   echo "Liker was the same as the picture owner. " . '$pictureOwner["userid"]: ' . $pictureOwner['userid'];
   return;
}

echo '<br>' . 'Twelfth query result should appear below:' . '<br>';

$agent = 73;
$likedImageId = 217;

$sql12 = "SELECT images.user_id as userid, email, username
FROM images
INNER JOIN users
ON images.user_id = users.id
WHERE `image_id`=?;
";

$stmt = $dbConn->prepare($sql12);
$stmt->execute([$likedImageId]);
$pictureOwner = $stmt->fetch(PDO::FETCH_ASSOC);

echo '$_SESSION["logged_in_user_id"]: ' . $agent . '<br>';
echo '$pictureOwner: ' . $pictureOwner . '<br>';
echo '$pictureOwner["userid"]: ' . $pictureOwner['userid'] . '<br>';
echo '$pictureOwner["email"]: ' . $pictureOwner['email'] . '<br>';

if ($agent == $pictureOwner['userid']) {
   echo "Liker was the same as the picture owner. " . '$pictureOwner["userid"]: ' . $pictureOwner['userid'];
   return;
}

// UPDATE users SET email = 'aiden.leung555@protonmail.com' WHERE id = 74;

?>