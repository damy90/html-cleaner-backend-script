<?php
//delete local spreadsheets
include'tables-map.php';
foreach ($path as $key => $value) {
    unlink($key.'.htm');
}
header("Location: index.php"); /* Redirect browser */
exit();
