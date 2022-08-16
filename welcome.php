<!DOCTYPE html>
<html>
<head>
<title>Camagru</title>

<style>

<?php

session_start();

include "style.css";
include_once './includes/dbh.php';

if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

    header("Location: index.php");
}
if ($_SESSION['loginSuccess'] === TRUE) {

    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['loginPersist'] = TRUE;
    $_SESSION['loginErrorMessage'] = FALSE;
}

?>

</style>

</head>

</html>

<?php

if (isset($_SESSION['loginPersist'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <body>
        <h1 style="margin-top: 100px">Welcome, master!</h1>
    
    <div>
          <img src="http://media2.s-nbcnews.com/i/streams/2013/June/130617/6C7911377-tdy-130617-leo-toasts-1.jpg"
          alt="Congrats!">
    </div>
    <div style="text-align: center;">
        <a href="logout.php" style>Click here to log out.</a>
    </div>
    </body>
    </html>
<?php

}

?>
