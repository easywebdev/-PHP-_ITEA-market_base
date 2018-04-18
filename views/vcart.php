<?php
if(($_SESSION['user'] && $_SESSION['status'] == 1) || $_COOKIE['uorder']) {
    if($_SESSION['user'] && $_SESSION['status'] == 1) {
        $orderKey = 'user_id';
        $orderVal = $_SESSION['user'];
    }
    else {
        $orderKey = 'anonymus';
        $orderVal = $_COOKIE['uorder'];
    }

    // Select orders
    $orders = $ccontent->getOrders('cart_products', $orderKey, $orderVal);
    $ordersCount = count($orders);

}
?>
<div class="cart">
    <a href="?cart=cart">
        <div class="cart__img-wraper">
            <img src="../images/cart.png">
        </div>
        <?php
            if($ordersCount) {
                ?>
                    <div class="cart__count">
                        <?= $ordersCount; ?>
                    </div>
                <?php
            }
        ?>

    </a>
</div>
