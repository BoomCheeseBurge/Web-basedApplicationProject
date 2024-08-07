<?php
	// Start Session
	session_start();
    
    // $pw = "david";
    // $hash = sha1($pw);
    // echo "<script>console.log('Hashed password: " . $hash . "' );</script>";

    // User-defined function that Works like include but allows to pass in an extra argument
    // includeWithVariables('layout/header.php', array('pageTitle' => 'home'));
    
    $pageTitle = "home";

    include('layout/header.php');
?>
    <!-- CONTENT SECTION -->
    <main>
        <?php if(isset($_SESSION["userid"])) : ?>
            <?php if($_SESSION["rolename"] == "CRCS" || $_SESSION["rolename"] == "SAA" || $_SESSION["rolename"] == "ViceRectorIV") : ?>
                    <div class="box1 position-absolute top-50 start-50 translate-middle">
                        <h1 class="home-title">
                            <span class="text1" data-text="Welcome back, Reviewer!">Welcome back, Reviewer!</span>
                        </h1>
                    </div>
            <?php elseif($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "FacultyMember") : ?>
                    <div class="box1 position-absolute top-50 start-50 translate-middle">
                        <h1 class="home-title">
                            <span class="text1" data-text="Apply yours today!">Apply yours today!</span>
                        </h1>
                        <div class="btn-style">
                            <a href="grantapplication.php">
                                <button type="button" class="apply-btn btn-bg">Apply Now</button>
                            </a>
                        </div>
                    </div>
            <?php elseif($_SESSION["rolename"] == "HoSP") : ?>
                    <div class="box1 position-absolute top-50 start-50 translate-middle">
                        <h1 class="home-title">
                            <span>Welcome back, Reviewer!</span>
                            <span>Apply yours today!</span>
                        </h1>
                        <div class="btn-style">
                            <a href="grantapplication.php">
                                <button type="button" class="apply-btn btn-bg">Apply Now</button>
                            </a>
                        </div>
                    </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="box1 position-absolute top-50 start-50 translate-middle">
                <h1 class="home-title">
                    <span class="text1" data-text="You are currently a guest.">You are currently a guest.</span>
                    <span class="text1" data-text="Log in to start!">Log in to start!</span>
                </h1>
            </div>
        <?php endif; ?>
    </main>
<?php include('layout/footer.php'); ?>