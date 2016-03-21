<?php
	include 'html-table-cleaner.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Google Drive tables to pure HTML demo</title>
    <link href="styles.css" rel="stylesheet" />
    <meta charset="utf-8">
</head>
<body>
	<h1>Google Drive tables to pure HTML demo</h1>
	<h2>I am experimenting and may break the demo temporarily at any time</h2>
	<p>This PHP based demo shows how to embed simple Google Drive spreadsheets in an almost CMS like manner without all the extra dumb inline styles that are added when spreadsheets are exported to HTML.</p>
	<p>Source code can be found <a href="https://drive.google.com/folderview?id=0B9-BhHxB4VqLNVZrNzFRa0RIY2M&usp=sharing" target="_blank">here</a></p>
	<h2>Cleaned up table</h2>
    <p>All HTML content except for the table is cleaned. All styles and attributes are removed except for rowspan and colspan. Custom CSS styles are applied to the cleaned HTML</p>
    
	<?php
		printTable('1.1 РАБОТНИ КАЛЕНДАРИ с 3 тела');
		//echo '<form action="'.$tablesDir.'refresh.php" method="post">'
	?>
    
    <?php
	   //include 'html-table-cleaner.php';
		//echo '<form action="'.$tablesDir.'refresh.php" method="post">'
	?>
    
	<form action="/spreadsheets-to-html-demo-v2.0/refresh.php" method="post">
		<input type="submit" value="Refresh" title="Refresh all local tables">	
	</form>
	<form action="/spreadsheets-to-html-demo-v2.0/backup.php" method="post">
		<input type="submit" value="Backup" title="Backup tables">	
	</form>
    <h2>Original Table (iframe)</h2>
    <iframe  width='800' height='300' src="https://docs.google.com/spreadsheets/d/1gq8bXVg7nj-7jfuXTfk0C6seMTWu59y-AvVeoD0srkk/pubhtml?gid=0&amp;single=true&amp;widget=true&amp;headers=false"></iframe>
</body>
</html>