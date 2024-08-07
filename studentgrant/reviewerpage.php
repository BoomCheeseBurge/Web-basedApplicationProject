<?php
	// Start Session
	session_start();
    
    // Check if the user has logged in
    if(!isset($_SESSION["userid"]) || $_SESSION["roleid"] == 1)
    {
        header("Location: index.php");
        exit;
    }

    $pageTitle = "review";

    include('layout/header.php');

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
            $appList = getGrantProposals($_SESSION["userid"], [], ["0", "11", "12", "13"], true);

        // Student(s)' Grant Proposals to be reviewed by Reviewer 2 (SAA)
        } else if ($_SESSION["roleid"] == 4)
        {
            $appList = getGrantProposals(null, ['1'], ["11", "21", "22", "23"], false);

        // Faculty Member(s)' Grant Proposals to be reviewed by Reviewer 2 (CRCS)
        }else if ($_SESSION["roleid"] == 6)
        {
            $appList = getGrantProposals(null, ['2', '3'], ["11", "21", "22", "23"], false);

        // Grant Proposals to be reviewed by Reviewer 3 (ViceRectorIV)
        } else if ($_SESSION["roleid"] == 5)
        {
            $appList = getGrantProposals(null, [], ["21", "31", "32", "33"], false);
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
                            <form action="filter.php" method="post" id="filterForm">
                                <div class="select">
                                    <select name="filterType" onchange="submitForm()">
                                        <option class="text-center" <?= (empty($filter)) ? "selected" : "" ?> disabled>Filter</option>
                                        <option class="text-center" value="default" <?= ($filter == "default") ? "selected" : "" ?>>None</option>
                                        <option class="text-center" value="unattended" <?= ($filter == "unattended") ? "selected" : "" ?>>Unattended</option>
                                        <option class="text-center" value="approved" <?= ($filter == "approved") ? "selected" : "" ?>>Approved</option>
                                        <option class="text-center" value="modifypending" <?= ($filter == "modifypending") ? "selected" : "" ?>>ModifyPending</option>
                                        <option class="text-center" value="rejected" <?= ($filter == "rejected") ? "selected" : "" ?>>Rejected</option>
                                    </select>
                                </div>
                            </form>
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
                        <th colspan="3">Actions</th>
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
                        <?php if ($row["revstat"] == 11 || $row["revstat"] == 21 || $row["revstat"] == 31) : ?>
                        <td>
                            <a href='#' class='btn btn-success' data-bs-toggle='tooltip' title='You have given approval of this grant'>Approved</a>
                        </td>
                        <td>
                            <a href='#' class='btn btn-primary text-decoration-none disabled-link2'>Modify</a>
                        </td>
                        <td>
                            <a href='#' class='btn btn-danger text-decoration-none disabled-link3'>Reject</a>
                        </td>
                        <!-- For applicants whose application needs to be modified -->
                        <?php elseif (($row["revstat"] == 12 || $row["revstat"] == 22 || $row["revstat"] == 32) && $row["reqmodify"] == 1) : ?>
                        <td>
                            <a href='#' class='btn btn-success text-decoration-none disabled-link1'>Accept</a>
                        </td>
                        <td>
                            <button class='btn btn-primary' data-bs-toggle='tooltip' title='Waiting for the applicant to resubmit the proposal file'>Pending</button>
                        </td>
                        <td>
                            <a href='#' class='btn btn-danger text-decoration-none disabled-link3'>Reject</a>
                        </td>
                        <!-- For applicants whose grant has been rejected -->
                        <?php elseif ($row["revstat"] == 13 || $row["revstat"] == 23 || $row["revstat"] == 33) : ?>
                        <td>
                            <a href='#' class='btn btn-success text-decoration-none disabled-link1'>Accept</a>
                        </td>
                        <td>
                            <a href='#' class='btn btn-primary text-decoration-none disabled-link2'>Modify</a>
                        </td>
                        <td>
                            <a href='#' class='btn btn-danger' data-bs-toggle='tooltip' title='You have rejected this grant'>Rejected</a>
                        </td>
                        <!-- For applicants whose grant has yet to be approved, request for modification, or rejected -->
                        <?php else : ?>
                        <td>
                            <button type="button" class="btn btn-success badge1" data-bs-toggle="modal" data-bs-target="#acceptModal">
                            Accept
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning badge2" data-bs-toggle="modal" data-bs-target="#modifyModal">
                            Modify
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger badge3" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Reject
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
    <!-- Accept Proposal Modal -->
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <a href='updateprogress.php?userid="<?= $row["userid"] ?>"&status=accept' class='btn btn-primary' style="cursor: pointer !important;">Yes</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Request Modify Proposal Modal -->
    <div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <a href='updateprogress.php?userid="<?= $row["userid"] ?>"&status=modify' class='btn btn-primary' style="cursor: pointer !important;">Yes</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Reject Proposal Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <a href='updateprogress.php?userid="<?= $row["userid"] ?>"&status=reject' class='btn btn-primary' style="cursor: pointer !important;">Yes</a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('layout/footer.php'); ?>
<script>
    const tooltipTrigger = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipContent = [...tooltipTrigger].map(tooltipE1 => new bootstrap.Tooltip(tooltipE1));

    function submitForm() {
        document.getElementById('filterForm').submit();
    }
</script>