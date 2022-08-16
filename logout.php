<?php

session_start();

if (isset($_SESSION['loginPersist'])) {

    $_SESSION['loginPersist'] = FALSE;
    $_SESSION['loginSuccess'] = FALSE;
}

header('Location: welcome.php');

?>