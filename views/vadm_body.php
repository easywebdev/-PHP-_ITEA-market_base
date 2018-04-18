<?php

?>
<main class="s-main container bloc_mb-20px min-height-400 cf">
    <div class="s-left">
        <h2 class="h2 color-white">MENU</h2>
        <nav class="left-nav">
        <?php
            require_once '../views/vadm_menu.php';
        ?>
        </nav>
    </div>
    <div class="s-right">
        <?php
            if(!$_GET['page'] || $_GET['page'] == 'list') {
                ?>
                <h2 class="h2 uppercase">products list</h2>
                    <div class="bloc_mb-20px">
                        <?php require_once '../views/vadm_filter.php'; ?>
                    </div>

                    <div class="block bloc_mb-20px">
                        <?php require_once '../views/vadm_items.php'; ?>
                    </div>

                    <div class="block block_pagination">
                        <?php require_once '../views/pagination.php'; ?>
                    </div>
                <?php
            }

            if($_GET['page'] == 'insert') {
                require_once '../views/vadm_insert_product.php';
            }

            if($_GET['page'] == 'edit') {
                require_once '../views/vadm_update_product.php';
            }
        ?>
    </div>
</main>
