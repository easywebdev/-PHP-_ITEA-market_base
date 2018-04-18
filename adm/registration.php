<?php
    require_once '../controllers/ccreateedit.php';
    session_start();

    if($_POST) {
        $postUser = $ccreateedit->addUser('users', $_POST);
    }

    if($_GET['id'] && $_GET['link']) {
        $user = $ccreateedit->getUser('users', $_GET['id']);

        $hash = md5($_GET['id'].$user['login'].$user['pass']);

        if($hash == $_GET['link']) {
            $ccreateedit->updateUserStatus('users', $_GET['id']);
            $_SESSION['user'] = $_GET['id'];
            $_SESSION['status'] = 1;
            header("Location:index.php");
        }
        else {
            $activationFail = 'Your activation link is wrong<br>Please create new registration account';
        }
    }
?>
<?php
    require_once '../views/vadm_header.php';
?>
<div class="form-container">
    <form class="form" method="post">
        <div class="form__item">
            <label class="form__label" for="login">Login:</label><input type="text" name="login" id="login" required>
        </div>
        <div class="form__item">
            <label class="form__label" for="pass">Password:</label><input type="password" name="pass" id="pass" required>
        </div>
        <div class="form__item">
            <label class="form__label" for="confirm_pass">Confirm Password:</label><input type="password" name="confirm_pass" required>
        </div>
        <div class="form__item">
            <label class="form__label" for="email">Email:</label><input type="email" name="email" id="email">
        </div>
        <div class="form__item">
            <label class="form__label" for="fullname">Full Name:</label><input type="text" name="fullname" id="fullname">
        </div>
        <div class="form__item">
            <input type="submit" name="register" value="REGISTER">
        </div>
    </form>
</div>

<div class="form-container">
    <a class="link-button" href="./logon.php">Logon</a>
</div>

<div class="form-container">
    <?php
        if($activationFail) {
            echo $activationFail;
        }
    ?>
</div>

<div class="form-container">
    <?php
        if($postUser) {
            echo $postUser;
        }
    ?>
</div>
<?php
    require_once '../views/footer.php';
?>
