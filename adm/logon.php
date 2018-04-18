<?php
session_start();

if($_POST['submit']) {
    require_once '../controllers/ccreateedit.php';

    $post['login'] = $_POST['login'];
    $post['pass'] = md5($_POST['pass']);
    $user = $ccreateedit->findUser('users', $post);

    if($user) {
        $_SESSION['user'] = $user['id'];
        $_SESSION['status'] = $user['status'];

        if($user['status'] != 1) {
            $msg = 'Your account is not activated.<br>Please check your email and activate account.';
        }
        else {
            // check if exist orders for anonymus and update them to User
            if($_COOKIE['uorder']) {
                $anonymusRows = $ccreateedit->selectAnonymusOrders('cart_products', $_COOKIE['uorder']);

                $updateCart = [
                    'user_id' => $user['id'],
                    'anonymus' => null,
                ];

                if($anonymusRows) {
                    while ($row = mysqli_fetch_assoc($anonymusRows)) {
                        $anonymusOrders[$row['anonymus']] = $row;
                    }

                    if($anonymusOrders) {
                        foreach ($anonymusOrders as $key => $value) {
                            $ccreateedit->updateAnonymusToUser('cart_products', $updateCart, $_COOKIE['uorder']);
                        }
                    }

                }
            }
        }
    }
    else {
        $msg = 'Sorry but this combination login and password not found in uor database.<br>
                Please go to the registration page: ';
        $msg .= '<a href="./registration.php">Registration</a>';
    }
}

if($_SESSION['user'] && $_SESSION['status'] == 1) {
    header("Location:index.php");
}
?>

<?php
    require_once '../views/vadm_header.php';
?>

<div class="form-container">
        <form class="form" method="post">
            <div class="form__item">
                <label class="form__label" for="login">Login:</label><input type="text" id="login" name="login">
            </div>
            <div class="form__item">
                <label class="form__label" for="pass">Password:</label><input type="password" id="pass" name="pass">
            </div>
            <div class="form__item">
                <input type="submit" name="submit" value="LOGIN">
            </div>
        </form>
</div>

<div class="form-container">
    <a class="link-button" href="./registration.php">Registration</a>
</div>

<div class="form-container">
    <?php
        if($msg) {
            echo $msg;
        }
    ?>
</div>

<?php
    require_once '../views/footer.php';
?>
