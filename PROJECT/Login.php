<?php
session_start();

if(isset($_SESSION['username'])) {
    header("location: cafe.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Kaffeine</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            background-color: #9a7b4f;
        }
        
        h1 { 
            text-align: center;
            color: beige;
        }
        
        form {
            margin: 0 auto;
            width: 300px;
            background-color: beige;
            padding: 20px;  
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        table {
            width: 100%;
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
            width: 100%;
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

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
    <script type="text/javascript">
        function validasi() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            if (username == "" || password == "") {
                alert("Username and password must be filled!");
                return false;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'login2.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.valid) {
                        window.location.href = "cafe.php";
                    } else {
                        alert('Invalid username or password');
                    }
                }
            };
            xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));

            return false;
        }
    </script>
</head>
<body>
    <h1>KAFFEINE Cafe</h1>

    <div class="error" style="color:red; margin-bottom:15px;">
        <?php
            if(isset($_COOKIE['message'])) {
                echo $_COOKIE['message'];
            }
        ?>
    </div>

    <form action="login.php" method="POST">
        <table>
            <tr>
                <td>Username</td>
                <td><input type="text" name="username" id="username"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input type="submit" value="Login" onclick="return validasi();">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>