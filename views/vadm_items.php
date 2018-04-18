<?php
$ItemStart = 0;                   // Start position in table for items
$ItemCounts = 10;                 // Count of shoved items

// Set start position for LIMIT in select function
if($_GET['blank']) {
    $ItemStart = $_GET['blank'] * $ItemCounts - $ItemCounts;
}

// Get Items
$Items = $ccreateedit->get_rows($tbl1, $tbl2, 'select_items_filter', $FilterString, $ItemStart, $ItemCounts);
?>

    <table class="list">
        <tr class="list">
            <td class="list">#</td>
            <td class="list">Product Name</td>
            <td class="list">Edit</td>
            <td class="list">Delete</td>
        </tr>


<?php
$pi = $ItemStart;
foreach ($Items as $key => $value) {
    $pi++;
    ?>
    <tr class="list">
            <td class="list">
                <?= $pi; ?>
            </td>
            <td class="list">
                <?= $value['name']; ?>
            </td>
            <td class="list">
                <a class="button" href="?page=edit&id=<?= $key; ?>&select=<?= $_GET['select']; ?>&like=<?= $_GET['like'] ?>&popular=<?= $_GET['popular'] ?>">EDIT</a>
            </td>
            <td class="list">
                <a class="button" href="?page=delete&id=<?= $key; ?>">DELETE</a>
            </td>
        </tr>
    <?php
}
?>
    </table>
