<?php
    require_once '../controllers/ccontent.php';

    if($_POST['order']) {
        if($_SESSION['user'] && $_SESSION['status'] == 1) {
            $userID = $_SESSION['user'];
            $anonymus = null;
        }
        else {
            $userID = 0;
            if($_COOKIE['uorder']) {
                $anonymus = $_COOKIE['uorder'];
            }
            else {
                $anonymus = md5(rand(1000, 9000));
                $cookieTime = 18000;
            }
        }

        $orderPost = [
            'product_id' => $_POST['id'],
            'user_id' => $userID,
            'anonymus' => $anonymus,
            'count' => $_POST['count'],
        ];

        $ccontent->addOrder('cart_products', $orderPost);
    }

    if($_POST['bye']) {
        $ccontent->processOrder('uorders', 'orders', 'orders_products', $_POST);
    }
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/reset.css">
    <link rel="stylesheet" type="text/css" href="../css/site.css">
    <title>MARKET</title>
    <script src=""></script>
</head>
<body>
    <header class="s-header">
        <div class="container">
            <div class="logo">
                <img class="logo__img" src="../images/store.png">
            </div>

            <div class="site-name">
                <h1>MARKET</h1>
            </div>
        </div>
    </header>