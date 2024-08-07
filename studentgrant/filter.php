<?php
    session_start();
    include('db.php');
    include('myfunction.php');


    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $filterType = $_POST["filterType"];

        // Filter handler for Reviewer 1 (HoSP and FacultyMember)
        if ($_SESSION["roleid"] == 2 || $_SESSION["roleid"] == 3)
        {
            if($filterType == "default")
            {
                $appList = getGrantProposals($_SESSION["userid"], [], ["0", "11", "12", "13"]);

            } elseif($filterType == "unattended")
            {
                $appList = getGrantProposals($_SESSION["userid"], [], ["0"]);

            } elseif($filterType == "approved")
            {
                $appList = getGrantProposals($_SESSION["userid"], [], ["11"]);

            } elseif($filterType == "modifypending")
            {
                $appList = getGrantProposals($_SESSION["userid"], [], ["12"]);

            } elseif($filterType == "rejected")
            {
                $appList = getGrantProposals($_SESSION["userid"], [], ["13"]);
            }
        
        // Filter handler for Reviewer 2 (CRCS)
        } elseif ($_SESSION["roleid"] == 6) 
        {
            if($filterType == "default") 
            {
                $appList = getGrantProposals(null, ["2", "3"], ["11", "21", "22", "23"]);

            } elseif($filterType == "unattended")
            {
                $appList = getGrantProposals(null, ["2", "3"], ["11"]);

            } elseif($filterType == "approved")
            {
                $appList = getGrantProposals(null, ["2", "3"], ["21"]);

            } elseif($filterType == "modifypending")
            {
                $appList = getGrantProposals(null, ["2", "3"], ["22"]);

            } elseif($filterType == "rejected")
            {
                $appList = getGrantProposals(null, ["2", "3"], ["23"]);
            }

        // Filter handler for Reviewer 2 (SAA)
        } elseif ($_SESSION["roleid"] == 4)
        {
            if($filterType == "default")
            {
                $appList = getGrantProposals(null, ["1"], ["11", "21", "22", "23"]);

            } elseif($filterType == "unattended")
            {
                $appList = getGrantProposals(null, ["1"], ["11"]);

            } elseif($filterType == "approved")
            {
                $appList = getGrantProposals(null, ["1"], ["21"]);

            } elseif($filterType == "modifypending")
            {
                $appList = getGrantProposals(null, ["1"], ["22"]);

            } elseif($filterType == "rejected")
            {
                $appList = getGrantProposals(null, ["1"], ["23"]);
            }

        // Filter handler for Reviewer 3 (ViceRectorIV)
        } elseif ($_SESSION["roleid"] == 5)
        {
            if($filterType == "default")
            {
                $appList = getGrantProposals(null, [], ["21", "31", "32", "33"]);

            } elseif($filterType == "unattended")
            {
                $appList = getGrantProposals(null, [], ["21"]);

            } elseif($filterType == "approved")
            {
                $appList = getGrantProposals(null, [], ["31"]);

            } elseif($filterType == "modifypending")
            {
                $appList = getGrantProposals(null, [], ["32"]);

            } elseif($filterType == "rejected")
            {
                $appList = getGrantProposals(null, [], ["33"]);
            }
        }

        // Store the result set in a session variable
        $_SESSION['result'] = $appList;
        
        header("Location: reviewerpage.php?filter=" . urlencode($filterType));
    }
?>