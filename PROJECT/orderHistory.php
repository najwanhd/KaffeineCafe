<?php
    session_start();

    $host = 'localhost';
    $username_db = 'root';
    $password_db = '';
    $database = 'kaffeine';

    $conn = new mysqli($host, $username_db, $password_db, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_SESSION['username'])) {
        header("location: login.php");
        exit();
    }

    $username = $_SESSION['username'];

    if (isset($_GET['delete_idOrder'])) {
        $deleteIdOrder = $_GET['delete_idOrder'];

        $preparedStmtCashier = $conn->prepare("DELETE FROM cashier WHERE idOrder = ?");
        $preparedStmtCashier->bind_param("i", $deleteIdOrder);
        $preparedStmtCashier->execute();
        $preparedStmtCashier->close();

        $preparedStmtOrderlist = $conn->prepare("DELETE FROM orderlist WHERE idOrder = ?");
        $preparedStmtOrderlist->bind_param("i", $deleteIdOrder);
        $preparedStmtOrderlist->execute();
        $preparedStmtOrderlist->close();

        header("Location: orderHistory.php");
        exit();
    }

    $sql = "SELECT * FROM orderlist ORDER BY idOrder DESC";

    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        $sql = "SELECT * FROM orderlist WHERE customerName LIKE '%$search%' ORDER BY idOrder DESC";
    }

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die('Could not get data: ' . mysqli_error($conn));
    }
?>

<html>
<head>
    <title>Order History - Kaffeine</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px;
            margin-top: 50px;
        }

        h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 850px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            border: 1px solid black;
            text-align: center;
        }

        .button-container {
            margin-top: 25px;
            border-radius: 4px;
            border: none;
        }

        .button-container button {
            background-color: #4b371c;
            color: beige;
            padding: 10px 20px;
            margin-right: 10px;
            border-radius: 4px;
            border: none;
        }

        .delete-button {
            background-color: #C94E4C;
            color: beige;
            padding: 2px 6px;
            text-decoration: none;
            border-radius: 4px;
            border: none;
        }

        .button-container button:hover {
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

        .search {
            width: 70%; 
            padding: 8px; 
            border-radius: 4px; 
            border: 1px solid #ccc;
        }

        .searchbutton {
            background-color: #4b371c; 
            color: beige; 
            padding: 10px 20px; 
            margin-top: 10px; 
            border-radius: 4px; 
            border: none;
        }

        .searchbutton:hover {
            background-color: #80471C;
        }


        @media print {
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }

                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 50px;
                    margin-top: 10px;
                }

                h1 {
                    font-size: 32px;
                    font-weight: bold;
                    margin-bottom: 20px;
                }

                table {
                    width: 100%;
                    border: 1px solid black;
                    border-collapse: collapse;
                }

                table th, table td {
                    padding: 8px;
                    border: 1px solid black;
                    text-align: center;
                }

                .button-container, .delete-button, .text, .nav {
                    display: none;
                }
            }
    </style>
</head>
<body>
    <div class="nav">
        <div class="text">
            <a href="#">
                <p>Cafe KAFFEINE</p>
            </a>
        </div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Menu</a></li>
            <li><a href="#">Order</a></li>
            <li><a href="#">Profile</a></li>
            <li class="dropdown">
                Hi, <?php echo $username; ?>
                <ul class="dropdown-menu">
                    <li><a href="Logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="container">
        <h1>Order History</h1>
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Search by customer name" class="search">
            <button type="submit" class="searchbutton">Search</button>
        </form>
        <br/>
        <table>
            <tr>
                <th>No</th>
                <th>Customer Name</th>
                <th>Transaction Time</th>
                <th>Ordered Items</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
            <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td> $i </td>";
                    echo "<td> {$row['customerName']} </td>";
                    echo "<td> {$row['transactionTime']} </td>";
                    echo "<td> {$row['orderedItems']} </td>";

                    $totalPriceFormatted = 'Rp' . number_format($row['totalPrice'], 0, ',', '.') . '.000';

                    echo "<td> {$totalPriceFormatted} </td>";

                    echo "<td> <a href='orderHistory.php?delete_idOrder={$row['idOrder']}' onclick='return confirm(\"Are you sure you want to delete this order?\")' class='delete-button'>Delete</a></td>";
                    echo "</tr>";
                    $i++;
                }

                mysqli_close($conn);
            ?>
        </table>
        <div class="button-container">
            <button type="button" onclick="location.href='menu.php';">New Order</button>
            <button type="button" onclick="printOrderHistory();">Print</button>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var menuItems = document.querySelector(".nav ul");

            menuItems.addEventListener("click", function(event) {
                if (event.target.tagName === "A") {
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

       
        function printOrderHistory() {
                var pageTitle = document.title;

                var orderTable = document.querySelector("table");
                var orderTableClone = orderTable.cloneNode(true);

                var actionColumn = orderTableClone.querySelector("th:nth-child(6)");
                var dataColumns = orderTableClone.querySelectorAll("td:nth-child(6)");
                actionColumn.parentNode.removeChild(actionColumn);
                for (var i = 0; i < dataColumns.length; i++) {
                    dataColumns[i].parentNode.removeChild(dataColumns[i]);
                }

                orderTableClone.style.visibility = "visible";

                var printWindow = window.open('', '', 'width=800,height=600');
                printWindow.document.open();

                printWindow.document.write('<html><head><title>' + pageTitle + '</title>');
                printWindow.document.write('<style>');
                printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 0; }');
                printWindow.document.write('h1 { font-size: 32px; font-weight: bold; margin-bottom: 20px; }');
                printWindow.document.write('table { width: 100%; border: 1px solid black; border-collapse: collapse; }');
                printWindow.document.write('table th, table td { padding: 8px; border: 1px solid black; text-align: center; }');
                
                printWindow.document.write('</style></head><body>');

                printWindow.document.write('<h1>' + pageTitle + '</h1>');
                printWindow.document.write(orderTableClone.outerHTML);
                printWindow.document.write('</body></html>');

                printWindow.document.close();
                printWindow.print();
                printWindow.close();
            }
    </script>
</body>
</html>
