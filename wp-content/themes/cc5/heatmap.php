<?php
// via http://nevyan.blogspot.com/2006/12/free-website-click-heatmap-diy.html

$q = $_SERVER['argv'][0];

function save_file($message, $filename) {
	trim($message);
	$message = str_replace(" ","",$message);
	
	if ($message != ""){
		$message = "\n" . $message;
		$fp = fopen($filename, "a") or die("error opening");
		$write = fputs($fp, $message);
		fclose($fp);
	}
}

save_file($q, "heatmap.txt");
?>
