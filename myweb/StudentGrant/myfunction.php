<?php
    //Include required phpmailer files
    require 'phpmailer/includes/PHPMailer.php';
    require 'phpmailer/includes/SMTP.php';
    require 'phpmailer/includes/Exception.php';
    //Define namespaces
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    function loginVerification($db, $email, $pw) {
        $hash = sha1($pw);
        $query = "SELECT u.id AS userid, u.firstname, u.lastname, u.faculty, u.email, ur.roleid, r.rolename
                  FROM user AS u 
                  JOIN userrole AS ur ON u.id = ur.userid
                  JOIN role AS r ON ur.roleid = r.id
                  WHERE email = '$email' AND passkey = '$hash'";
        return mysqli_query($db, $query);
    }

    function email($body){       
        //Create instance of phpmailer
        $mail = new PHPMailer();
        //Set mailer to use smtp
        $mail->isSMTP();
        //Define smtp host
        $mail->Host = "smtp.gmail.com";
        //Enable smtp authentication
        $mail->SMTPAuth = "true";
        //Set type of encryption (ssl/tls)
        $mail->SMTPSecure = "tls";
        //Set port to connect smtp
        $mail->Port = "587";
        //Set gmail username
        $mail->Username = "brandonjohnlyc@gmail.com";
        //Set gmail password
        $mail->Password = "nlcnbhywfadhizkd";
        //Set email sucject
        $mail->Subject = "Email Regarding Grant Application";
        //Set email sender
        $mail->setFrom("brandonjohnlyc@gmail.com");
        //Enable HTML
        // $mail->isHTML(true);
        //Email body
        $mail->Body = $body;
        //Add recipient
        $mail->addAddress("brandonjohnlyc@gmail.com");
        //Finally send email
        $mail->send();
        // if ($mail->send()) {
        //     echo "Email sent...!";
        // } else {
        //     echo "Error...!";
        // }
        //Closing smtp connection
        $mail->smtpClose();

    }
?>