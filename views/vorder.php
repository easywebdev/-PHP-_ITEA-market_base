<?php

?>
<div class="block__item_full-width bloc_mb-20px"><a class="link-button" href="<?= $_SESSION['backlink']; ?>">BACK</a></div>

<?php
    if($orders) {
        ?>
            <div class="block__item block__item_mr20">
                <table class="list">
                    <tr>
                        <td class="list">#</td>
                        <td class="list">Product Name</td>
                        <td class="list">Image</td>
                        <td class="list">Count</td>
                        <td class="list">Price UAH</td>
                    </tr>
                        <?php
                            $price = 0;
                            $order = [];
                            foreach ($orders as $key => $value) {
                                    $product = $ccontent->getProduct($tbl1, $value['product_id']);
                                    $price += ($value['count'] * $product['price']);
                                    $order[$key] = [
                                        'product_id' => $value['product_id'],
                                        'count' => $value['count'],
                                    ]
                                    ?>
                                        <tr>
                                            <td class="list"><?= $key; ?></td>
                                            <td class="list"><?= $product['name']; ?></td>
                                            <td class="list"><img width="50" src="<?= $product['image']; ?>"></td>
                                            <td class="list"><?= $value['count']; ?></td>
                                            <td class="list"><?= $value['count'] * $product['price']; ?></td>
                                        </tr>
                                    <?php
                            }
                        ?>
                    <tr>
                        <td colspan="4">Total Price:</td>
                        <td><?= $price; ?></td>
                    </tr>
                </table>
            </div>

            <div class="block__item">
                <form method="post">
                    <input type="hidden" name="user_id" value="<?= $orderVal; ?>">
                    <input type="hidden" name="data" value=<?= json_encode($order); ?>>
                    <input type="hidden" name="datetime" value="<?= date("y.m.d H:m:s") ?>">
                    <table class="list">
                        <tr>
                            <td class="list">
                                <label for="">Address:</label>
                            </td>
                            <td class="list">
                                <input type="text" name="address" value="">
                            </td>
                        </tr>
                        <tr>
                           <td colspan="2" class="list">
                               <input type="submit" name="bye" value="BYE">
                           </td>
                        </tr>
                    </table>
                </form>
            </div>
        <?php
    }
?>

