<?php
// Calculate the total count of pages for pagination
$total = intval(($Rows - 1) / $ItemCounts) + 1;

// Get the current page
if($_GET['blank']) {
    $page = $_GET['blank'];
}
else {
    $page = 1;
}

// Fix the page if it is uot of the limits
/*
if(empty($page) || $page < 0) {
    $page = 1;
}
if($page > $total) {
    $page = $total;
}
*/
//$ItemStart = $page * $ItemCounts - $ItemCounts;

// Output the navigation left and right links
if ($page != 1) {
    $pervpage = '<a class="pagination__item" href=?blank=1'.'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'><<</a><a class="pagination__item" href=?blank='. ($page - 1).'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'><</a> ';
}

if ($page != $total) {
    $nextpage = '<a class="pagination__item" href=?blank='.($page + 1).'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'>></a><a class="pagination__item" href=?blank='.$total.'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'>>></a>';
}

// Output left and right button pages
if($page - 2 > 0) {
    $page2left = '<a class="pagination__item" href=?blank='.($page - 2).'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'>'.($page - 2).'</a>';
}
if($page - 1 > 0) {
    $page1left = '<a class="pagination__item" href=?blank='.($page - 1).'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'>'.($page - 1).'</a>';
}
if($page + 2 <= $total) {
    $page2right = '<a class="pagination__item" href=?blank='.($page + 2).'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'>'.($page + 2).'</a>';
}
if($page + 1 <= $total) {
    $page1right = '<a class="pagination__item" href=?blank='.($page + 1).'&select='.$_GET['select'].'&like='.$_GET['like'].'&popular='.$_GET['popular'].'>'.($page + 1).'</a>';
}

// Output full  pagination stack
echo '<div class="pagination">'.$pervpage.$page2left.$page1left.'<span class="pagination__item pagination__item-focus">'.$page.'</span>'.$page1right.$page2right.$nextpage.'</div>';
echo '<div>('.count($Items).' / '.$Rows.')</div>';
?>
