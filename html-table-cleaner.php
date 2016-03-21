<?php
$filetype = '.htm';
$tablesDir = dirname(__FILE__).'/';//central directory with central refresh. To save files in current folder and implement local refresh use ./ as parameter and coppy refresh.php in each directory

$allowed_attributes = array('colspan', 'rowspan');
$allowed_tags_content = array('table','thead','tbody','tr','td','tfoot','caption', 'body', 'html', 'div', 'br');
$allowed_tags = '<table><thead><th><tbody><tr><td><tfoot><caption>';

function printTable($table)
{
    $html=getTable($table);
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");

    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $title=$dom->getElementsByTagName('span')->item(0)->nodeValue;
    echo '<h1>'.$title.'</h1>';

    $html=cleanHtml($dom);

    echo $html;
}

function getTable($table)
{
    global $filetype;
    global $tablesDir;

    //if the table is't loaded yet find the table url by name and save on the server (for storing the pages locally)
    if (!file_exists($tablesDir.$table.$filetype)) {
        //echo 'downloading';
        include 'tables-map.php';
        file_put_contents($tablesDir.$table.$filetype, fopen($path[$table], 'r'));
    }

    //use $path[$table] to download from google drive
    if ($html = file_get_contents($tablesDir.$table.$filetype)) {
        return $html;
    } else {
        echo "Could not load table";
    }
}

function cleanHtml($dom)
{
    global $allowed_attributes;
    global $allowed_tags_content;
    global $allowed_tags;

    //clean tags + content
    foreach ($dom->getElementsByTagName('*') as $node) {
        //TODO check if the current node has childnodes
        if (!in_array($node->nodeName, $allowed_tags_content) || $node->nodeValue==='') {
            $node->parentNode->removeChild($node);
        }
    }

    //clean attribures
    foreach ($dom->getElementsByTagName('*') as $node) {
        for ($i = $node->attributes->length -1; $i >= 0; $i--) {
            $attribute = $node->attributes->item($i);
            if (!in_array($attribute->name, $allowed_attributes)) {
                $node->removeAttributeNode($attribute);
            }
                
        }
    }
    $html=$dom->saveHTML();

    //the last of many script tags isn't deleted. The substring is an ugly hack
    $html=substr($html, 0, strpos($html, '<script>'));
    //clean tags & leave content
    $html = strip_tags($html, $allowed_tags);

    return $html;
}
