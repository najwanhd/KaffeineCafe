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

<html>
    <head>
        <title>Menu - Kaffeine</title>
        <style type="text/css">
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .container {
                display: flex;
                margin-top: 10px;
            }

            .menu {
                width: 82%;
                padding: 40px;
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .cart-window {
                width: 18%;
                padding-top: 40px;
                padding-right: 40px;
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }

            .item {
                width: calc(25% - 25px);
                border: 1px solid #ccc;
                border-radius: 5px;
                padding: 20px;
                margin-bottom: 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                box-sizing: border-box;
            }

            .item img {
                max-width: 150px;
                max-height: 150px;
                margin-bottom: 10px;
                border-radius: 3px;
            }

            .item-name {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .item-price {
                font-size: 16px;
                margin-bottom: 5px;
            }

            .counter {
                display: flex;
                align-items: center;
            }

            .counter-button {
                background: #4b371c;
                border: none;
                color: beige;
                cursor: pointer;
                padding: 5px;
                font-size: 16px;
                border-radius: 50%;
                margin: 0 8px;
                width: 25px;
                height: 25px;
            }

            .item-counter {
                font-weight: bold;
            }

            .checkout-button {
                background: #4b371c;
                border: none;
                color: beige;
                cursor: pointer;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 4px;
                margin-top: 20px;
            }

            .cart-items {
                margin-bottom: 10px;
            }

            .cart-item {
                margin-bottom: 5px;
                font-size: 16px;
            }

            .cart {
                background: #f7f7f7;
                border: 1px solid #ccc;
                padding: 10px;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                border-radius: 5px;
                width: 200px;
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

            @media (max-width: 768px) {
                .container {
                    flex-direction: column;
                }

                .menu, .cart-window {
                    width: 100%;
                    padding: 20px;
                }

                .item {
                    width: calc(50% - 15px);
                }
            }

            @media (max-width: 480px) {
                .item {
                    width: 100%;
                }
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
            <div class="menu">
                <div class="item">
                    <img src="latte.jpg" alt="Caffe Latte">
                    <div class="item-name">Caffe Latte</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(0)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(0)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="cappuccino.jpg" alt="Cappuccino">
                    <div class="item-name">Cappuccino</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(1)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(1)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="caramelmacchiato.jpg" alt="Caramel Macchiato">
                    <div class="item-name">Caramel Macchiato</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(2)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(2)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="americano.jpg" alt="Americano">
                    <div class="item-name">Americano</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(3)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(3)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="vanillalatte.jpg" alt="Vanilla Latte">
                    <div class="item-name">Vanilla Latte</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(4)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(4)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="mocha.jpg" alt="Mochaccino">
                    <div class="item-name">Mochaccino</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(5)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(5)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="matcha.jpg" alt="Matcha Latte">
                    <div class="item-name">Matcha Latte</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(6)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(6)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="chocolate.jpg" alt="Chocolate">
                    <div class="item-name">Signature Chocolate</div>
                    <div class="item-price">Rp35.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(7)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(7)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="tea.jpg" alt="Tea">
                    <div class="item-name">Tea</div>
                    <div class="item-price">Rp30.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(8)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(8)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="lemontea.jpg" alt="Lemon Tea">
                    <div class="item-name">Lemon Tea</div>
                    <div class="item-price">Rp30.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(9)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(9)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="lychee.jpg" alt="Lychee Tea">
                    <div class="item-name">Lychee Tea</div>
                    <div class="item-price">Rp30.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(10)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(10)">+</button>
                    </div>
                </div>
                <div class="item">
                    <img src="croissant.jpg" alt="Croissant">
                    <div class="item-name">Croissant</div>
                    <div class="item-price">Rp25.000</div>
                    <div class="counter">
                        <button class="counter-button" onclick="decrementCounter(11)">-</button>
                        <span class="item-counter">0</span>
                        <button class="counter-button" onclick="incrementCounter(11)">+</button>
                    </div>
                </div>
            </div>

            <div class="cart-window">
                <div class="cart">
                    <h3>Cart</h3>
                    <div class="cart-items" id="cart-items"></div>
                    <button class="checkout-button" onclick="checkout()">Checkout</button>
                </div>
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
            
            var itemCounters = document.querySelectorAll('.item-counter');
            var cartItemsElement = document.getElementById('cart-items');

            function incrementCounter(itemIndex) {
                var counterElement = itemCounters[itemIndex];
                var count = parseInt(counterElement.textContent);
                count++;
                counterElement.textContent = count;
                updateCart(itemIndex, count);
            }

            function decrementCounter(itemIndex) {
                var counterElement = itemCounters[itemIndex];
                var count = parseInt(counterElement.textContent);
                if (count > 0) {
                    count--;
                    counterElement.textContent = count;
                    updateCart(itemIndex, count);
                }
            }

            function updateCart(itemIndex, count) {
                var itemName = '';
                switch (itemIndex) {
                    case 0:
                        itemName = 'Caffe Latte';
                        break;
                    case 1:
                        itemName = 'Cappuccino';
                        break;
                    case 2:
                        itemName = 'Caramel Macchiato';
                        break;
                    case 3:
                        itemName = 'Americano';
                        break;
                    case 4:
                        itemName = 'Vanilla Latte';
                        break;
                    case 5:
                        itemName = 'Mochaccino';
                        break;
                    case 6:
                        itemName = 'Matcha Latte';
                        break;
                    case 7:
                        itemName = 'Signature Chocolate';
                        break;
                    case 8:
                        itemName = 'Tea';
                        break;
                    case 9:
                        itemName = 'Lemon Tea';
                        break;
                    case 10:
                        itemName = 'Lychee Tea';
                        break;
                    case 11:
                        itemName = 'Croissant';
                        break;
                }

                var cartItems = document.querySelectorAll('.cart-item');
                var cartItemElement = null;

                for (var i = 0; i < cartItems.length; i++) {
                    if (cartItems[i].textContent.startsWith(itemName)) {
                        cartItemElement = cartItems[i];
                        break;
                    }
                }

                if (count === 0) {
                    if (cartItemElement) {
                        cartItemElement.remove();
                    }
                } else {
                    if (cartItemElement) {
                        cartItemElement.textContent = itemName + ': ' + count;
                    } else {
                        cartItemElement = document.createElement('div');
                        cartItemElement.className = 'cart-item';
                        cartItemElement.textContent = itemName + ': ' + count;
                        cartItemsElement.appendChild(cartItemElement);
                    }
                }
            }

            function checkout() {
                var orderedItems = [];
                var totalPrice = 0;

                for (var i = 0; i < itemCounters.length; i++) {
                    var count = parseInt(itemCounters[i].textContent);

                    if (count > 0) {
                        var itemName = '';
                        switch (i) {
                            case 0:
                                itemName = 'Caffe Latte';
                                break;
                            case 1:
                                itemName = 'Cappuccino';
                                break;
                            case 2:
                                itemName = 'Caramel Macchiato';
                                break;
                            case 3:
                                itemName = 'Americano';
                                break;
                            case 4:
                                itemName = 'Vanilla Latte';
                                break;
                            case 5:
                                itemName = 'Mochaccino';
                                break;
                            case 6:
                                itemName = 'Matcha Latte';
                                break;
                            case 7:
                                itemName = 'Signature Chocolate';
                                break;
                            case 8:
                                itemName = 'Tea';
                                break;
                            case 9:
                                itemName = 'Lemon Tea';
                                break;
                            case 10:
                                itemName = 'Lychee Tea';
                                break;
                            case 11:
                                itemName = 'Croissant';
                                break;
                        }

                        orderedItems.push({
                            name: itemName,
                            quantity: count
                        });

                        var itemPrice = parseFloat(document.getElementsByClassName('item-price')[i].textContent.replace('Rp', '').replace('.', '').replace(',', '.'));
                        totalPrice += itemPrice * count;
                    }
                }

                totalPrice = Math.floor(totalPrice);

                var orderedItemsParam = encodeURIComponent(JSON.stringify(orderedItems));
                var totalPriceParam = encodeURIComponent(totalPrice.toString());

                window.location.href = "order.php?orderedItems=" + orderedItemsParam + "&totalPrice=" + totalPriceParam;
            }
        </script>
    </body>
</html>