<?php
    // Several codes below are used to guarantee or ensure that the session has been completely terminated
    session_start();
    $_SESSION = [];
    session_unset();
    session_destroy();

    header("Location: index.php");

    exit;
?>