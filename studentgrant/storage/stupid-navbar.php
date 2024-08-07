<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            list-style: none;
            text-decoration: none;
            box-sizing: border-box;
        }

        body {
            background-color:lavender;
        }

        header {
            width: 100%;
            height: 80px;
            background-color:honeydew;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 100px;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            color: lavender;
        }

        .hamburger {
            display: none;
        }

        .nav-bar ul {
            display: flex;
        }

        .nav-bar ul li a {
            display: block;
            color: cadetblue;
            font-size: 20px;
            padding: 10px 25px;
            border-radius: 50px;
            transition: 0.2s;
            margin: 0 5px;
        }

        .nav-bar ul li a:hover {
            color:darkslateblue;
            background-color:dodgerblue;
        }

        .nav-bar ul li a.active {
            color:darkslateblue;
            background-color:dodgerblue;
        }

        @media only screen and (max-width: 1320px)
        {
            header {
                padding: 0 50px;
            }
        }
        @media only screen and (max-width: 1100px)
        {
            header {
                padding: 0 30px;
            }
        }
        @media only screen and (max-width: 900px)
        {
            .hamburger {
                display: block;
                cursor: pointer;
            }
            .hamburger .line {
                width: 30px;
                height: 3px;
                background-color:burlywood;
                margin: 6px 0;
            }
            .hamburger.active .line:nth-child(2){
                opacity: 0;
            }
            .hamburger.active .line:nth-child(1){
                transform: translateY(8px) rotate(-45deg);
            }
            .hamburger.active .line:nth-child(3){
                transform: translateY(-8px) rotate(45deg);
            }
            .nav-bar {
                height: 0px;
                position: absolute;
                top: 80px;
                left: 0;
                right: 0;
                width: 100vw;
                background-color: darkseagreen;
                transition: 0.2s;
                overflow: hidden;
            }
            .nav-bar.active {
                height: 450px;
            }
            .nav-bar ul {
                display: block;
                width: fit-content;
                margin: 80px auto 0 auto;
                text-align: center;
                transition: 0.5s;
                opacity: 0;
            }
            .nav-bar.active ul {
                opacity: 1;
            }
            .nav-bar ul li a {
                margin-bottom: 12px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">LOGO</div>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="#" class="active">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Contract</a></li>
            </ul>
        </nav>
    </header>
    
    <script>
        hamburger = document.querySelector('.hamburger');
        hamburger.onclick = function() {
            navBar = document.querySelector('.nav-bar');
            navBar.classList.toggle('active');
            hamburger = document.querySelector(".hamburger");
            hamburger.classList.toggle("active");
        }
    </script>
</body>
</html>