<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("location: login.php");
        exit();
    }

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home - Kaffeine</title>
        <style type="text/css">
            html {
                margin: 0;
                padding: 0;
                width: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                background-image: url(GambarDashboardFixx.jpg);
                background-repeat: no-repeat;
                background-size: cover;
                display: block;
                background-color: #9a7b4f; 
            }

            .text {
                float: left;
                margin-left: 46px;
                padding: 0px 38px;
                font-family: 'Arial', sans-serif;
                font-size: 25px;
                font-weight: bold;
            }

            .text a {
                text-decoration: none;
                color: #ffffff;
            }

            .nav {
                position: fixed;
                top: 0;
                left: 0;
                margin: 0;
                padding: 0;
                height: 80px;
                width: 100%;
                background-color: #9a7b4f; 
                z-index: 1; 
            }

            .nav ul {
                list-style: none;
                cursor: pointer;
                float: right;
                margin-right: 140px;
            }

            .nav ul li {
                list-style: none;
                display: inline-block;
                color: #ffffff;
                font-family: 'Arial', sans-serif;
                padding: 15px 15px;
                font-weight: 400;
            }

            .nav ul li:hover {
                border-bottom: 3px solid #000000;
                transition: all .3s ease;
            }

            .nav ul li a {
                text-decoration: none;
                color: #ffffff;
            }

            .nav ul li a:hover {
                text-decoration: none;
                color: #ffffff;
            }

            .header {
                text-align: center;
                position: absolute;
                top: 40%; 
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .header h1 {
                color: #ffffff;
                text-align: left;
                font-family: Arial, sans-serif;
                font-size: 50px;
            }

            .philosophy {
                position: absolute;
                top: 55%; 
                transform: translate(-50%, -50%);
                text-align: center; 
                left: 50%;
                z-index: 0; 
            }

            .philosophy p {
                color: #9a7b4f; 
                font-family: 'Arial', sans-serif;
                font-size: 16px;
                text-align: center; 
            }

            .menu-button {
                text-align: center;
                margin-top: 500px;
            }

            .menu-button a {
                background-color: #9a7b4f;
                border-color: transparent;
                color: beige;
                cursor: pointer;
                padding: 10px 20px;
                font-family: 'Arial', sans-serif;
                font-size: 18px;
                border-radius: 4px;
            }

            .menu-button a:hover {
                background-color: #80471c;
            }

            .profile {
                display: flex;
                align-items: center;
            }

            .profile-pic {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                margin-right: 10px;
            }

            .dropdown {
                position: relative;
                cursor: pointer;
            }

            .dropdown .dropdown-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                background-color: #9a7b4f;
                padding: 10px;
                margin-top: 5px;
                border-radius: 4px;
                list-style: none;
            }

            .dropdown:hover .dropdown-menu {
                display: block;
            }

            .dropdown .dropdown-menu li {
                color: #ffffff;
                font-family: 'Arial', sans-serif;
                padding: 5px 0;
            }

            .dropdown .dropdown-menu li:hover {
                background-color: #80471c;
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var menuItems = document.querySelector(".nav ul");

                menuItems.addEventListener("click", function(event) {
                    if (event.target.tagName === "LI") {
                        var clickedMenu = event.target.textContent.toLowerCase();
                        
                        switch (clickedMenu) { 
                            case "home":
                                window.location.href = "cafe.php";
                                break;
                            case "menu":
                                window.location.href = "menu.php";
                                break;
                            case "order":
                                window.location.href = "orderHistory.php";
                                break;
                            case "profile":
                                window.location.href = "profile.php";
                                break;
                            default:
                                break;
                        }
                    }
                });
            });
        </script>
    </head>
    <body>
        <div class="wrapper">
            <div class="nav">
                <div class="text">
                    <a href="a">
                        <p>Cafe KAFFEINE</p>
                    </a>
                </div>
                <ul>
                    <li>Home</li>
                    <li>Menu</li>
                    <li>Order</li>
                    <li>Profile</li>
                    <li class="dropdown">
                        Hi, <?php echo $username; ?>
                        <ul class="dropdown-menu">
                            <li><a href="Logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="header">
                <h1>Welcome to Kaffeine</h1>
            </div>

            <div class="philosophy">
                <p>We believe that a cup of coffee can be the perfect link to generate creative ideas </br> and build a warm community.
                </br>In Kaffeine, we are committed to providing an extraordinary coffee experience of the highest quality</p>
            </div>

            <div class="menu-button">
                <a href="menu.php">Our Menu</a>
            </div>
        </div>
    </body>
</html>