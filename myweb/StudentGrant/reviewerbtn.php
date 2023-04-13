<?php
    // Start Session
    session_start();
    // Connect to the database
    include('db.php');
    // A function to verify the login input by the user
    include('myfunction.php');

    $userid = $_GET["userid"];
    $status = $_GET["status"];

    $body_modify = "Please revise the uploaded proposal file.";
    $body_accept = "Your proposal has been approved.";
    $body_reject = "Your proposal has been rejected.";

    if($_SESSION["roleid"] == 2 || $_SESSION["roleid"] == 3) {
        if($status == "modify") {
            $query_userappid = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
                                JOIN user AS u ON ua.userid = u.id SET reviewerstatus = 11 WHERE u.id = '$userid'";
            mysqli_query($db, $query_userappid);
            email($body_modify);
        }
    }

?>