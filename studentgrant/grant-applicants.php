<?php
	// Start Session
	session_start();
    
    // Check if the user has logged in
    if(!isset($_SESSION["userid"]) || $_SESSION["roleid"] == 1)
    {
        header("Location: index.php");
        exit;
    }

    $pageTitle = "applicant";

    include('layout/header.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $user_id = $_POST["userid"];
        $app_id = $_POST["appid"];

        if(assignProposal($user_id, $app_id) > 0)
        {
            echo "
                <script>
                    alert('Successfully assigned to proposal!');
                </script>
            ";
        } else
        {
            echo "
                <script>
                    alert('Failed to assign to proposal!);
                </script>
            ";
        }
        
        header("Location: grant-applicants.php");
    }

    $filter = "";

    // Check if there is a filter request by the user
    if(!empty($_GET["filter"])) {

        $filter = $_GET["filter"];

        // Retrieve the array of rows from the session variable
        $appList = $_SESSION['result'];
        
    }else{
        // Grant Proposals to be reviewed by Reviewer 1 (Faculty Member or HoSP)
        if ($_SESSION["roleid"] == 2 || $_SESSION["roleid"] == 3)
        {
            $appList = getGrantApplicants($_SESSION["userid"], [], ["0", "11", "12", "13"]);

        // Student(s)' Grant Proposals to be reviewed by Reviewer 2 (SAA)
        } else if ($_SESSION["roleid"] == 4)
        {
            $appList = getGrantApplicants(null, ['1'], ["11", "21", "22", "23"]);

        // Faculty Member(s)' Grant Proposals to be reviewed by Reviewer 2 (CRCS)
        }else if ($_SESSION["roleid"] == 6)
        {
            $appList = getGrantApplicants(null, ['2', '3'], ["11", "21", "22", "23"]);

        // Grant Proposals to be reviewed by Reviewer 3 (ViceRectorIV)
        } else if ($_SESSION["roleid"] == 5)
        {
            $appList = getGrantApplicants(null, [], ["21", "31", "32", "33"]);
        }
    }
?>

<main>
    <div class="table-wrapper">
        <?php if ($_SESSION["rolename"] == "HoSD" && isset($userappid)) : ?>
            <div class="app-name">You are now approving/reviewing as an HoSD level</div>
                <div class="bar-wrapper">

                </div>
        <?php elseif ($_SESSION["rolename"] == "SAA" && isset($userappid)) : ?>
            <section id="hero">
                <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
            </section>
        <?php elseif ($_SESSION["rolename"] == "Faculty CRCS" && isset($userappid)) : ?>
            <section id="hero">
                <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
            </section>
        <?php elseif ($_SESSION["rolename"] == "ViceRectorIV" && isset($userappid)) : ?>
            <section id="hero">
                <a href="grantapplication.php"><button class="applygrant">Apply Now</button></a>
            </section>
        <?php endif; ?>
        <div class="reviewer-container">
            <table class="table table-dark table-striped" style="caption-side: top;">
                <caption class="fs-4 text">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><h1>GRANT APPLICANTS</h1></span>
                        <span class="nontext-box">
                            <span class="badge text-bg-info" data-bs-toggle='tooltip' title='Reserved space for filter based on whether already assigned or proposal upload date or faculty'><i class="bi bi-exclamation-diamond-fill" style="color: white;"></i></span>
                        </span>
                    </div>
                </caption>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Faculty</th>
                        <th>PublicationType</th>
                        <th>Proposal File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($appList)) : ?>
                    <tr>
                        <td colspan='9' class='text-center'>No Data Found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($appList as $row) : ?>
                    <!-- Fetch the result as an associative array -->
                    <tr>
                        <td><?= $row["appdate"] ?></td>
                        <td><?= $row["firstname"] ?></td>
                        <td><?= $row["lastname"] ?></td>
                        <td><?= $row["faculty"] ?></td>
                        <td><?= $row["pubtype"] ?></td>
                        <td>
                            <a href='fileuploaded/<?= $row["proposalname"] ?>' target='_blank' class='text-decoration-none text-white proposalfile' data-bs-toggle='tooltip' title='Preview'>
                                <i class="bi bi-eye" style="font-size: 1.5rem; color: coral;"></i>
                                <i class="bi bi-eye-fill" style="font-size: 1.5rem; color: coral;"></i>
                            </a>
                        </td>
                        <!-- For applicants whose grant has been approved  -->
                        <?php if ($row["reviewer1id"] != 0) : ?>
                        <td>
                            <a href='#' class='btn btn-success disabled-link1'>Assigned</a>
                        </td>
                        <?php else : ?>
                        <td>
                            <button type="button" class="btn btn-success badge1" data-bs-toggle="modal" data-bs-userid="<?= $_SESSION["userid"] ?>" data-bs-appid="<?= $row['appid'] ?>" data-bs-target="#assignModal">
                            Assign
                            </button>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


<!-- ---------------------------------------------------------------- MODALS ---------------------------------------------------------------- -->
    <!-- Assign Proposal Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure?
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="post">
                        <input id="userID" type="hidden" name="userid" required>
                        <input id="appID" type="hidden" name="appid" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="assign" id="assign" style="cursor: pointer !important;">Yes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('layout/footer.php'); ?>
<script>
    function submitForm() {
        document.getElementById('filterForm').submit();
    }

    const assignModal = document.getElementById('assignModal');
    assignModal.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        const userID = button.getAttribute('data-bs-userid');
        const appID = button.getAttribute('data-bs-appid');

        // console.log(userID);
        // console.log(appID);

        // Update the modal's content.
        const userInput = document.getElementById('userID');
        const appInput = document.getElementById('appID');

        userInput.value = userID;
        appInput.value = appID;
    });
</script>