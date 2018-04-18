<?php
$Types = $ccontent->get_unique('products', 'type');
?>
<form class="block block_filter" method="get">
    <div class="block__item block__item_input">
        <select name="select">
            <option name="none" value="" <?php if(!$_GET['select']) { echo 'selected'; } ?>>none</option>
            <?php
                foreach ($Types as $key => $value) {
                    ?>
                        <option name="<?= $value; ?>" value="<?= $value; ?>" <?php if($_GET && $_GET['select'] == $value) {echo 'selected';} ?>><?= $value ?></option>
                    <?php
                }
            ?>
        </select>
    </div>
    <div class="block__item block__item_input">
        <input name="like" type="text" placeholder="Search" value="<?php if($_GET['like']) {echo $_GET['like'];} ?>">
    </div>
    <div class="block__item block__item_input">
        <input name="popular" type="checkbox" value="yes" <?php if($_GET['popular'] == 'yes') { echo 'checked'; } ?>>Popular only
    </div>
    <div class="block__item block__item_input">
        <input type="submit" name="filter" value="FILTER">
    </div>
</form>
