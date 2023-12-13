<?php
    session_start();

    $host = "localhost";
    $database = "kaffeine";
    $username = "root";
    $password = "";

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['username'] = $username;
        echo json_encode(array('valid' => true));
    } else {
        echo json_encode(array('valid' => false));
    }

    mysqli_close($conn);
?>
