<?php
    session_start();
    include('db.php');
    include('header.html');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// echo $_POST["submit"];
        
        // Check if the file was uploaded
        if (isset($_FILES['grantproposal'])) {
            // Store the registration details
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $publicationtype = $_POST["publicationtype"];
            $file_name = $_FILES['grantproposal']['name'];
            // $file_size = $_FILES['grantproposal']['size'];
            $file_type = $_FILES['grantproposal']['type'];
            // $file_temp = $_FILES['grantproposal']['tmp_name'];

            // Get current date of registration
            $date = date("Y-m-d H:i:s");

            // Check if the file is a PDF
            if ($file_type == 'application/pdf') {
                // Read the file content
                // $file_content = file_get_contents($file_temp);

                // Escape special characters
                $file_name = mysqli_real_escape_string($db, $file_name);
                // $file_content = mysqli_real_escape_string($dbc, $file_content);

                // Execute the MAX query
                $query_maxid = "SELECT MAX(id) AS maxid FROM app";
                $result = mysqli_query($db, $query_maxid);

                // Fetch the result as an associative array
                $row = mysqli_fetch_assoc($result);

                // Get the value of the maxid column and increment by 1
                $maxid = $row['maxid'];
                $maxid += 1;

                // Insert the file into the database
                // $query = "INSERT INTO pdf_files (file_name, file_size, file_type, file_content) VALUES ('$file_name', $file_size, '$file_type', '$file_content')";
                // mysqli_query($dbc, $query);

                $query = "INSERT INTO app (id, publicationtypeid, grantproposal, applicationdate, applicationtimestamp) VALUES ('$maxid', '$publicationtype', '$file_name', '$date', '$date')";
                mysqli_query($db, $query);

                $userid = $_SESSION['userid'];

                $query2 = "INSERT INTO userapplication (userid, applicationid) VALUES ('$userid', '$maxid')";
                mysqli_query($db, $query2);

                // Specify the location to store uploaded file
                $uploaddir = "fileuploaded/";
                // Identify uploaded file from server temp folder
                $file = $_FILES["grantproposal"]["tmp_name"];
                // Identify the directory path where the file should be placed
                $destination = $uploaddir. $_FILES["grantproposal"]["name"];
                // Move uploaded file from server temp folder to dedicated folder or directory
                move_uploaded_file($file, $destination);

                header("Location: facultypage.php");
                
                // if(is_uploaded_file($file)) {
                //     if(move_uploaded_file($file, $destination)) {         
                //         echo "File" . $_FILES["grantproposal"]["name"]. "<br>";
                //         echo "File is successfully uploaded!";
                //         echo "Uploaded by ". $name."(".$matric.")";
                //         // File Information
                //         echo "Name: ".$_FILES["grantproposal"]["name"]."<br>";
                //         echo "Type: ".$_FILES["grantproposal"]["type"]."<br>";
                //         echo "Temp name: ".$_FILES["grantproposal"]["tmp_name"]."<br>";
                //         echo "Size: ".$_FILES["grantproposal"]["size"]."<br>";
                //         echo "Error: ".$_FILES["grantproposal"]["error"]."<br>";
                //     }
                //     else {
                //         echo "File is successfully uploaded!";
                //     }
                // }
                // else {
                //     echo "No file uploaded through HTML form!";
                // }
                // // Display a success message
                // echo "The file has been uploaded.";
            }
            else {
                // Display an error message
                $_SESSION["filetypeerror"] = "Only PDF files are allowed.";
                // header("Location: applicationform.php");
                include('applicationform.php');
            }
        }
    }else {include('applicationform.php'); }
    
    include('footer.html')
?>



