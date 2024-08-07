<?php
	// Start Session
	session_start();
    
    // Check if the user has logged in
    if(!isset($_SESSION["userid"]))
	{
        header('Location: index.php');
        exit; // Ignore all script below
    }

    // Get the ID of the grant proposal and user
	$app_id = $_GET["app_id"];
    $user_id = $_SESSION["userid"];

    $pageTitle = "progress";

    include('layout/header.php');

    // Handles the file reupload form if the user is requested to revise their grant proposal file
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the file was uploaded
        if (isset($_FILES['revisedFile']))
        {
            $file_type = $_FILES['revisedFile']['type'];

            echo "<script>console.log('$file_type');</script>";
            
            // Check if the file is a PDF
            if ($file_type == 'application/pdf')
            {
                $file_name = $_FILES['revisedFile']['name'];
                $file_temp = $_FILES['revisedFile']['tmp_name'];
                $old_file = $_POST['old_file'];

                if(reviseProposal($file_name, $file_temp, $old_file, $app_id) > 0)
                {
                    echo "
                        <script>
                            alert('Successfully updated proposal!');
                        </scrip>
                    ";
                } else
                {
                    echo "
                        <script>
                            alert('Failed to update proposal!);
                        </script>
                    ";
                }
            }
            else
            {
                // Display an error message
                $_SESSION["filetypeerror"] = "Only PDF files are allowed.";
                header("Location: grant-progress.php");
            }
        }
    }

    // Retrieve the grant proposal information
    $row = getGrantProposal($app_id, $user_id);
    // Get the value of the publication type, reviewerstatus, and the proposal file's name
    $pubtype = $row['pubtype'];
    $revstat = $row['revstat'];
    $reqmodify = $row['reqmodify'];
    $title = $row['title'];

    // Get the status of this grant proposal progress
    $grant_stat = checkReviewerStatus($revstat, $reqmodify);

    // $notification = "A message will been sent to your email regarding your grant application.";
?>
<main>
    <div class="grant-progress">
        <?php if(($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "FacultyMember" || $_SESSION["rolename"] == "HoSP") && !empty($grant_stat)) : ?>
            <div class="bar-wrapper">
                <div class="button-wrapper">
                    <a class="btn btn-primary btn-lg" href="grant-list.php" role="button" style="cursor: pointer !important;"><i class="bi bi-arrow-left-circle-fill"></i> Back</a>
                    <?php if(!$grant_stat["hideButton"]) : ?>
                        <button type="button" class="btn btn-warning badge2" data-bs-toggle="modal" data-bs-target="#reviseModal">
                        Revise
                        </button>
                    <?php endif; ?>
                </div>
                <h5 class="app-name">Research Title: <b><?= $title ?></b></h5>
                <h5 class="pub-type">Publication Type: <b><?= $pubtype ?></b></h5>
                <h2 class="bar-title">Grant Application Progress</h2>
                <div class="skill">
                    <p>Reviewer 1</p>
                    <div class="skill-bar skill1" style="width:<?= $grant_stat['reviewerbar1'] ?>%; background: #<?= $grant_stat['barcolor1'] ?>;">
                        <span class="skill-count1"><?= $grant_stat["reviewerbar1"] ?>%</span>
                    </div>
                </div>
                <div class="skill">
                    <p>Reviewer 2</p>
                    <div class="skill-bar skill2" style="width:<?= $grant_stat['reviewerbar2'] ?>%; background: #<?= $grant_stat['barcolor2'] ?>;">
                        <span class="skill-count2"><?= $grant_stat['reviewerbar2'] ?>%</span>
                    </div>
                </div>
                <div class="skill">
                    <p>Reviewer 3</p>
                    <div class="skill-bar skill3" style="width:<?= $grant_stat['reviewerbar3'] ?>%; background: #<?= $grant_stat['barcolor3']?>;">
                        <span class="skill-count3"><?= $grant_stat['reviewerbar3'] ?>%</span>
                    </div>
                </div>
            </div>
            <?php if(!$grant_stat["hideButton"]) : ?>
                <!-- Revise Proposal Modal -->
                <div class="modal fade" id="reviseModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reviseModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="reviseModalLabel">Revise Grant Proposal</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="post">
                                    <input id="oldFileInput" type="hidden" name="old_file" value="<?= $row['fileTitle'] ?>" required>
                                    <div class="row mb-3">
                                        <label for="revisedFile" class="col-sm-2 col-form-label">Revised Proposal</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" id="revisedFile" name="revisedFile" accept=".pdf" required>
                                            <?php if(isset($_SESSION["filetypeerror"])) : ?>
                                                <span class="error-msg" style="margin: 10px 0;
                                                background: crimson;
                                                color:#fff;
                                                border-radius: 5px;
                                                font-size: 18px;
                                                padding: 5px;
                                                display: block;
                                                text-align: center;"><?= $_SESSION["filetypeerror"] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button name="submit" type="submit" name="revise" id="revise" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php elseif(($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "FacultyMember" || $_SESSION["rolename"] == "HoSP") && empty($grant_stat)) : ?>
                <section id="hero">
                    <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
                </section>
        <?php endif; ?>
    </div>
</main>

<?php include('layout/footer.php'); ?>