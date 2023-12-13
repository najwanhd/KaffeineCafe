<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("location: login.php");
        exit();
    }

    $host = 'localhost';
    $username_db = 'root';
    $password_db = '';
    $database = 'kaffeine';

    $conn = new mysqli($host, $username_db, $password_db, $database);

    if ($conn->connect_error) {
        die("Koneksi ke database gagal: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $customerName = $_POST['customer-name'];
        $orderedItems = json_decode($_POST['ordered-items'], true);
        $totalPrice = $_POST['total-price'];

        $formattedItems = "";
        foreach ($orderedItems as $item) {
            $formattedItems .= $item['name'] . ", qty:" . $item['quantity'] . "\n";
        }

        $transactionTime = date('Y-m-d H:i:s');

        $preparedStmtOrderlist = $conn->prepare("INSERT INTO orderlist (customerName, transactionTime, orderedItems, totalPrice) VALUES (?, ?, ?, ?)");
        $preparedStmtOrderlist->bind_param("ssss", $customerName, $transactionTime, $formattedItems, $totalPrice);

        if ($preparedStmtOrderlist->execute()) {
            $orderId = $preparedStmtOrderlist->insert_id;

            $preparedStmtUser = $conn->prepare("SELECT id FROM user WHERE username = ?");
            $preparedStmtUser->bind_param("s", $username);
            $preparedStmtUser->execute();
            $preparedStmtUser->bind_result($userId);
            $preparedStmtUser->fetch();
            $preparedStmtUser->close();

            $preparedStmtCashier = $conn->prepare("INSERT INTO cashier (idOrder, id) VALUES (?, ?)");
            $preparedStmtCashier->bind_param("ii", $orderId, $userId);
            $preparedStmtCashier->execute();
            $preparedStmtCashier->close();

            echo '<script>alert("Pesanan telah disimpan!");</script>';
            header("Location: orderHistory.php");
            exit();
        } else {
            echo "Error: " . $preparedStmtOrderlist->error;
        }

        $preparedStmtOrderlist->close();
        $conn->close();
        exit;
    }
?>

<html>
    <head>
        <title>Order - Kaffeine</title> 
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                padding: 40px;
                margin-top: 50px
            }

            h1 {
                font-size: 32px;
                font-weight: bold;
                margin-bottom: 20px;
            }

            form {
                margin-bottom: 20px;
            }

            label {
                display: block;
                font-weight: bold;
                margin-bottom: 10px;
            }

            input[type="text"] {
                width: 100%;
                padding: 8px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            textarea {
                width: 100%;
                padding: 8px;
                font-size: 16px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            button {
                background-color: #4b371c;
                border: none;
                color: beige;
                cursor: pointer;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 4px;
                margin-top: 20px;
            }

            button:hover {
                background-color: #80471C;
            }

            .cart-items {
                margin-bottom: 20px;
            }

            .cart-item {
                margin-bottom: 5px;
                font-size: 16px;
            }

            .total-price {
                font-weight: bold;
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
            <h1>Order</h1>
            <form action="" method="post" onsubmit="return showConfirmation();">
                <label for="customer-name">Customer Name</label>
                <input type="text" id="customer-name" name="customer-name" required>

                <div class="cart-items">
                    <h3>Ordered Items</h3>
                    <?php
                    $orderedItems = isset($_GET['orderedItems']) ? json_decode($_GET['orderedItems'], true) : array();
                    $totalPrice = isset($_GET['totalPrice']) ? $_GET['totalPrice'] : '';

                    foreach ($orderedItems as $item) {
                        echo '<div class="cart-item">' . $item['name'] . ': ' . $item['quantity'] . '</div>';
                    }
                    ?>
                </div>

                <label for="total-price">Total Price</label>
                <input type="text" id="total-price" name="total-price" value="<?php echo isset($_GET['totalPrice']) ? number_format($_GET['totalPrice'], 2) : ''; ?>" readonly>
                <input type="hidden" id="ordered-items" name="ordered-items" value="<?php echo htmlentities(json_encode($orderedItems)); ?>">
                <button type="submit">Save</button>
                <button type="button" onclick="printInvoice()">Print</button>
            </form>
        </div>

        <script>
            function showConfirmation() {
                alert("Order successfully saved!");
                return true;
            }

            function printInvoice() {
                var pageTitle = document.title;

                var cafeName = "Cafe KAFFEINE";
                var customerName = document.getElementById("customer-name").value;
                var orderedItems = document.getElementById("ordered-items").value;
                var totalPrice = document.getElementById("total-price").value;

                var printWindow = window.open('', '', 'width=800,height=600');
                printWindow.document.open();

                printWindow.document.write('<html><head><title>' + pageTitle + '</title>');
                printWindow.document.write('<style>');
                printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 0; }');
                printWindow.document.write('h1 { font-size: 32px; font-weight: bold; margin-bottom: 20px; }');
                printWindow.document.write('p.cafe-name { font-size: 24px; font-weight: bold; margin-bottom: 10px; }');
                printWindow.document.write('p.customer-name { font-size: 18px; font-weight: bold; margin-bottom: 10px; }');
                printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }');
                printWindow.document.write('table th, table td { padding: 8px; border: 1px solid black; text-align: center; }');
                printWindow.document.write('</style></head><body>');

                printWindow.document.write('<p class="cafe-name">' + cafeName + '</p>');
                printWindow.document.write('<p class="customer-name">Customer Name: ' + customerName + '</p>');

                printWindow.document.write('<table>');
                printWindow.document.write('<tr><th>No</th><th>Item</th><th>Quantity</th></tr>');

                var items = JSON.parse(orderedItems);
                var itemIndex = 1;
                items.forEach(function(item) {
                    var itemName = item.name;
                    var itemQuantity = item.quantity;
                    printWindow.document.write('<tr><td>' + itemIndex + '</td><td>' + itemName + '</td><td>' + itemQuantity + '</td></tr>');
                    itemIndex++;
                });

                printWindow.document.write('</table>');
                printWindow.document.write('<p>Total Price: Rp' + totalPrice + '</p>');
                printWindow.document.write('</body></html>');

                printWindow.document.close();
                printWindow.print();
                printWindow.close();
            }
        </script>
    </body>
</html>