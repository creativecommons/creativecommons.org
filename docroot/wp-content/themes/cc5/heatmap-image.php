<?
Header ("Content-Type: image/png");

$width = 1024;
$height = 4300;
$im = ImageCreate ($width,$height);
$red = ImageColorAllocate ($im, 255, 0, 0);
$white = ImageColorAllocate ($im, 255, 255, 255);
$black = ImageColorAllocate ($im,0, 0, 0);
$blue = ImageColorAllocate ($im, 0, 0, 255);
$gray = ImageColorAllocate ($im, 0xC0, 0xC0, 0xC0);

ImageFill ( $im , 0 , 0 , $black );

$file = "heatmap.txt";
$fp = fopen ( $file, 'r' ) or die ("error opening file");
$file = fread ($fp,filesize ($file));
$splitted = explode ("\n", $file);

for ($i = 0; $i < count ($splitted); $i++){
	if (!is_string ($splitted[$i])) return false;
	
	parse_str ($splitted[$i], $vars);
	
	$x = $vars['x'];
	$y = $vars['y'];
	imagesetpixel ($im, $x, $y, $white);
	//echo "str: " . $splitted[$i] . " x: " . $vars['x'] . " y: " . $vars['y'] . "<br/>";
}

Imagepng ($im);
imagedestroy ($im);
?>
