<?php
    //Include required phpmailer files
    require 'phpmailer/includes/PHPMailer.php';
    require 'phpmailer/includes/SMTP.php';
    require 'phpmailer/includes/Exception.php';
    //Define namespaces
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // function includeWithVariables($filePath, $variables = array(), $print = true)
    // {
    //     // Extract the variables to a local namespace
    //     extract($variables);

    //     // Start output buffering
    //     ob_start();

    //     // Include the template file
    //     include $filePath;

    //     // End buffering and return its contents
    //     $output = ob_get_clean();
    //     if (!$print) {
    //         return $output;
    //     }
    //     echo $output;
    // }

    function loginVerification($db, $email, $pw) {
        $hash = sha1($pw);
        $query = "SELECT u.id AS userid, u.firstname, u.lastname, u.faculty, u.email, ur.roleid, r.rolename
                  FROM user AS u 
                  JOIN userrole AS ur ON u.id = ur.userid
                  JOIN role AS r ON ur.roleid = r.id
                  WHERE email = '$email' AND passkey = '$hash'";
        return mysqli_query($db, $query);
    }

    // function query($query)
	// {
	// 	global $db;
	// 	$result = mysqli_query($db, $query);

	// 	$rows = []; // Temporary array to store iterated records

    //     if(mysqli_affected_rows($db) > 1)
    //     {
    //         while ($row = mysqli_fetch_assoc($result)) {
    //             $rows[] = $row;
    //         }
    //     } else {
    //         return mysqli_fetch_assoc($result);
    //     }

	// 	return $rows;
	// }

    // ********************************************************************************************************************* */ 
    
    //  ██████╗ ██████╗  █████╗ ███╗   ██╗████████╗    ██╗     ██╗███████╗████████╗
    //  ██╔════╝ ██╔══██╗██╔══██╗████╗  ██║╚══██╔══╝    ██║     ██║██╔════╝╚══██╔══╝
    //  ██║  ███╗██████╔╝███████║██╔██╗ ██║   ██║       ██║     ██║███████╗   ██║   
    //  ██║   ██║██╔══██╗██╔══██║██║╚██╗██║   ██║       ██║     ██║╚════██║   ██║   
    //  ╚██████╔╝██║  ██║██║  ██║██║ ╚████║   ██║       ███████╗██║███████║   ██║   
    //   ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝       ╚══════╝╚═╝╚══════╝   ╚═╝   

    function getGrantList(string $user_id = null, array $rev_stats = [])
    {    
        global $db;
    
        if (!is_null($user_id) && !empty($rev_stats))
        {
            $query = "SELECT a.id AS appid, a.research_title AS proposalname, a.grantproposal AS filename, pt.type_name AS pubtype, a.publicationtypeid AS pubid, a.reviewerstatus AS revstat, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE ua.userid = $user_id 
            AND (a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats) . ")";
        } else
        {
            $query = "SELECT a.id AS appid, a.research_title AS proposalname, a.grantproposal AS filename, pt.type_name AS pubtype, a.publicationtypeid AS pubid, a.reviewerstatus AS revstat, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE ua.userid = $user_id";
        }

        $result = mysqli_query($db, $query);

        // Fetch rows from the result set and store them in an array
        $appList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appList[] = $row;
        }
    
        return $appList;
    }

    function editProposal($fileUpload, $title = null, $pubType = null)
    {
        global $db;

        if($fileUpload)
        {
            $old_file = $_POST['old_file'];
            $file_name = $_FILES['proposalFile']['name'];
            $app_id = $_POST['app_id'];
            $tmp_name = $_FILES['proposalFile']['tmp_name'];
            
            // Remove old proposal file
            unlink("fileuploaded/$old_file");

            // Escape special characters
            $file_name = mysqli_real_escape_string($db, $file_name);

            // Update the 'reqmodify' as the applicant has revised and reuploaded the proposal file
            $query = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN user AS u ON ua.userid = u.id SET grantproposal = '$file_name', reqmodify = 0 WHERE ua.applicationid = $app_id";
            mysqli_query($db, $query);
            
            // Specify the location to store uploaded file
            $uploaddir = "fileuploaded/";
            // Identify the directory path where the file should be placed
            $destination = $uploaddir. $file_name;
            // Move uploaded file from server temp folder to dedicated folder or directory
            move_uploaded_file($tmp_name, $destination);

        } else {

        }



        return mysqli_affected_rows($db);
    }

    function deleteProposal()
    {
        global $db;
    }

    // ********************************************************************************************************************* */

    //  ██████╗ ██████╗  █████╗ ███╗   ██╗████████╗    ██████╗ ██████╗  ██████╗  ██████╗ ██████╗ ███████╗███████╗███████╗
    //  ██╔════╝ ██╔══██╗██╔══██╗████╗  ██║╚══██╔══╝    ██╔══██╗██╔══██╗██╔═══██╗██╔════╝ ██╔══██╗██╔════╝██╔════╝██╔════╝
    //  ██║  ███╗██████╔╝███████║██╔██╗ ██║   ██║       ██████╔╝██████╔╝██║   ██║██║  ███╗██████╔╝█████╗  ███████╗███████╗
    //  ██║   ██║██╔══██╗██╔══██║██║╚██╗██║   ██║       ██╔═══╝ ██╔══██╗██║   ██║██║   ██║██╔══██╗██╔══╝  ╚════██║╚════██║
    //  ╚██████╔╝██║  ██║██║  ██║██║ ╚████║   ██║       ██║     ██║  ██║╚██████╔╝╚██████╔╝██║  ██║███████╗███████║███████║
    //   ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝       ╚═╝     ╚═╝  ╚═╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝

    function getGrantProposal($app_id, $user_id)
    {
        global $db;

        // Find the application status of the user
        $query = "SELECT a.grantproposal AS fileTitle, a.research_title AS title, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify FROM app AS a
        JOIN userapplication AS ua ON a.id = ua.applicationid
        JOIN publicationtype AS pt ON a.publicationtypeid = pt.id 
        WHERE ua.applicationid = $app_id AND ua.userid = $user_id";
        
        $result = mysqli_query($db, $query);
        
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

    function reviseProposal($file_name, $tmp_name, $old_file, $app_id)
    {
        global $db;

        // Remove old proposal file
        unlink("fileuploaded/$old_file");

        // Escape special characters
        $file_name = mysqli_real_escape_string($db, $file_name);

        // Update the 'reqmodify' as the applicant has revised and reuploaded the proposal file
        $query = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
        JOIN user AS u ON ua.userid = u.id SET grantproposal = '$file_name', reqmodify = 0 WHERE ua.applicationid = $app_id";
        mysqli_query($db, $query);
        
        // Specify the location to store uploaded file
        $uploaddir = "fileuploaded/";
        // Identify the directory path where the file should be placed
        $destination = $uploaddir. $file_name;
        // Move uploaded file from server temp folder to dedicated folder or directory
        move_uploaded_file($tmp_name, $destination);

        return mysqli_affected_rows($db);
    }

    // ********************************************************************************************************************* */ 
    
    //  ██████╗ ██████╗  █████╗ ███╗   ██╗████████╗     █████╗ ██████╗ ██████╗ ██╗     ██╗ ██████╗ █████╗ ███╗   ██╗████████╗
    //  ██╔════╝ ██╔══██╗██╔══██╗████╗  ██║╚══██╔══╝    ██╔══██╗██╔══██╗██╔══██╗██║     ██║██╔════╝██╔══██╗████╗  ██║╚══██╔══╝
    //  ██║  ███╗██████╔╝███████║██╔██╗ ██║   ██║       ███████║██████╔╝██████╔╝██║     ██║██║     ███████║██╔██╗ ██║   ██║   
    //  ██║   ██║██╔══██╗██╔══██║██║╚██╗██║   ██║       ██╔══██║██╔═══╝ ██╔═══╝ ██║     ██║██║     ██╔══██║██║╚██╗██║   ██║   
    //  ╚██████╔╝██║  ██║██║  ██║██║ ╚████║   ██║       ██║  ██║██║     ██║     ███████╗██║╚██████╗██║  ██║██║ ╚████║   ██║   
    //   ╚═════╝ ╚═╝  ╚═╝╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝       ╚═╝  ╚═╝╚═╝     ╚═╝     ╚══════╝╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═══╝   ╚═╝   

    function getGrantApplicants(string $user_id = null, array $role_ids = [], array $rev_stats = [])
    {    
        global $db;
    
        if (!is_null($user_id) && !empty($rev_stats))
        {
            $query = "SELECT a.id AS appid, u.id AS userid, ua.reviewer1_id AS reviewer1id, u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE ua.userid <> $user_id 
            AND (a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats) . ")";

        } elseif (!empty($role_ids) && !empty($rev_stats))
        {

            $query = "SELECT a.id AS appid, u.id AS userid, u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE ur.roleid = " . join(' OR ur.roleid = ', $role_ids) . "
            AND (a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats) . ")";

        } elseif (!empty($rev_stats) && is_null($user_id) && empty($role_ids))
        {
            $query = "SELECT a.id AS appid, u.id AS userid, u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats);
        }

        $result = mysqli_query($db, $query);

        // Fetch rows from the result set and store them in an array
        $appList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appList[] = $row;
        }
    
        return $appList;
    }

    function assignProposal($user_id, $app_id)
    {
        global $db;

        $query = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
        JOIN user AS u ON ua.userid = u.id SET reviewer1_id = ".$user_id." WHERE ua.applicationid = $app_id";

        mysqli_query($db, $query);

        return mysqli_affected_rows($db);
    }

    // ********************************************************************************************************************* */ 

    // ██████╗ ███████╗██╗   ██╗██╗███████╗██╗    ██╗███████╗██████╗     ███████╗████████╗ █████╗ ████████╗██╗   ██╗███████╗
    // ██╔══██╗██╔════╝██║   ██║██║██╔════╝██║    ██║██╔════╝██╔══██╗    ██╔════╝╚══██╔══╝██╔══██╗╚══██╔══╝██║   ██║██╔════╝
    // ██████╔╝█████╗  ██║   ██║██║█████╗  ██║ █╗ ██║█████╗  ██████╔╝    ███████╗   ██║   ███████║   ██║   ██║   ██║███████╗
    // ██╔══██╗██╔══╝  ╚██╗ ██╔╝██║██╔══╝  ██║███╗██║██╔══╝  ██╔══██╗    ╚════██║   ██║   ██╔══██║   ██║   ██║   ██║╚════██║
    // ██║  ██║███████╗ ╚████╔╝ ██║███████╗╚███╔███╔╝███████╗██║  ██║    ███████║   ██║   ██║  ██║   ██║   ╚██████╔╝███████║
    // ╚═╝  ╚═╝╚══════╝  ╚═══╝  ╚═╝╚══════╝ ╚══╝╚══╝ ╚══════╝╚═╝  ╚═╝    ╚══════╝   ╚═╝   ╚═╝  ╚═╝   ╚═╝    ╚═════╝ ╚══════╝
   
    /*
     * Note that the following notation is used for the reviewer status:
     *  - First digit indicates the reviewer-N
     *  - Second digit indicates the status (e.g., approved, modify request, or rejected)
    */

    function checkReviewerStatus($revstat, $reqmodify)
    {
        // Every grant application starts with 0% at all progress bars
        if($revstat == 0)
        {
            return [
                'reviewerbar1' => 0,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => '959595',
                'barcolor2' => '959595',
                'barcolor3' => '959595',
                'hideButton' => true
            ];
        }else
        // The grant application has been approved by reviewer 1
        if($revstat == 11)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => '37ff37',
                'barcolor2' => '959595',
                'barcolor3' => '959595',
                'hideButton' => true
            ];
        }else
        // The grant application is modified and awaiting approval of the modified proposal from reviewer 1
        if($revstat == 12 && $reqmodify == 0)
        {
            return [
                'reviewerbar1' => 50,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => '2a9df4',
                'barcolor2' => '959595',
                'barcolor3' => '959595',
                'hideButton' => true
            ];
        }else
        // The grant application needs to be modified and reuploaded by the applicant
        if($revstat == 12 && $reqmodify == 1)
        {
            return [
                'reviewerbar1' => 50,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => '2a9df4',
                'barcolor2' => '959595',
                'barcolor3' => '959595',
                'hideButton' => false
            ];            
        }else
        // The grant application has been rejected by reviewer 1
        if($revstat == 13)
        {
            return [
                'reviewerbar1' => 0,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => 'f4392f',
                'barcolor2' => '959595',
                'barcolor3' => '959595',
                'hideButton' => true
            ];
        }else
        // The grant application has been approved by reviewer 2
        if($revstat == 21)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 100,
                'reviewerbar3' => 0,
                'barcolor1' => '37ff37',
                'barcolor2' => '37ff37',
                'barcolor3' => '959595',
                'hideButton' => true
            ];
        }else
        // The grant application is modified and awaiting approval of the modified proposal from reviewer 2
        if($revstat == 22 && $reqmodify == 0)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 50,
                'reviewerbar3' => 0,
                'barcolor1' => '37ff37',
                'barcolor2' => '2a9df4',
                'barcolor3' => '959595',
                'hideButton' => true
            ];
        }else
        // The grant application needs to be modified and reuploaded by the applicant
        if($revstat == 22 && $reqmodify == 1)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 50,
                'reviewerbar3' => 0,
                'barcolor1' => '37ff37',
                'barcolor2' => '2a9df4',
                'barcolor3' => '959595',
                'hideButton' => false
            ];
        }else
        // The grant application has been rejected by reviewer 2
        if($revstat == 23)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => '37ff37',
                'barcolor2' => 'f4392f',
                'barcolor3' => '959595',
                'hideButton' => false
            ];
        }else
        // The grant application has been approved by reviewer 3
        if($revstat == 31)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 100,
                'reviewerbar3' => 100,
                'barcolor1' => '37ff37',
                'barcolor2' => '37ff37',
                'barcolor3' => '37ff37',
                'hideButton' => false
            ];
        }else
        // The grant application is modified and awaiting approval of the modified proposal from reviewer 3
        if($revstat == 32 && $reqmodify == 0)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 100,
                'reviewerbar3' => 50,
                'barcolor1' => '37ff37',
                'barcolor2' => '37ff37',
                'barcolor3' => '2a9df4',
                'hideButton' => true
            ];
        }else
        // The grant application needs to be modified and reuploaded by the applicant
        if($revstat == 32 && $reqmodify == 1)
        {
            return [
                'reviewerbar1' => 100,
                'reviewerbar2' => 100,
                'reviewerbar3' => 50,
                'barcolor1' => '37ff37',
                'barcolor2' => '37ff37',
                'barcolor3' => '2a9df4',
                'hideButton' => false
            ];
        }else
        // The grant application has been rejected by reviewer 3
        if($revstat == 33)
        {
            return [
                'reviewerbar1' => 0,
                'reviewerbar2' => 0,
                'reviewerbar3' => 0,
                'barcolor1' => '37ff37',
                'barcolor2' => '37ff37',
                'barcolor3' => 'f4392f',
                'hideButton' => true
            ];
        }
    }

    function getGrantProposals(string $user_id = null, array $role_ids = [], array $rev_stats = [], bool $checkAssign)
    {    
        global $db;

        if($checkAssign)
        {
            $assignedToWhom = "AND ua.reviewer1_id = $user_id";
        } else
        {
            $assignedToWhom = "";
        }
    
        if (!is_null($user_id) && !empty($rev_stats))
        {
            $query = "SELECT u.id AS userid, u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE ua.userid <> $user_id 
            $assignedToWhom
            AND (a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats) . ")";

        } elseif (!empty($role_ids) && !empty($rev_stats))
        {

            $query = "SELECT u.id AS userid, u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            WHERE ur.roleid = " . join(' OR ur.roleid = ', $role_ids) . "
            $assignedToWhom
            AND (a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats) . ")";

        } elseif (!empty($rev_stats) && is_null($user_id) && empty($role_ids))
        {
            $query = "SELECT u.id AS userid, u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.type_name AS pubtype, a.reviewerstatus AS revstat, a.reqmodify, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id
            JOIN userrole as ur ON u.id = ur.userid
            $assignedToWhom
            WHERE a.reviewerstatus = " . join(' OR a.reviewerstatus = ', $rev_stats);
        }

        $result = mysqli_query($db, $query);

        // Fetch rows from the result set and store them in an array
        $appList = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appList[] = $row;
        }
    
        return $appList;
    }

    function updateProgress($status, $revstat, $user_id, $emailBody)
    {
        global $db;

        if($status == "accept") {
            // Update the 'reviewerstatus' and 'reqmodify' column to indicate that reviewer 1, 2, or 3, has given approval
            $query = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN user AS u ON ua.userid = u.id SET reviewerstatus = ".$revstat."1, reqmodify = 0 WHERE u.id = $user_id";
            mysqli_query($db, $query);
            email($emailBody);

        }else if($status == "modify") {
            // Update the 'reviewerstatus' and 'reqmodify' columns to inform the applicant to modify and reupload the grant proposal file
            $query = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
                                JOIN user AS u ON ua.userid = u.id SET reviewerstatus = ".$revstat."2, reqmodify = 1 WHERE u.id = $user_id";
            mysqli_query($db, $query);
            email($emailBody);

        }else if($status == "reject") {
            // Update the 'reviewerstatus' and 'reqmodify' columns to inform the applicant that the proposal is rejected
            $query = "UPDATE app AS a JOIN userapplication AS ua ON a.id = ua.applicationid
                                JOIN user AS u ON ua.userid = u.id SET reviewerstatus = ".$revstat."3, reqmodify = 0 WHERE u.id = $user_id";
            mysqli_query($db, $query);
            email($emailBody);
        }

        return mysqli_affected_rows($db);
    }

    // ***************************************************************************************************

    // ███████╗███╗   ███╗ █████╗ ██╗██╗         ██████╗ ███████╗██╗      █████╗ ████████╗███████╗██████╗ 
    // ██╔════╝████╗ ████║██╔══██╗██║██║         ██╔══██╗██╔════╝██║     ██╔══██╗╚══██╔══╝██╔════╝██╔══██╗
    // █████╗  ██╔████╔██║███████║██║██║         ██████╔╝█████╗  ██║     ███████║   ██║   █████╗  ██║  ██║
    // ██╔══╝  ██║╚██╔╝██║██╔══██║██║██║         ██╔══██╗██╔══╝  ██║     ██╔══██║   ██║   ██╔══╝  ██║  ██║
    // ███████╗██║ ╚═╝ ██║██║  ██║██║███████╗    ██║  ██║███████╗███████╗██║  ██║   ██║   ███████╗██████╔╝
    // ╚══════╝╚═╝     ╚═╝╚═╝  ╚═╝╚═╝╚══════╝    ╚═╝  ╚═╝╚══════╝╚══════╝╚═╝  ╚═╝   ╚═╝   ╚══════╝╚═════╝ 

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