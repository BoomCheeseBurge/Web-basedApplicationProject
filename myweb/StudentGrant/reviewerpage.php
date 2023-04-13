<?php
    session_start();
    include('db.php');
    include('myfunction.php'); 
    include('header.html');
    
    if(!isset($_SESSION["userid"])) {
        header("Location: index.php");
        exit;
    }

        if($_SESSION["roleid"] == 2 || $_SESSION["roleid"] == 3) {
            // Query the user applications
            $query_appstatus = "SELECT u.id AS userid , u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.publicationtype AS pubtype, a.reviewerstatus AS revstat, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id 
            WHERE a.reviewerstatus =  0 OR a.reviewerstatus = 11";

            $result = mysqli_query($db, $query_appstatus);
            $row_number = mysqli_num_rows($result);

        }else if($_SESSION["roleid"] == 4 || $_SESSION["roleid"] == 6) {
            // Query the user applications
            $query_appstatus = "SELECT u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.publicationtype AS pubtype, a.reviewerstatus AS revstat, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id 
            WHERE a.reviewerstatus =  21";

            $result = mysqli_query($db, $query_appstatus);
            $row_number = mysqli_num_rows($result);
        }else if($_SESSION["roleid"] == 5) {
            // Query the user applications
            $query_appstatus = "SELECT u.firstname, u.lastname, u.faculty, a.grantproposal AS proposalname, pt.publicationtype AS pubtype, a.reviewerstatus AS revstat, a.applicationdate AS appdate FROM app AS a
            JOIN userapplication AS ua ON a.id = ua.applicationid
            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id
            JOIN user AS u ON ua.userid = u.id 
            WHERE a.reviewerstatus =  31";

            $result = mysqli_query($db, $query_appstatus);
            $row_number = mysqli_num_rows($result);
        }
?>

<body>
    <header>
        <div class="title1">
            <a href="#" class="index">Research Paper Grant</a>
        </div>
        <input type="checkbox" id="nav_check" hidden>
        <nav>
            <div class="title1">
                <h1>Research Paper Grant</h1>
            </div>
            <ul>
                <li>
                    <a href="" class="user-section">Home</a>
                </li>
                <li>
                    <a href="" class="user-section">Grantees</a>
                </li>
                <li>
                    <a href="" class="user-section">About</a>
                </li>
                <li>
                    <a href="" class="user-section">Contact</a>
                </li>
                <li>
                    <a href="#"><button class="myuser">Hello, <?php echo($_SESSION["firstname"]); ?>!</button></a><br>
                    <a href="logout.php"><button class="logout">Logout</button></a>
                </li>
            </ul>
        </nav>
        <label for="nav_check" class="bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </header>
    <main>
        <?php
            if($_SESSION["rolename"] == "HoSD" && isset($userappid)) {
                echo '<div class="app-name">You are now approving/reviewing as an HoSD level</div>';
                echo '
                <div class="bar-wrapper">

                </div>';
            }else
            if($_SESSION["rolename"] == "SAA" && isset($userappid)) {
                echo '<section id="hero">
                    <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>';
            }else
            if($_SESSION["rolename"] == "Faculty CRCS" && isset($userappid)) {
                echo '<section id="hero">
                <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>';
            }else
            if($_SESSION["rolename"] == "ViceRectorIV" && isset($userappid)) {
                echo '<section id="hero">
                    <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>';
            }
        ?>
    </main>
    <table>
        <thead>
            <tr>
            <th>Date</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Faculty</th>
            <th>PublicationType</th>
            <th>ProposalFile</th>
            <th>Accept</th>
            <th>Modify</th>
            <th>Reject</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for ($i=0; $i<$row_number; $i++){
                // Fetch the result as an associative array
                $row = mysqli_fetch_assoc($result);
                echo "<tr>
                <td>".$row["appdate"]."</td>
                <td>".$row["firstname"]."</td>
                <td>".$row["lastname"]."</td>
                <td>".$row["faculty"]."</td>
                <td>".$row["pubtype"]."</td>
                <td>".$row["proposalname"]."</td>
                <td><a href='/myweb/StudentGrant/fileuploaded/".$row["proposalname"]."' target='_blank'>ViewProposal</a></td>
                <td><a href='reviewerbtn.php?userid=".$row["userid"]."&status=accept'>Accept</a></td>
                <td><a href='reviewerbtn.php?userid=".$row["userid"]."&status=modify'>Modify</a></td>
                <td><a href='reviewerbtn.php?userid=".$row["userid"]."&status=reject'>Reject</a></td>
                </tr>";
            }
        echo "</tbody>
    </table>";
    include('footer.html');
?>