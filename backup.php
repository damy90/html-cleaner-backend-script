<?php
$today = getdate();
$filetype = '.htm';
include'tables-map.php';
//get latest version
foreach ($path as $key => $value) {
    file_put_contents($key.$filetype, fopen($path[$key], 'r'));
}
//zip and download
$zip = new ZipArchive;
$download = 'tables-backup-'.$today['mday'].'-'.$today['mon'].'-'.$today['year'].'.zip';
$zip->open($download, ZipArchive::CREATE);
foreach ($path as $key => $value) { /* Add appropriate path to read content of zip */
    $zip->addFile($key.$filetype);
    //echo $key;
}
$zip->close();
header('Content-Type: application/zip');
header("Content-Disposition: attachment; filename = $download");
header('Content-Length: ' . filesize($download));
header("Location: $download");
