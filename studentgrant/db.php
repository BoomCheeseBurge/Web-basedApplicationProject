<?php
    // Connect to the MariaDB database
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'studentgrant';
    $db = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    if (mysqli_connect_errno()) {
        echo 'Error: Could not connect to database. Please try again later.';
        exit;
    }
?>