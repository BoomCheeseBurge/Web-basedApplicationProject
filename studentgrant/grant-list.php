<?php
	// Start Session
	session_start();
    
    // Check if the user has logged in
    if(!isset($_SESSION["userid"]) || $_SESSION["roleid"] != 1)
	{
        header('Location: index.php');
        exit; // Ignore all script below
    }

    $pageTitle = "proposal";

    include('layout/header.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if ($_POST["update"])
        {
            if(isset($_FILES['proposalFile']))
            {
                editProposal(true);
            } else
            {
                editProposal(false);
            }
        }
        elseif ($_POST["delete"])
        {
            deleteProposal();
        }
    }

    // Check if there is a filter request by the user
    if(!empty($_GET["filter"])) {

        $filter = $_GET["filter"];

        // Retrieve the array of rows from the session variable
        $appList = $_SESSION['result'];
        
    }else{

        // Retrieve grant Proposals of Student, Faculty Member, or HoSP
        $appList = getGrantList($_SESSION["userid"], [], []);
    }

    // Counter for table rows
    $i = 1;
?>

<main>
    <div class="reviewer-container">
            <table class="table table-dark table-striped" style="caption-side: top;">
                <caption class="fs-4 text">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><h1>GRANT APPLICANTS</h1></span>
                        <span class="nontext-box">
                            <a class="btn btn-info" href="grantapplication.php" role="button" style="color: white;"><i class="bi bi-plus-circle-fill" style="font-size: 1.2rem;"></i> Apply More!</a>
                        </span>
                    </div>
                </caption>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Proposal Title</th>
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
                        <td><?= $i; ?></td>
                        <td><?= $row["appdate"] ?></td>
                        <td><?= $row["proposalname"] ?></td>
                        <td><?= $row["pubtype"] ?></td>
                        <td>
                            <a href='fileuploaded/<?= $row["filename"] ?>' target='_blank' class='text-decoration-none text-white proposalfile' data-bs-toggle="tooltip" title="Preview">
                                <i class="bi bi-eye" style="font-size: 1.5rem; color: coral;"></i>
                                <i class="bi bi-eye-fill" style="font-size: 1.5rem; color: coral;"></i>
                            </a>
                        </td>
                         <td>
                            <a href='grant-progress.php?user_id="<?= $_SESSION["userid"] ?>"&app_id="<?= $row["appid"] ?>"' class='btn btn-success text-decoration-none badge1'>View</a>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning badge2" data-bs-toggle="modal" data-bs-target="#editModal" data-bs-oldfile="<?= $row['filename'] ?>" data-bs-name="<?= $row['proposalname'] ?>" data-bs-type="<?= $row['pubid'] ?>">
                            Edit
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger badge3" data-bs-toggle="modal" data-bs-file="<?= $row['filename'] ?>" data-bs-target="#deleteModal">
                            Delete
                            </button>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Proposal Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Grant Proposal</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="post">
                        <input id="oldFileInput" type="hidden" name="old_file" required>
                        <div class="row mb-3">
                            <label for="researchTitle" class="col-sm-2 col-form-label">Research Title</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="researchTitle">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="pubType" class="col-sm-2 col-form-label">Publication Type</label>
                            <div class="col-sm-10">
                            <select class="form-select" name="publicationtype" id="pubType">
                                <option disabled><b>CONFERENCE PUBLICATION</b></option>
                                <option value="1">Indexed-International Conference</option>
                                <option value="2">International Conference</option>
                                <option value="3">National Conference</option>
                                <option disabled><hr></option>
                                <option disabled>JOURNAL PUBLICATION</option>
                                <option value="4">Indexed-International Journal</option>
                                <option value="5">International Journal</option>
                                <option value="6">National Journal</option>
                            </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="proposalFile" class="col-sm-2 col-form-label">Proposal File</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="proposalFile" name="proposalFile" accept=".pdf">
                                <?php if(isset($_SESSION["filetypeerror"])) : ?>
                                    <span class="error-msg" style="margin: 10px 0;
                                    background: crimson;
                                    color:#fff;
                                    border-radius: 5px;
                                    font-size: 18px;
                                    padding: 5px;
                                    display: block;
                                    text-align: center;"><?= $_SESSION["filetypeerror"] ?></span>';
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button name="submit" type="submit" name="update" id="update" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Proposal Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure?
                </div>
                <div class="modal-footer">
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                        <input type="hidden" name="fileToDelete" id="fileToDelete">
                        <button type="submit" name="delete" id="delete" class='btn btn-primary' style="cursor: pointer !important;">Yes</button>
                    </form>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    const editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        const oldFile = button.getAttribute('data-bs-oldfile');
        const proposalName = button.getAttribute('data-bs-name');
        const pubType = button.getAttribute('data-bs-type');

        // console.log(oldFile);
        // console.log(proposalName);
        // console.log(pubType);
        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        const oldFileInput = document.getElementById('oldFileInput');
        const nameInput = document.getElementById('researchTitle');
        const typeInput = document.getElementById('pubType');

        oldFileInput.value = oldFile;
        nameInput.value = proposalName;
        typeInput.value = pubType;
    });

    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', event => {
        // Button that triggered the modal
        const button = event.relatedTarget;
        // Extract info from data-bs-* attributes
        const deleteFile = button.getAttribute('data-bs-file');

        // console.log(deleteFile);

        // If necessary, you could initiate an AJAX request here
        // and then do the updating in a callback.
        //
        // Update the modal's content.
        const deleteFileInput = document.getElementById('fileToDelete');

        deleteFileInput.value = deleteFile;
    });
</script>

<?php include('layout/footer.php'); ?>