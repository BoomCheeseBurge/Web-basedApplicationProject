<body class="form">
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
    <div class="background">
        <div class="container">
            <div class="title2"><h2>Registration</h2></div>
            <form action="grantapplication.php" enctype="multipart/form-data" method="post">
                <div class="application-details">
                    <div class="text-box">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" placeholder="Enter first-name here" required>
                    </div>
                    <div class="text-box">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Enter last-name here" required>
                    </div>
                    <div class="nontext-box">
                        <label for="grantproposal">Grant Proposal</label>
                        <input type="file" name="grantproposal" id="grantproposal" accept=".pdf" required>
                        <?php
                            if(isset($_SESSION["filetypeerror"])){
                                echo '<span class="error-msg" style="margin: 10px 0;
                                background: crimson;
                                color:#fff;
                                border-radius: 5px;
                                font-size: 18px;
                                padding: 5px;
                                display: block;
                                text-align: center;">'.$_SESSION["filetypeerror"].'</span>';
                            }
                        ?>
                    </div>
                    <div class="nontext-box">
                        <label for="publicationtype">Publication Type</label>
                        <div class="select">
                            <select name="publicationtype" id="publicationtype">
                                <option selected disabled>Choose a Publication Type</option>
                                <option value="1">Indexed-International Conference</option>
                                <option value="2">International Conference</option>
                                <option value="3">National Conference</option>
                                <option disabled>---------------------------------------------</option>
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
    </div>
