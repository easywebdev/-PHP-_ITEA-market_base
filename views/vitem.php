<?php
require_once '../controllers/arrays.php';

if($_GET['page']) {
    $ItemType = $ccontent->get_item_type($tbl1, $_GET['page']);

    // Find file name for require_once and Set the Class Name
    foreach ($arrays->filename as $key => $value) {
        if($key == $ItemType) {
            $file = $value;                    // file name without .php
            $classname = ucfirst($file);       // Class name, it is build from file name
            break;
        }
    }

    require_once "../controllers/$file.php";
    $item = new $classname();                                                  // Create new object
    $itemdata = $item->setSQLitem($tbl1, $_GET['page']);                       // Get SQL data for selected item
    $itemImg = $item->getItemImg($tbl3, $_GET['page']);                        // Get Item images

    //Back Link
    if(!$_POST) {
        $backLink = $_SERVER['HTTP_REFERER'];
        $_SESSION['backlink'] = $backLink;
    }
    else {
        $backLink = $_SESSION['backlink'];
    }
}
?>
<a class="link-button" href="<?= $backLink; ?>">BACK</a>
<h2 class="h2"><?= $itemdata['name']; ?></h2>
<h2 class="h2">(<?= $itemdata['type'] ?>)</h2>

<div class="block">
    <div class="block__item block__item_input item__img-wrapper">
        <?php
        if($itemImg) {
            foreach ($itemImg as $key => $value) {
                if($value['delta'] == 0) {
                    $mainImg = $value['path'];
                    break;
                }
            }
        }
        $mainImg = isset($mainImg) ? '<img class="item__img" src="'.$mainImg.'">' : '<img class="item__img" src="../images/noimage.jpg">';
        echo $mainImg;
        ?>
    </div>
    <div class="block__item block__item_input block__item_description">
        <h3>Description</h3>
        <?= $itemdata['description']; ?>
    </div>
</div>

<div class="block bloc_mb-20px">
    <div class="block__item block__item_input">
        Price <?= $itemdata['price']; ?> UAH
    </div>
</div>

<h3 class="h3">Specification</h3>
<div class="block bloc_mb-20px block_specification">
    <table>
        <?php
        foreach ($item->TagNames as $key => $value) {
            foreach ($value as $tag => $name) {
                ?>
                <tr>
                    <td><?= $name; ?></td>
                    <td><?= $itemdata[$tag]; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>

<h3 class="h3">Images</h3>
<div class="block bloc_mb-20px">
    <?php
        if($itemImg) {
            foreach ($itemImg as $key => $value) {
                ?>
                <div class="block__item">
                    <div class="item__img-wrapper">
                        <img class="item__img" src="<?= $value['path']; ?>">
                    </div>
                </div>
                <?php
            }
        }
    ?>
</div>

<div class="block">
    <div class="block__item block__item_input">
        <?= $itemdata['datetime']; ?>
    </div>
</div>

<div>
    <h2>Order</h2>
    <form method="post">
        <table>
            <tr>
                <td>
                    <input type="hidden" name="id" value="<?= $_GET['page']; ?>">
                    <label for="count"></label><input type="number" name="count" value="1">
                    <input type="submit" name="order" value="ORDER">
                </td>
            </tr>
        </table>
    </form>
</div>