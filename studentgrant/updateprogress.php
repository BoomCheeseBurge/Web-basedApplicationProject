<?php
    // Start Session
    session_start();
    // Connect to the database
    include('db.php');
    // A function to verify the login input by the user
    include('myfunction.php');

    // Get user ID, proposal status, and role ID
    $userid = $_GET["userid"];
    $status = $_GET["status"];
    $role_id = $_SESSION["roleid"];

    $body_accept = "Your proposal has been approved.";
    $body_modify = "Please revise the uploaded proposal file.";
    $body_reject = "Your proposal has been rejected.";

    // The tens value in the 'reviewerstatus' column indicates the level of reviewer
    // The ones value in the 'reviewerstatus' column indicates the status of approval given by the reviewer
    // For instance, the value '11' means that reviewer 1 has given a modify status to the applicant's proposal file

    // Check if its reviewer 1 who requests the grant proposal update
    if($role_id == 2 || $role_id == 3) {

        if(updateProgress($status, "1", $userid, $body_accept) > 0)
        {
            echo "
                <script>
                    alert('Successfully updated proposal!');
                </script>
            ";
        } else
        {
            echo "
                <script>
                    alert('Failed to update proposal!);
                </script>
            ";
        }
        header("Location: reviewerpage.php");

    // Check if its reviewer 2 who requests the grant proposal update
    }else if($role_id == 4 || $role_id == 6) {

        if(updateProgress($status, "2", $userid, $body_accept) > 0)
        {
            echo "
                <script>
                    alert('Successfully updated proposal!');
                </script>
            ";
        } else
        {
            echo "
                <script>
                    alert('Failed to update proposal!);
                </script>
            ";
        }
        header("Location: reviewerpage.php");

    // Check if its reviewer 3 who requests the grant proposal update
    }else if($role_id == 5) {

        if(updateProgress($status, "3", $userid, $body_accept) > 0)
        {
            echo "
                <script>
                    alert('Successfully updated proposal!');
                </script>
            ";
        } else
        {
            echo "
                <script>
                    alert('Failed to update proposal!);
                </script>
            ";
        }
        header("Location: reviewerpage.php");
    }
?>