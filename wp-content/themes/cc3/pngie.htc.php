<?php 
// Get component file name 
$path = (array_key_exists("path", $_GET)) ? $_GET["path"] : ""; 
// basename() also strips \x00, we don't need to worry about ? and # in path: 
// Must be real files anyway, fopen() does not support wildcards 

//$path = ereg_replace("[[:alpha:]]+://([^/])", "", $path)

header("Content-type: text/x-component"); 
header("Content-Length: 2000"); 
header("Content-Disposition: inline; filename=pngie.htc.php");  

?>
<public:component>
<public:attach event="onpropertychange" onevent="propertyChanged()" />
<script>

// Fixes PNG transparency issues on IE


var supported = /MSIE (5\.5)|[6789]/.test(navigator.userAgent) && navigator.platform == "Win32";
var realSrc;
var blankSrc = "<?= $path ?>/transparent.gif";

if (supported) fixImage();

function propertyChanged() {
   if (!supported) return;

   var pName = event.propertyName;
   if (pName != "src") return;
   // if not set to blank
   if ( ! new RegExp(blankSrc).test(src))
      fixImage();
};

function fixImage() {
   // get src
   var src = element.src;

   // check for real change
   if (src == realSrc) {
      element.src = blankSrc;
      return;
   }

   if ( ! new RegExp(blankSrc).test(src)) {
      // backup old src
      realSrc = src;
   }

   // test for png
   if ( /\.png$/.test( realSrc.toLowerCase() ) ) {
      // set blank image
      element.src = blankSrc;
      // set filter
      element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" +
                                     src + "',sizingMethod='image')";
   }
   else {
      // remove filter
      element.runtimeStyle.filter = "";
   }
}

</script>
</public:component>
