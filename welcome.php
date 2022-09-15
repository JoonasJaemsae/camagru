<?php

ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

session_start();
// ob_start(); // if logout redirection doesn't work.

include_once './includes/new_conn.php';

if ($_SESSION['loginSuccess'] === TRUE) {

    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['loginPersist'] = TRUE;
    $_SESSION['loginErrorMessage'] = FALSE;
}
if ($_SESSION['loginSuccess'] === FALSE && $_SESSION['loginPersist'] === FALSE) {

    header("Location: index.php");
    exit();
}

?>
<html>

<head>
    <title>Camagru</title>

    <style>
        <?php include "style.css"; ?>
    </style>
</head>

</html>

<?php

if (isset($_SESSION['loginPersist'])) {
?>
    <html>

    <body id="gradient">
        <?php

            include_once 'navbar.php';

        ?>
        <h1 style="margin-top: 100px">
            Welcome, master!
        </h1>
        <div>
            <img src="http://media2.s-nbcnews.com/i/streams/2013/June/130617/6C7911377-tdy-130617-leo-toasts-1.jpg" alt="Congrats!">
        </div>
        <div style="text-align: center;">
            <a href="logout.php">Click here to log out.</a>
        </div>

        <div style="text-align: center;">
            <?php
            if ($_SESSION['loginPersist'] == TRUE) {
                echo '$_SESSION["loginPersist"] is TRUE' . '<br>';
            } else if ($_SESSION['loginPersist'] == FALSE) {
                echo '$_SESSION["loginPersist"] is FALSE' . '<br>';
            }

            if ($_SESSION['loginSuccess'] == TRUE) {
                echo '$_SESSION["loginSuccess"] is TRUE' . '<br>';
            } else if ($_SESSION['loginSuccess'] == FALSE) {
                echo '$_SESSION["loginSuccess"] is FALSE' . '<br>';
            }
            // if ($_SESSION['loginSuccess'] == FALSE && $_SESSION['loginPersist'] == FALSE) {

            //     header("Location: index.php");
            // }
            ?>
        </div>
    </body>

    </html>
<?php

}

?>