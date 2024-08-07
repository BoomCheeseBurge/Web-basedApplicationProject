<main class="form-bg position-absolute top-50 start-50 translate-middle">
    <div class="form-holder">
        <div id="form-title"><h2>Grant Registration</h2></div>
        <form action="grantapplication.php" enctype="multipart/form-data" method="post">
            <div class="application-details">
                <div class="text-box">
                    <label for="title">Research Title</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="nontext-box">
                    <label for="grantproposal">Grant Proposal</label>
                    <input type="file" name="grantproposal" id="grantproposal" accept=".pdf" required>
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
                <div class="nontext-box">
                    <label for="publicationtype">Publication Type</label>
                    <div class="select">
                        <select name="publicationtype" id="publicationtype">
                            <option selected disabled>Choose a Publication Type</option>
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
            </div>
            <div class="submit-button">
                <input name="submit" type="submit" value="Apply">
            </div>
        </form>
    </div>
</main>