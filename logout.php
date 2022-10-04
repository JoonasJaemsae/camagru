<?php

session_start();

if (isset($_SESSION['loginPersist'])) {

    $_SESSION['loginPersist'] = FALSE;
    $_SESSION['loginSuccess'] = FALSE;
    $_SESSION['logged_in_user_id'] = '';
}

header('Location: index.php');

?>