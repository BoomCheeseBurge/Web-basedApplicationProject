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
        }

        body {
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color:lightblue;
        }

        nav {
            background-color: azure;
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);
        }

        nav ul {
            width: 100%;
            list-style: none;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        /* List items defines the height of the parent */
        nav li {
            height: 50px;
        }

        nav a {
            height: 100%;
            padding: 0 30px;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            color: black;
        }

        nav a:hover {
            background-color: #f0f0f0;
        }

        nav li:first-child {
            margin-right: auto;
        }

        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            height: 100vh;
            width: 250px;
            z-index: 999;
            background-color: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            box-shadow: -10px 0 10px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            justify-content: flex-start;
            text-align:justify;
        }

        .sidebar li,
        .sidebar a {
            width: fit-content;
        }

        .menu-button {
            display: none;
        }

        @media(max-width:800px) {
            .menu-button {
                display: block;
            }
            .hideOnMobile {
                display: none;
            }
        }
        @media(max-width:400px) {
            .sidebar {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav>
        <ul class="sidebar">
            <li onclick="hideSidebar()"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#000000"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg></a></li>
            <li><a href="#">LOGO</a></li>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
        <ul>
            <li><a href="#">LOGO</a></li>
            <li><a href="#" class="hideOnMobile">Home</a></li>
            <li><a href="#" class="hideOnMobile">About</a></li>
            <li><a href="#" class="hideOnMobile">Contact</a></li>
            <li><a href="#" class="hideOnMobile">Dashboard</a></li>
            <li><a href="#" class="hideOnMobile">Logout</a></li>
            <li class="menu-button" onclick="showSidebar()"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960" width="35px" fill="#000000"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg></a></li>
        </ul>
    </nav>

    <main>
        <div style="text-align: center;">
            <a href="#">Centered link</a>
        </div>

    </main>

    <script>
        function showSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.display = 'flex';
        }
        function hideSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.style.display = 'none';
        }
    </script>
    
</body>
</html>