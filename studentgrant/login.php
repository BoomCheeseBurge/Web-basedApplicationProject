<?php
    include('db.php');
    include('myfunction.php');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // The form has been submitted, so we can access the $_POST array
        
        $email = $_POST["email"];
        $password = $_POST["password"];

        $result = loginVerification($db, $email, $password);
        $row = mysqli_fetch_object($result);
        var_dump($row);
        exit;
        $num_results = mysqli_num_rows($result);
        if($num_results == 1) {

            header("Location: studentpage.php");
        }else{
            $error[] = 'Incorrect email or password';
         }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Open Sans', sans-serif;
        }

        body {
            height: 100vh;
            background-image: url('/myweb/StudentGrant/IMG/kelly-sikkema_unsplash.jpg');
            /*Image used is credited to https://unsplash.com/photos/Nlax2tu89bU*/
            background-size: cover;
        }

        /* NAVBAR */
        header .index {
            width: 100%;
            font-size: 20px;
            color: #F9F1F0;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        header .index:hover {
            color: #F2CBF2;
        }

        header {
            width: 85%;
            height: 5em;
            background: #1e1f26;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            border-radius: 0 0 10px 10px;
            margin: 0 auto;
        }
        .title1 {
            width: 15em;
        }
        nav .title1 {
            display: none;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-right: 2em;
        }

        nav ul li a {
            color: #F9F1F0;
            display: block;
            margin: 0 2px;
            font-weight: 600;
            padding: 15px 18px;
            transition: 0.3s;
            border-radius: 30px;
            text-decoration: none;
        }
        nav ul li a:hover {
            color: #525655;
            background: #F9F1F0;
        }

        nav ul li a.active {
            color: #525655;
            background-color: #EED6D3;
        }
        nav ul li a.active:hover {
            background-color: #F9F1F0;
        }

        nav ul li .login-btn {
            width: 100px;
            height: 50px;
            background: transparent;
            border: 2px solid coral;
            outline: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1em;
            color: coral;
            font-weight: 500;
            margin-left: 40px;
            transition: 0.3s ease;
        }

        nav ul li .login-btn:hover {
            background: #F2CBF2;
            color: #525655;
        }

        /* nav ul li .loginpopup {
            width: 100px;
            height: 50px;
            background: transparent;
            border: 2px solid coral;
            outline: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1em;
            color: coral;
            font-weight: 500;
            margin-left: 40px;
            transition: 0.3s ease;
        }

        nav ul li .loginpopup:hover {
            background: #F2CBF2;
            color: #525655;
        } */

        .bars {
            display: none;
            height: fit-content;
            cursor: pointer;
            padding: 3px 8px;
            border-radius: 5px;
            transition: 0.2s;
        }
        .bars:hover {
            background: rgba(249, 241, 240, 0.05);
        }
        .bars .line {
            width: 30px;
            height: 2px;
            margin: 6px 0;
            background: #EED6D3;
        }

        /* HERO */
        section#hero {
            position: absolute;
            top: 210px;
            right: 245px;
            text-align: center;
            color: #F9F1F0;
            z-index: 1;
        }

        #hero h1 {
            font-size: 3rem;
        }

        @media only screen and (max-width: 850px) {
            header {
                width: 90%;
                padding: 0 20px;
            }
            nav {
                position: absolute;
                left: -300px;
                top: 0;
                z-index: 999;
                width: 280px;
                height: 100vh;
                background: #525655;
                transition: 0.2s;
                box-shadow: 2px 0 20px 0 rgba(249, 241, 240, 0.05);
            }
            #nav_check:checked ~ nav {
                left: 0;
            }
            nav .title1 {
                display: block;
                height: 70px;
                display: flex;
                align-items: center;
                margin-left: 30px
            }
            nav ul li a {
                margin-bottom: 5px;
                padding: 10px 15px;
                border-radius: 5px;
            }
            nav ul {
                display: block;
                padding: 0 20px;
                margin-top: 30px;
            }
            .bars {
                display: block;
            }
        }

        /* Login Form */
        .wrapper {
            position: relative;
            width: 25em;
            height: 27.5em;
            top: 5em;
            left: 40em;
            background: transparent;
            border: 2px solid #F9F1F0;
            border-radius: 20px;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            transition: height 0.2s ease;
        }

        .wrapper .active {
            height: 520px;
        }

        .wrapper .login-box {
            width: 100%;
            padding: 40px;
        }

        .login-box h2 {
            font-size: 2em;
            color: #1e1f26;
            text-align: center;
        }

        .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            border-bottom: 2px solid #1e1f26;
            margin: 30px 0;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            font-size: 1em;
            color: #1e1f26;
            font-weight: 500;
            pointer-events: none;
            transition: top 0.5s ease;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            color: #1e1f26;
            font-weight: 600;
        }

        .input-box .icon {
            position: absolute;
            right: 8px;
            font-size: 1.2em;
            color: #1e1f26;
            line-height: 57px;
        }

        .login-btn {
            width: 100%;
            height: 45px;
            background: #1e1f26;
            border: none;
            outline: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            color: #F2CBF2;
            font-weight: 500;
        }

        /* Incorrect Login */
        main .wrapper .login-box .error-msg{
            margin: 10px 0;
            background: crimson;
            color:#fff;
            border-radius: 5px;
            font-size: 18px;
            padding: 5px;
            display: block;
            text-align: center;
        }
    </style>

    <title>Grant Application</title>
</head>
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
                    <a href="index.html" class="active">Home</a>
                </li>
                <li>
                    <a href="">Grantees</a>
                </li>
                <li>
                    <a href="">About</a>
                </li>
                <li>
                    <a href="">Contact</a>
                </li>
            </ul>
        </nav>
        <label for="nav_check" class="bars">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </label>
    </header>
    <main>
        <div class="wrapper">
            <div class="login-box">
                <h2>Login</h2>
                <?php
                    if(isset($error)){
                        foreach($error as $error){
                            echo '<span class="error-msg">'.$error.'</span>';
                        };
                    };
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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
    </main>
</body>
</html>