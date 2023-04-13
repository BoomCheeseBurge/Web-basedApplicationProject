<?php
    // Start Session
    session_start();
    // Connect to the database
    include('db.php');
    // A function to verify the login input by the user
    include('myfunction.php');

    if(isset($_SESSION["userid"])) {
        if($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "Faculty Member") {
            header("Location: facultypage.php");
            exit;
        }else{
            header("Location: reviewerpage.php");
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // The form has been submitted, so we can access the $_POST array
        
        $email = $_POST["email"];
        $password = $_POST["password"];

        $result = loginVerification($db, $email, $password);
        $row = mysqli_fetch_object($result);
        $num_results = mysqli_num_rows($result);

        $_SESSION["userid"] = $row->userid;
        $_SESSION["firstname"] = $row->firstname;
        $_SESSION["lastname"] = $row->lastname;
        $_SESSION["faculty"] = $row->faculty;
        $_SESSION["email"] = $row->email;
        $_SESSION["roleid"] = $row->roleid;
        $_SESSION["rolename"] = $row->rolename;

        $role = $row->rolename;

        if($num_results == 1) {
            // If the user is already logged in, then the user is forbidden from accessing this login page again
            if(isset($_SESSION["userid"])) {
                if($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "Faculty Member") {
                    header("Location: facultypage.php");
                    exit;
                }else{
                    header("Location: reviewerpage.php");
                    exit;
                }//else
                // if() {
                //     header("Location: landingpage.php");
                //     exit;
                // }
            }

            header("Location: studentpage.php");
        }else{
            echo $num_results;
            $error[] = 'Incorrect email or password';
         }
    }
    include('header.html');
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
                    <a href="" class="active">Home</a>
                </li>
                <li>
                    <a href="" class="login-section">Grantees</a>
                </li>
                <li>
                    <a href="" class="login-section">About</a>
                </li>
                <li>
                    <a href="" class="login-section">Contact</a>
                </li>
                <li>
                    <a href="#showlogin"><button class="loginpopup">Login</button></a>
                    <?php
                        if(isset($error)){
                            echo "<div class='overlay overlay_target' id='showlogin'>";
                        }else{
                            echo "<div class='overlay' id='showlogin'>";
                        }
                    ?>
                        <div class="wrapper">
                            <span class="close-wrapper">
                                <a href="#">
                                    <i class="fa-solid fa-xmark" style="color: #5e77a1;"></i>
                                </a>
                            </span>
                    
                            <div class="login-box">
                                <h2>Login</h2>
                                <?php
                                    if(isset($error)){
                                        foreach($error as $error){
                                            echo '<span class="error-msg">'.$error.'</span>';
                                        };
                                    }
                                ?>
                                <form action="#" method="post">
                                    <div class="input-box">
                                        <span class="icon"><i class="fa-solid fa-envelope" style="color: #56698a;"></i></span>
                                        <input type="email" name="email" id="email" required>
                                        <label for="email">Email</label>
                                    </div>
                                    <div class="input-box">
                                        <span class="icon"><i class="fa-solid fa-lock" style="color: #506a95;"></i></span>
                                        <input type="password" name="password" id="password" required>
                                        <label for="password">Password</label>
                                    </div>
                                    <button type="submit" class="login-btn">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <label for="nav_check" class="bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </header>
<?php include('footer.html'); ?>