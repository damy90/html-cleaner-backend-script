<?php
//read local spreadsheet and generate html
$separator = "\t";
$filetype = '.tsv';
$tableData = NULL;
$tablesDir = dirname(__FILE__).'/';//central directory with central refresh. To save files in current folder and implement local refresh use ./ as parameter and coppy refresh.php in each directory

function utf8getcsv(&$handle, $length, $separator){
	global $separator;
    if (($buffer = fgets($handle, $length)) !== false) {
        $result = explode($separator, $buffer );
		$num = count($result);
		$result[$num-1] = trim($result[$num-1]);//remove an extra newline \r\n
		return $result; 
    }
    return false;
}

function getTable($table){
	global $separator;
	global $filetype;
	global $tablesDir;
	//echo $tablesDir;
	//if the table is't loaded yet find the table url by name and save on the server
	if (!file_exists ( $tablesDir.$table.$filetype ))
	{
		//echo 'downloading';
		include 'tables-map.php';
		file_put_contents($tablesDir.$table.$filetype, fopen($path[$table], 'r'));
	}
	//open local table
	if( $tableData==NULL ){
		$tableData = array();
		$handle = fopen($tablesDir.$table.$filetype, "r");
		if ($handle !== FALSE) {
			while (($data = utf8getcsv($handle, 1000, $separator)) !== FALSE) {
				array_push( $tableData, $data );
			}	
		}
	}
	
	//use tables directly from Google Drive (slower but doesn't need the refresh script)
	/*if( $tableData==NULL ){
		$tableData = array();
		include 'tables-map.php';
		$handle = fopen($path[$table], "r");
		if ($handle !== FALSE) {
			while (($data = utf8getcsv($handle, 1000, $separator)) !== FALSE) {
				array_push( $tableData, $data );
			}	
		}
	}*/
	return $tableData;
}
function printTable($table){
	
	$tableData = getTable($table);
	if( $tableData ) {
		$len = count($tableData);
	    for( $row=0; $row<$len; $row++){
	    	$data = $tableData[$row];
	        $num = count($data);
			if ($row == 0){
				echo "<table>\r\n\t\t<thead>\r\n\t\t\t<tr>";
			}
			else{
	            echo "\t\t\t<tr>";
	        }
		        
	        for ($c=0; $c < $num; $c++) {
	            //echo $data[$c] . "<br />\n";
	            if(empty($data[$c]))
	               $value = "&nbsp;";
	            else{
	               $value = trim($data[$c]);
				}
            	if( $value!=='v' && $value!='h' && $value!='hv'){
	            	$rowspan = 1;
	            	for( $n=$row+1; $n<$len; $n++ ){
						if( trim($tableData[$n][$c])=='v' )
							$rowspan++;
						else
							break;
					}
					
					$colspan = 1;
	            	for( $m=$c+1; $m<$num; $m++ ){
						if( trim($tableData[$row][$m])=='h' )
							$colspan++;
						else
							break;
					}
					
	                if ($row == 0) 
                		echo '<th';
					else echo '<td';
	                if( $rowspan>1 )
						echo ' rowspan="'.$rowspan.'"';
					if( $colspan>1 )
						echo ' colspan="'.$colspan.'"';
	                echo '>'.$value;
	                if ($row == 0) 
                		echo '</th>';
	                else echo '</td>';
				}
			}
			if ($row == 0) {
	            echo "</tr>\r\n\t\t</thead>\r\n\t\t<tbody>\r\n";
	        }
	        else{
	            echo "</tr>\r\n";
	        }
	    }
	    
	    echo "\t\t</tbody>\r\n\t</table>\n";
	    fclose($handle);
	}
	else{
		echo "File not found:".$table.$filetype;
	}	
}
?>