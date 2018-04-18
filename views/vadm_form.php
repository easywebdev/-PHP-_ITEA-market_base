<?php
    /* Create Form */
    $form = '<table class="list list_noborder"><form method="post" enctype="multipart/form-data">';
    $form .= '<input type="hidden" name="fileitem" value="'.$file.'">';
    $form .= '<input type="hidden" name="classname" value="'.$classname.'">';
    $form .= '<input type="hidden" name="type" value="'.$ItemType.'">';
    $form .= '<tr><td class="list list_noborder"><label for="model">Model</label></td><td class="list list_noborder"><input type="text" name="model" value="'.$itemdata['name'].'"></td></tr>';
    $form .= '<tr><td class="list list_noborder"><label for="description">Description</label></td><td class="list list_noborder"><textarea name="description">'.$itemdata['description'].'</textarea></td></tr>';
    $form .= '<tr><td class="list list_noborder"><label for="price">Price</label></td><td class="list list_noborder"><input type="text" name="price" value="'.$itemdata['price'].'"></td></tr>';
    $form .= '<tr><td class="list list_noborder"><label for="img">Upload Immages</label></td><td class="list list_noborder"><input type="file" name="img[]" multiple></td></tr>';
    foreach ($FormElements as $key => $value) {
        $form .= '<tr><td class="list list_noborder"><label for="'.$key.'">'.$value['title'].'</label></td>';
        switch ($value['type']) {
            case 'select':
                $options = $ccreateedit->getCol($value['values']['table'], $value['values']['title'], $value['values']['value']);
                $form .= '<td class="list list_noborder"><select name="'.$key.'" id="'.$key.'">';
                foreach ($options as $k => $v) {
                    $form .= '<option value="'.$k.'"';
                    $form .= ($v == $itemdata[$value['values']['title']]) ? 'selected' : '';
                    $form .= '>';
                    $form .= $v.'</option>';
                }
                $form .= '</select>';
                break;

            case 'number':
                $form .= '<td class="list list_noborder"><input type="number" name="'.$key.'" id="'.$key.'"';
                $form .= isset($value['step']) ? ' step="' . $value['step'] . '"' : '';
                $form .= isset($value['min']) ? ' min="' . $value['min'] . '"' : '';
                $form .= isset($value['max']) ? ' max="' . $value['max'] . '"' : '';
                if($itemdata[$value['field']]) {
                    $form .= ' value="'.$itemdata[$value['field']].'""';
                }
                else {
                    $form .= isset($value['value']) ? ' value="' . $value['value'] . '"' : '';
                }
                $form .= '>';
                break;

            case 'text':
                $form .= '<td class="list list_noborder"><input type="text" name="'.$key.'" id="'.$key.'">';
                break;
        }
        $form .= '</td></tr>';
    }
    $form .= '<tr><td class="list list_noborder" rowspan="2"><input class="button" type="submit" name="'.$postName.'" value="APPLY"></td></tr>';

    $form .= '</form></table>';
    /**/
?>

<h2 class="h2">
    <?php
        if($postName == 'update') {
            echo 'Update Product';
        }
        else {
            echo 'Insert Product';
        }
    ?>
</h2>

<div class="block">
<?= $form; ?>

