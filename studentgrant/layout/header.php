<?php
	// Connect to the database
	include('db.php');

	// A function to verify the login input by the user
	include('myfunction.php');

	if(!isset($_SESSION["userid"])) {
		// Handles the log in form if the user has submitted the log in form
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			// The form has been submitted, so we can access the $_POST array
			$email = htmlspecialchars($_POST["email"]);
			$password = htmlspecialchars($_POST["password"]);

			$result = loginVerification($db, $email, $password);
			$row = mysqli_fetch_object($result);
			$num_results = mysqli_num_rows($result);

			if($num_results == 1) {
				$_SESSION["userid"] = $row->userid;
				$_SESSION["firstname"] = $row->firstname;
				$_SESSION["lastname"] = $row->lastname;
				$_SESSION["faculty"] = $row->faculty;
				$_SESSION["email"] = $row->email;
				$_SESSION["roleid"] = $row->roleid;
				$_SESSION["rolename"] = $row->rolename;

				$role = $row->rolename;

				header("Location: index.php");
			}else{
				echo $num_results;
				$error[] = 'Incorrect email or password';
			}
		}
	}

	if(isset($_SESSION["userid"])) {

		$userid = $_SESSION["userid"];
		// Find if the user has any submitted application
		$query_userappid = "SELECT userid AS userappid FROM userapplication WHERE userid = $userid LIMIT 1";
		$result = mysqli_query($db, $query_userappid);

		// Fetch the result as an associative array
		$row = mysqli_fetch_assoc($result);
		$row_num = mysqli_num_rows($result);

		// Get the value of the maxid column and increment by 1
		@ $userappid = $row['userappid'];
	}

?>

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
	
			<link rel="stylesheet" href="CSS/style.css">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
			<link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.css">
			<link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap-grid.min.css">
			<link rel="stylesheet" href="bootstrap-5.2.3-dist/css/bootstrap.css">
			<script src="bootstrap-5.2.3-dist/js/popper.min.js"></script>
			<script src="bootstrap-5.2.3-dist/js/bootstrap.bundle.js.map"></script>
			<script src="bootstrap-5.2.3-dist/js/bootstrap.bundle.js"></script>
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

			<title>Publication Grant</title>
		</head>
		<body>
			<?php if(!isset($_SESSION["userid"])) : ?>
				<div class="overlay" id="showLogin">
					<div class="wrapper">
						<span class="close-wrapper">
							<a href="#closeMessage">
								<i class="fa-solid fa-xmark" style="color: #5e77a1;"></i>
							</a>
						</span>
						<div class="login-box">
							<h2>Login</h2>
								<?php if(isset($error)) : ?>
									<?php foreach($error as $error) : ?>
										<div class="alert alert-warning alert-dismissible fade show error-msg" role="alert">
										<?= $error ?>
										<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							<form action="#" method="post">
								<div class="input-box">
									<span class="icon"><i class="fa-solid fa-envelope"></i></span>
									<input type="email" name="email" id="email" required>
									<label for="email">Email</label>
								</div>
								<div class="input-box">
									<span class="icon"><i class="fa-solid fa-lock"></i></span>
									<input type="password" name="password" id="password" required>
									<label for="password">Password</label>
								</div>
								<button type="submit" class="login-btn">Login</button>
							</form>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<!-- NAVBAR SECTION -->
			<header>
				<nav>
					<ul class="sidebar">
						<li onclick="hideSidebar()">
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#ffff"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a>
						</li>
						<?php if(isset($_SESSION["userid"])) : ?>
							<?php if($_SESSION["rolename"] == "FacultyMember" || $_SESSION["rolename"] == "HoSP") : ?>
								<li class="header-nav">
									<a class="nav-link dropdown-toggle login-section <?= ($pageTitle === "review") ? "active": ""; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										Dashboard
									</a>
									<ul class="dropdown-menu dropdown-menu-dark">
										<li>
											<a href="grant-applicants.php" class="dropdown-item">Grant Applicant</a>
										</li>
										<li><hr class="dropdown-divider"></li>
										<li>
											<a href="reviewerpage.php" class="dropdown-item">Grant Review</a>
										</li>
									</ul>
								</li>
							<?php elseif($_SESSION["rolename"] == "CRCS" || $_SESSION["rolename"] == "SAA" || $_SESSION["rolename"] == "ViceRectorIV") : ?>
									<li class="header-nav">
										<a href="reviewerpage.php" class="login-section <?= ($pageTitle === "review") ? "active": ""; ?>">Dashboard</a>
									</li>
							<?php endif; ?>
						<?php endif; ?>
						<li class="header-nav">
							<a href="index.php" class="login-section <?= ($pageTitle === "home") ? "active": ""; ?>">Home</a>
						</li>
						<?php if(isset($userappid)) : ?>
							<?php if($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "FacultyMember" || $_SESSION["rolename"] == "HoSP") : ?>
								<li class="header-nav">
									<a href="grant-list.php" class="login-section <?= ($pageTitle === "proposal") ? "active": ""; ?>">Proposal</a>
								</li>
							<?php endif; ?>
						<?php endif; ?>
						<li class="header-nav">
							<a href="#" class="login-section <?= ($pageTitle === "about") ? "active": ""; ?>">About</a>
						</li>
						<li class="header-nav">
							<a href="#" class="login-section <?= ($pageTitle === "contact") ? "active": ""; ?>">Contact</a>
						</li>
						<?php if(isset($_SESSION["userid"])) : ?>
							<li class="header-nav">
								<a href="#"><button class="myuser">Greetings, <?= $_SESSION["firstname"] ?>!</button></a><br>
								<a href="logout.php"><button class="logout">Logout</button></a>
							</li>
						<?php else : ?>
							<li class="header-nav">
								<a href="#showLogin"><button class="loginpopup">Login</button></a>
							</li>
						<?php endif; ?>
					</ul>
					<!-- ------------------------------------------------------------------------------------------------------- -->
					<ul class="menu-bar">
						<li>
							<a href="index.php" class="index">Research Paper Grant</a>
						</li>
						<?php if(isset($_SESSION["userid"])) : ?>
							<?php if($_SESSION["rolename"] == "FacultyMember" || $_SESSION["rolename"] == "HoSP") : ?>
								<li class="header-nav">
									<a class="nav-link dropdown-toggle login-section hideOnMobile <?= ($pageTitle === "review") ? "active": ""; ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										Dashboard
									</a>
									<ul class="dropdown-menu dropdown-menu-dark">
										<li>
											<a href="grant-applicants.php" class="dropdown-item">Grant Applicant</a>
										</li>
										<li><hr class="dropdown-divider"></li>
										<li>
											<a href="reviewerpage.php" class="dropdown-item">Grant Review</a>
										</li>
									</ul>
								</li>
							<?php elseif($_SESSION["rolename"] == "CRCS" || $_SESSION["rolename"] == "SAA" || $_SESSION["rolename"] == "ViceRectorIV") : ?>
									<li class="header-nav">
										<a href="reviewerpage.php" class="login-section <?= ($pageTitle === "review") ? "active": ""; ?>">Dashboard</a>
									</li>
							<?php endif; ?>
						<?php endif; ?>
						<li class="header-nav">
							<a href="index.php" class="login-section hideOnMobile <?= ($pageTitle === "home") ? "active": ""; ?>">Home</a>
						</li>
						<?php if(isset($userappid)) : ?>
							<?php if($_SESSION["rolename"] == "Student" || $_SESSION["rolename"] == "FacultyMember" || $_SESSION["rolename"] == "HoSP") : ?>
								<li class="header-nav">
									<a href="grant-list.php" class="login-section hideOnMobile <?= ($pageTitle === "proposal") ? "active": ""; ?>">Proposal</a>
								</li>
							<?php endif; ?>
						<?php endif; ?>
						<li class="header-nav">
							<a href="#" class="login-section hideOnMobile <?= ($pageTitle === "about") ? "active": ""; ?>">About</a>
						</li>
						<li class="header-nav">
							<a href="#" class="login-section hideOnMobile <?= ($pageTitle === "contact") ? "active": ""; ?>">Contact</a>
						</li>
						<?php if(isset($_SESSION["userid"])) : ?>
							<li class="header-nav">
								<a href="#"><button class="myuser hideOnMobile">Greetings, <?= $_SESSION["firstname"] ?>!</button></a><br>
								<a href="logout.php"><button class="logout hideOnMobile">Logout</button></a>
							</li>
						<?php else : ?>
							<li class="header-nav">
								<a href="#showLogin"><button class="loginpopup hideOnMobile">Login</button></a>
							</li>
						<?php endif; ?>
						<li class="menu-button" onclick="showSidebar()">
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#ffff"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a>
						</li>
					</ul>
				</nav>
			</header>