<?php
session_start();

if(!$_SESSION['user'] && $_SESSION['status'] != 1) {
    header("Location:logon.php");
}

if($_GET['logout'] == 'logout') {
    $_SESSION['user'] = null;
    $_SESSION['status'] = null;
    header("Location:logon.php");
}

require_once '../views/vadm_header.php';
require_once '../views/vadm_body.php';
require_once '../views/footer.php';