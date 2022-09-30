<?php

$sql = "SELECT image_id, image_data, username
FROM images
INNER JOIN syottotesti
ON images.user_id = syottotesti.id
ORDER BY image_id DESC;
";

$stmt = $dbConn->prepare($sql);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

function checkUsersLike($image_id, $dbConn)
{
    if (isset($_SESSION['logged_in_user_id'])) {
        $user_id = $_SESSION['logged_in_user_id'];

        $sql = "SELECT `likes`.`like_id`, `likes`.`user_id`, `likes`.`image_id`, `syottotesti`.`username`
            FROM likes
            INNER JOIN syottotesti
            ON likes.user_id = syottotesti.id
            INNER JOIN images
            ON likes.image_id = images.image_id
            WHERE `likes`.`user_id`=? AND `likes`.`image_id`=?;
            ";

        $stmt = $dbConn->prepare($sql);
        $stmt->execute([$user_id, $image_id]);
        // $likeCheck = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
            // echo "TRUUUU! " . $user_id . ' ' . $image_id  . '<br>'; // for testing.
            return true;
        } else {
            return false;
        }
    }
}

function getImageLikeCount($image_id, $dbConn)
{
    $sql = "SELECT COUNT(likes.image_id) as total
        FROM likes
        WHERE image_id=?;
        ";

    $stmt = $dbConn->prepare($sql);
    $stmt->execute([$image_id]);
    $count = $stmt->fetch();
    // echo $count['total'];
    return $count['total'];
}

$image_id = $_POST['imageId'];
echo  "data:image/jpeg;base64," . $image_data;
