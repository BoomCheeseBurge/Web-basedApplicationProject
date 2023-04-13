<?php
    session_start(); 
    include('header.html');

    if(!isset($_SESSION["userid"])) {
        header("Location: index.php");
        exit;
    }
    $userid = $_SESSION["userid"];
    include('db.php');

    // Find if the user has any submitted application
    $query_userappid = "SELECT userid AS userappid FROM userapplication WHERE userid = '$userid'";
    $result = mysqli_query($db, $query_userappid);

    // Fetch the result as an associative array
    $row = mysqli_fetch_assoc($result);
    $row_num = mysqli_num_rows($result);

    // Get the value of the maxid column and increment by 1
    @ $userappid = $row['userappid'];

    if(isset($userappid)) {
        // Find if the application status of the user
        $query_appstatus = "SELECT a.grantproposal AS proposalname, pt.publicationtype AS pubtype, a.reviewerstatus AS revstat FROM app AS a
                            JOIN userapplication AS ua ON a.id = ua.applicationid
                            JOIN publicationtype AS pt ON a.publicationtypeid = pt.id 
                            WHERE ua.userid =  $userid";

        // function loginVerification($db, $email, $pw) {
        //     $hash = sha1($pw);
        //     $query = "SELECT u.id AS userid, u.firstname, u.lastname, u.faculty, u.email, ur.roleid, r.rolename
        //             FROM user AS u 
        //             JOIN userrole AS ur ON u.id = ur.userid
        //             JOIN role AS r ON ur.roleid = r.id
        //             WHERE email = '$email' AND passkey = '$hash'";
        //     return mysqli_query($db, $query);
        // }

        $result2 = mysqli_query($db, $query_appstatus);

        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($result2);

        // Get the value of the maxid column and increment by 1
        $pubtype = $row['pubtype'];
        $revstat = $row['revstat'];
        $proposalname = $row['proposalname'];

        // Reupload button stays hidden
        $hideButton = true;

        if($revstat == 0) {
            $reviewbar1 = 0;
            $reviewbar2 = 0;
            $reviewbar3 = 0;
        }else if($revstat == 11) {
            $reviewbar1 = 50;
            $reviewbar2 = 0;
            $reviewbar3 = 0;

            $hideButton = false;

        }else if($revstat == 12) {
            $reviewbar1 = 100;
            $reviewbar2 = 0;
            $reviewbar3 = 0;
            
        }else if($revstat == 13) {
            $reviewbar1 = 0;
            $reviewbar2 = 0;
            $reviewbar3 = 0;
            
        }else if($revstat == 21) {
            $reviewbar1 = 100;
            $reviewbar2 = 50;
            $reviewbar3 = 0;

            $hideButton = false;
            
        }else if($revstat == 22) {
            $reviewbar1 = 100;
            $reviewbar2 = 100;
            $reviewbar3 = 0;
            
        }else if($revstat == 23) {
            $reviewbar1 = 0;
            $reviewbar2 = 0;
            $reviewbar3 = 0;
            
        }else if($revstat == 31) {
            $reviewbar1 = 100;
            $reviewbar2 = 100;
            $reviewbar3 = 50;

            $hideButton = false;

        }else if($revstat == 32) {
            $reviewbar1 = 100;
            $reviewbar2 = 100;
            $reviewbar3 = 100;
        }else if($revstat == 33) {
            $reviewbar1 = 0;
            $reviewbar2 = 0;
            $reviewbar3 = 0;
        }
    }

    $notification = "A message will been sent to your email regarding your grant application.";
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
    <main class="facultypage">
        <?php
            if($_SESSION["rolename"] == "Student" && isset($userappid) && $row_num != 0) {
                echo '<div class="app-name">Application File Name: '.$proposalname.'</div>
                <div class="pub-type">Publication Type: '.$pubtype.'</div>';
                echo '
                <div class="bar-wrapper">
                    <h2 class="bar-title">Paper Application Progress</h2>

                    <div class="skill">
                        <p>Reviewer 1</p>
                        <div class="skill-bar skill1" style="width:'.$reviewbar1.'%;">
                            <span class="skill-count1">'.$reviewbar1.'%</span>
                        </div>
                    </div>
                    <div class="skill">
                        <p>Reviewer 2</p>
                        <div class="skill-bar skill2" style="width:'.$reviewbar2.'%;">
                            <span class="skill-count2">'.$reviewbar2.'%</span>
                        </div>
                    </div>
                    <div class="skill">
                        <p>Reviewer 3</p>
                        <div class="skill-bar skill3" style="width:'.$reviewbar3.'%;">
                            <span class="skill-count3">'.$reviewbar3.'%</span>
                        </div>
                    </div>
                </div>';
                if(!$hideButton) {
                    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">
                        <fieldset>
                            <label for="reupload">Reupload Grant Proposal:</label>
                            <input type="file" name="reupload" id="reupload" accept=".pdf" required>
                        </fieldset>
                    </form>';
                }
            }else
            if($_SESSION["rolename"] == "Student" && !isset($userappid) && $row_num == 0) {
                echo '<section id="hero">
                    <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>';
            }else
            if($_SESSION["rolename"] == "Faculty Member" && isset($userappid) && $row_num != 0) {
                echo '<section id="hero">
                <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>';
            }else
            if($_SESSION["rolename"] == "Faculty Member" && !isset($userappid) && $row_num == 0) {
                echo '<section id="hero">
                    <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>';
            }
        ?>
    </main>
<?php
    include('footer.html');
?>