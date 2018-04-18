<?php
require_once '../controllers/ccreateedit.php';

if($_SESSION['user'] && $_SESSION['status'] == 1) {
    $user = $ccreateedit->getUser('users', $_SESSION['user']);
}

$tbl1 = 'products';               // Table of products (Items)
$tbl2 = 'orders';                 // Table of orders
$tbl3 = 'images';                 // Table of Product images
$Select = null;                   // Select type
$Like = null;                     // Search by Name
$Popular = null;                  // Only Popular

// If Filter options are present
if($_GET['select']) {
    $Select = $_GET['select'];
}
if($_GET['like']) {
    $Like = $_GET['like'];
}
if($_GET['popular'] == 'yes') {
    $Popular = 2;
}
// Create sql conditions for filter options
$Filters = [];
if($Select) {
    $Filters[] = "{$tbl1}.type = '{$Select}'";
}
if($Like) {
    $Filters[] = "{$tbl1}.name LIKE '%{$Like}%'";
}
if($Popular) {
    $Filters[] = "COUNT($tbl2.id) > $Popular";
}
$FilterString = implode(' AND ', $Filters);

// Get total count of all rows in DB according to filter options
$Rows = $ccreateedit->get_count_rows($tbl1, $tbl2, $FilterString);

// If exist del image
if($_GET['delimg']) {
    $img = $ccreateedit->getImg($tbl3, $_GET['delimg']);

    if($img) {
        unlink($img['path']);
        $ccreateedit->deleRow($tbl3, $img['id']);

        $delta = $img['delta'];
        $cimgs = $ccreateedit->getChangedImgs($tbl3, $img['product_id'], $delta);
    }

    if($cimgs) {
        foreach ($cimgs as $key => $value) {
            $filePath = pathinfo($value['path']);
            $path = $filePath['dirname'];
            $ext = $filePath['extension'];
            $name = $img['product_id'].'_'.($value['delta'] - 1);

            $fileName = $path.'/'.$name.'.'.$ext;

            $postImg = [
                'id' => $key,
                'delta' => $value['delta'] - 1,
                'path' => $fileName,
            ];
            rename($value['path'], $fileName);
            $ccreateedit->updateImg($tbl3, $postImg);
        }
    }
    // Remove browser cash for update images
    clearstatcache();
}

// If exist del Page
if($_GET['page'] == 'delete') {
    require_once '../controllers/arrays.php';

    $ItemType = $ccreateedit->get_item_type($tbl1, $_GET['id']);

    // Find file name for require_once and Set the Class Name
    foreach ($arrays->filename as $key => $value) {
        if ($key == $ItemType) {
            $file = $value;                    // file name without .php
            $classname = ucfirst($file);       // Class name, it is build from file name
            break;
        }
    }

    require_once "../controllers/$file.php";
    $item = new $classname($_GET['id']);                                                  // Create new object

    // Del data from all connected tables
    $ccreateedit->delKeyRow('products', 'id', $_GET['id']);
    $ccreateedit->delKeyRow('orders', 'product_id', $_GET['id']);
    $ccreateedit->delKeyRow('orders_products', 'product_id', $_GET['id']);
    $ccreateedit->delKeyRow('images', 'product_id', $_GET['id']);
    $item->getTablesDel($_GET['id']);

    // Del all images
    $dir = '../images/product_'.$_GET['id'];
    $ccreateedit->delAllFiles($dir);

    // Return to the list of products
    header('Location:' . $_SERVER['HTTP_REFERER']);

}

?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/reset.css">
    <link rel="stylesheet" type="text/css" href="../css/site.css">
    <link rel="stylesheet" type="text/css" href="../css/adm.css">
    <title>MARKET</title>
    <script src=""></script>
</head>
<body>
<header class="s-header">
    <div class="container">
        <div class="logo">
            <img class="logo__img" src="../images/store.png">
        </div>

        <div class="user">
            <div class="user__button">
                <?php
                    if($_SESSION['user'] && $_SESSION['status'] == 1) {
                        ?>
                        <div class="user__name color-white">
                            USER: <?= $user['fullname']; ?>
                        </div>
                            <a class="button" href="?logout=logout">logout</a>;
                        <?php
                    }
                ?>
            </div>
        </div>

        <div class="site-name">
            <h1>MARKET (Admin Panel)</h1>
        </div>
    </div>
</header>