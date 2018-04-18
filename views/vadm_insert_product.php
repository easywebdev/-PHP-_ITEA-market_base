<?php
    require_once '../controllers/ccreateedit.php';
    $Types = $ccreateedit->get_unique($tbl1, 'type');
?>

<h2 class="h2">Select Product Type</h2>
<div class="bloc_mb-20px">
    <form class="" method="post">
        <select name="select">
            <option name="none" value="" <?php if(!$_POST['select']) { echo 'selected'; } ?>>none</option>
            <?php
            foreach ($Types as $key => $value) {
                ?>
                <option name="<?= $value; ?>" value="<?= $value; ?>" <?php if($_POST['type'] && $_POST['select'] == $value) {echo 'selected';} ?>><?= $value ?></option>
                <?php
            }
            ?>
        </select>
        <input type="submit" name="type" value="SELECT TYPE">
    </form>
</div>

<?php
if($_POST['select']) {
    require_once '../controllers/arrays.php';

    $ItemType = $_POST['select'];

    // Find file name for require_once and Set the Class Name
    foreach ($arrays->filename as $key => $value) {
        if($key == $ItemType) {
            $file = $value;                    // file name without .php
            $classname = ucfirst($file);       // Class name, it is build from file name
            break;
        }
    }

    require_once "../controllers/$file.php";
    $NewItem = new $classname();                                                  // Create new object

    $FormElements = $NewItem->formElements;
    $postName = 'create';

    require_once '../views/vadm_form.php';
}
if($_POST['create']) {
    require_once "../controllers/{$_POST['fileitem']}.php";
    $objItem = new $_POST['classname'];
    $objItem->addItem($tbl1, $_POST);
    $add_msg = 'Product was Add successfully';
    echo '<div class="">' . $add_msg . '</div>';
}
?>
