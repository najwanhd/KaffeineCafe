<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("location: login.php");
        exit();
    }

    $host = "localhost";
    $database = "kaffeine";
    $username = "root";
    $password = "";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile - Kaffeine</title>
        <style type="text/css">
            body {
                font-family: Arial, sans-serif;
                background-color: #9a7b4f;
            }

            .container {
                margin: 0 auto;
                width: 500px;
                background-color: beige;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin-top: 80px;
            }

            .profile-picture {
                text-align: center;
                margin-bottom: 20px;
            }

            .profile-picture img {
                width: 150px;
                height: 150px;
                border-radius: 50%;
            }

            td {
                padding: 10px;
            }

            input[type="text"], input[type="password"] {
                width: 100%;
                padding: 8px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            input[type="submit"] {
                width: 50%;
                padding: 10px;
                border-radius: 4px;
                border: none;
                background-color: #4b371c;
                color: beige;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: #80471C;
            }

            .logout-btn button {
                width: 100%;
                padding: 10px;
                border-radius: 4px;
                border: none;
                background-color: #4b371c;
                color: beige;
                cursor: pointer;
            }

            .logout-btn button:hover {
                background-color: #80471C;
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
    </head>
    <body>
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
        <div class="container">
            <div class="profile-picture">
                <?php
                    $host = 'localhost';
                    $username_db = 'root';
                    $password_db = '';
                    $database = 'kaffeine';

                    $conn = new mysqli($host, $username_db, $password_db, $database);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $loggedInUsername = $_SESSION['username'];

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $newUsername = $_POST["username"];
                        $newPassword = $_POST["password"];
                        $newName = $_POST["name"];

                        $stmt = $conn->prepare("UPDATE user SET username=?, password=?, name=? WHERE username=?");
                        $stmt->bind_param("ssss", $newUsername, $newPassword, $newName, $loggedInUsername);

                        $stmt->execute();
                        $stmt->close();

                        if ($newUsername !== $loggedInUsername) {
                            $_SESSION['username'] = $newUsername;
                        }

                        if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
                            $targetDirectory = "profilePicture/";
                            $tmpFile = $_FILES['profilePicture']['tmp_name'];
                            $fileName = $_FILES['profilePicture']['name'];
                            $fileSize = $_FILES['profilePicture']['size'];
                            $maxFileSize = 5 * 1024 * 1024; // 5 MB

                            // Check file size
                            if ($fileSize > $maxFileSize) {
                                echo "Failed to upload! File size is too big (Max. 5MB).";
                                echo "<a href='profile.php'>Back</a>";
                                exit();
                            } else {
                                $newFileName = time() . "_" . $fileName;
                                $targetPath = $targetDirectory . $newFileName;

                                if (move_uploaded_file($tmpFile, $targetPath)) {
                                    // Update profile picture path in the database
                                    $stmt = $conn->prepare("UPDATE user SET profilePicture=? WHERE username=?");
                                    $stmt->bind_param("ss", $targetPath, $loggedInUsername);
                                    $stmt->execute();
                                    $stmt->close();
                                } else {
                                    echo "Failed to upload file.";
                                    echo "<a href='profile.php'>Back</a>";
                                    exit();
                                }
                            }
                        }
                    }

                    $stmt = $conn->prepare("SELECT username, password, name, profilePicture FROM user WHERE username=?");
                    $stmt->bind_param("s", $loggedInUsername);
                    $stmt->execute();
                    $stmt->bind_result($username, $password, $name, $profilePicture);
                    $stmt->fetch();
                    $stmt->close();

                    $conn->close();

                    // Display profile picture
                    echo '<img src="' . $profilePicture . '" alt="Profile Picture">';
                ?>
            </div>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <table>
                    <tr>
                        <td>Username</td>
                        <td><input type="text" name="username" value="<?php echo $username; ?>"></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="password" name="password" value="<?php echo $password; ?>"></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><input type="text" name="name" value="<?php echo $name; ?>"></td>
                    </tr>
                    <tr>
                        <td>Profile Picture</td>
                        <td><input type="file" name="profilePicture"></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left">
                            <input type="submit" name="save" value="Save Changes">
                        </td>
                    </tr>
                </table>
            </form>

            <div class="logout-btn">
                <br/><br/><button onclick="logout()">Logout</button>
            </div>
        </div>

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

            function logout() {
                window.location.href = "logout.php";
            }
        </script>
    </body>
</html>