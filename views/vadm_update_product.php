<?php
require_once '../controllers/arrays.php';

if($_GET['page'] == 'edit' && $_GET['id']) {
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
    $item = new $classname();                                                  // Create new object

    if($_POST['update']) {
        $item->updateSQL($tbl1, $_POST, $_GET['id']);
    }

    $itemdata = $item->setSQLitem($tbl1, $_GET['id']);                         // Get SQL data for selected item
    $itemImg = $item->getItemImg($tbl3, $_GET['id']);                          // Get Item images

    $FormElements = $item->formElements;
    $postName = 'update';
    require_once '../views/vadm_form.php';
}
?>

<div>
<h3 class="h3 mr-10">Images:</h3>
<table class="list">
    <tr><td class="list">#</td><td class="list">Image</td><td class="list">Size</td><td class="list">Delete</td></tr>
<?php
    if($itemImg) {
        foreach ($itemImg as $key => $value) {
            ?>
                <tr class="list">
                    <td class="list">
                        <?= $value['delta']; ?>
                    </td>
                    <td class="list">
                        <img width="50" src="<?= $value['path']; ?>">
                    </td>
                    <td class="list">
                        <?= $value['size']; ?>
                    </td>
                    <td class="list">
                        <a class="button" href="?page=edit&id=<?= $_GET['id']; ?>&delimg=<?= $key; ?>">DELL</a>
                    </td>
                </tr>
            <?php
        }
    }
?>
</table>
</div>
</div>
