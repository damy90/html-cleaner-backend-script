# Google Drive tables to clean HTML demo
Download google spreadsheets as HTML files, remove unneeded content (HTML tags and attributes), print clean HTML tables.

The same script can be adapted to clean any HTML or XML file.
## Conception
A client with an old static php site once asked for a way to edit  the price tables. Instead of switching to a CMS platform or teaching the client basic HTML syntax I came up with a small script to fetch spreadsheets from google drive and remove the unneeded content and styling.
## Fetching Google Drive tables
### Adding new google drive table to your site
* Create a new spreadsheet in google drive
* Publish the document as a web page from File => Publish to the web
* Copy the provided url to tables-map.php in the following format:
```PHP
<?php
	$path['1.1 РАБОТНИ КАЛЕНДАРИ с 3 тела'] = 'https://docs.google.com/spreadsheets/d/1gq8bXVg7nj-7jfuXTfk0C6seMTWu59y-AvVeoD0srkk/pubhtml';
	//$path['spreadsheet2']='https://docs.google.com/spreadsheet/pub?key=0At-BhHxB4VqLdDFOZGpGVTZ1S3IteWVBNG9qQkxSMHc&single=true&gid=0&output=txt';
	//...
?>
```
* Add the table to any php page:
```PHP
<?php
	include 'html-table-cleaner.php';
    printTable('1.1 РАБОТНИ КАЛЕНДАРИ с 3 тела');
?>
```
### Additional configuration
#### Cleaning up HTML/XML content
To specify which tags, attributes and contents will be kept edit the following lines in html-cleaner.php

```PHP
$allowed_attributes = array('colspan', 'rowspan');
//The content of the tags listed in $allowed_tags_content will not be deleted even if the tags are
$allowed_tags_content = array('table','thead','tbody','tr','td','tfoot','caption', 'body', 'html', 'div', 'br');
$allowed_tags = '<table><thead><th><tbody><tr><td><tfoot><caption>';
```
#### Caching HTML pages on the server (before cleaning)
To change caching options change the following function in html-cleaner.php

```php
function getTable($table)
{
    global $filetype;
    global $tablesDir;

    //Caching html files on the server. Comment the if statement to disable caching 
    //if the table isn't loaded yet find the table url by name and save on the server (for storing the pages locally)
    if (!file_exists($tablesDir.$table.$filetype)) {
        //echo 'downloading';
        include 'tables-map.php';
        file_put_contents($tablesDir.$table.$filetype, fopen($path[$table], 'r'));
    }

    //use $path[$table] in file_get_contents to download directly from google drive
    if ($html = file_get_contents($tablesDir.$table.$filetype)) {
        return $html;
    } else {
        echo "Could not load table";
    }
}
```
#### Cache cleaning and backup
* To clean the cache (delete all locally stored external .html files) call refresh.php

```HTML
<form action="/spreadsheets-to-html-demo-v2.0/refresh.php" method="post">
    <input type="submit" value="Refresh" title="Refresh all local tables">	
</form>
```

* To backup the current cached version of the files call backup.php. This will archive the remote html files in a zip file on the server and start a download on the client machine.
```HTML
<form action="/spreadsheets-to-html-demo-v2.0/backup.php" method="post">
    <input type="submit" value="Backup" title="Backup tables">	
</form>
```