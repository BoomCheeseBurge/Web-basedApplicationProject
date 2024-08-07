<?php
	// Start Session
	session_start();
    
    // Check if the user has logged in
    if(!isset($_SESSION["userid"]))
	{
        header('Location: index.php');
        exit; // Ignore all script below
    }

    $pageTitle = "grant";

    include('layout/header.php');

    // Check if the submit button is pressed
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Check if the file was uploaded
        if (isset($_FILES['grantproposal'])) {

            // Store the grant registration details
            $title = htmlspecialchars($_POST["title"]);
            $publicationtypeid = $_POST["publicationtype"];
            $file_name = $_FILES['grantproposal']['name'];
            // $file_size = $_FILES['grantproposal']['size'];
            $file_type = $_FILES['grantproposal']['type'];
            // $file_temp = $_FILES['grantproposal']['tmp_name'];

            // Get current date of registration
            $date = date("Y-m-d H:i:s");

            // Check if the file is a PDF
            if ($file_type == 'application/pdf') {

                // Escape special characters
                $file_name = mysqli_real_escape_string($db, $file_name);

                // Insert new grant application into the database
                $query2 = "INSERT INTO app (id, publicationtypeid, research_title, grantproposal, applicationdate, applicationtimestamp, reviewerstatus, reqmodify) VALUES ('', '$publicationtypeid', '$title', '$file_name', '$date', '$date', 0, 0)";
                mysqli_query($db, $query2);

                // Store the current logged-in user ID
                $userid = $_SESSION['userid'];

                // Find the ID number of the recently added grant application
                $query_maxid = "SELECT MAX(id) AS maxid FROM app";
                $result2 = mysqli_query($db, $query_maxid);

                // Fetch the result as an associative array
                $row2 = mysqli_fetch_assoc($result2);

                // Get the value of the ID
                $maxid = $row2['maxid'];

                if ($_SESSION["roleid"] == 1)
                {
                    // Add user-application to the joint table
                    $query3 = "INSERT INTO userapplication (userid, applicationid, reviewer1_id, reviewer2_id) VALUES ('$userid', '$maxid', 'none', 4)";
                    mysqli_query($db, $query3);
                } else {
                    // Add user-application to the joint table
                    $query3 = "INSERT INTO userapplication (userid, applicationid, reviewer1_id, reviewer2_id) VALUES ('$userid', '$maxid', 'none', 6)";
                    mysqli_query($db, $query3);
                }

                // Specify the location to store uploaded file
                $uploaddir = "fileuploaded/";
                // Identify uploaded file from server temp folder
                $file = $_FILES["grantproposal"]["tmp_name"];

                // Identify the directory path where the file should be placed
                $destination = $uploaddir. $_FILES["grantproposal"]["name"];
                // Move uploaded file from server temp folder to dedicated folder or directory
                move_uploaded_file($file, $destination);

                header("Location: grant-list.php");
            }
            else {
                // Display an error message
                $_SESSION["filetypeerror"] = "Only PDF files are allowed.";
                // header("Location: applicationform.php");
                include('applicationform.php');
            }
        }
    }else { include('applicationform.php'); }
    
    include('layout/footer.php');
?>