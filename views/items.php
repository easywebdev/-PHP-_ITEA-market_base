<?php
$ItemStart = 0;                   // Start position in table for items
$ItemCounts = 10;                 // Count of shoved items

// Set start position for LIMIT in select function
if($_GET['blank']) {
    $ItemStart = $_GET['blank'] * $ItemCounts - $ItemCounts;
}



// Get Items
$Items = $ccontent->get_rows($tbl1, $tbl2, 'select_items_filter', $FilterString, $ItemStart, $ItemCounts);

if($Items) {
    foreach ($Items as $key => $value) {
        $itemImg = $ccontent->getItemImg($tbl3, $key);
        $mainImg = null;                                      // Zeroing variable in each cycle
        if($itemImg) {
            foreach ($itemImg as $ikey => $ivalue) {
                if($value['delta'] == 0) {
                    $mainImg = $ivalue['path'];
                    break;
                }
            }
        }
        $mainImg = isset($mainImg) ? $mainImg : '../images/noimage.jpg';
        ?>
        <a class="block__item block__item_mb-products" href="?page=<?= $key; ?>">
            <div class="item">
                <h3 class="h3"><?= $value['name']; ?></h3>
                <div class="item__img-wrapper">
                    <img class="item__img" src="<?= $mainImg; ?>">
                </div>
                <div class="item__type">
                    Type: <?= $value['type']; ?>
                </div>
                <div class="item__text">
                    Price:<span class="item__prise"><?= $value['price']; ?></span>(UAH)
                </div>
                <div class="item__orders">Orders count [<?= $value['orders'] ?>]</div>
            </div>
        </a>
        <?php
    }
}
else {
    echo 'NO RESULTS';
}
?>

