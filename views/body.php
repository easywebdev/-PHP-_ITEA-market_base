<?php

    $tbl1 = 'products';               // Table of products (Items)
    $tbl2 = 'orders';                 // Table of orders
    $tbl3 = 'images';                 // Tabble of Item images
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
    $Rows = $ccontent->get_count_rows($tbl1, $tbl2, $FilterString);

?>
<main class="s-main">
    <div class="container">
        <div class="s-cart">
            <?php
                require_once '../views/vcart.php';
            ?>
        </div>
        <?php
            if($_GET['page']) {
                    require_once '../views/vitem.php';
            }
            elseif ($_GET['cart'] == 'cart') {
                ?>
                    <h2>ORDER</h2>
                    <div class="block bloc_mb-20px">
                        <?php require_once '../views/vorder.php'; ?>
                    </div>

                <?php
            }
            else {
                ?>
                <h2>FILTER</h2>
                <div class="bloc_mb-20px">
                    <?php require_once '../views/filter.php'; ?>
                </div>

                <h2>ITEMS</h2>
                <div class="block bloc_mb-20px">
                    <?php require_once '../views/items.php'; ?>
                </div>

                <div class="block block_pagination">
                    <?php require_once '../views/pagination.php'; ?>
                </div>
                <?php
            }
        ?>
    </div>
</main>
