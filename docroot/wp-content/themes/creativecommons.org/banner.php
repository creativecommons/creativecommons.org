<?php

	// We need to switch the Analytics source based on which site this banner 
	// shows up on.
	switch ($_SERVER['HTTP_HOST']) {
		case 'wiki.creativecommons.org': 
			$analytics_source = 'ccwiki';
			break;
		case 'search.creativecommons.org':
			$analytics_source = 'ccsearch';
			break;
		case 'labs.creativecommons.org':
			$analytics_source = 'cclabs';
			break;
		case 'creativecommons.net':
			$analytics_source = 'ccnet';
			break;
		default:
			$analytics_source = 'ccorg';
	}

?>

<div id="top-banner" style="background-color: transparent; ">

        
            <?php

            $i = rand(1,2);

if ($i == 1) {
?>

	<a style="display: block; " href="https://donate.creativecommons.org/?utm_campaign=2014fund&utm_source=ccorg1&utm_medium=site_header&utm_medium=site_header">
	<img src="/wp-content/uploads/2014/11/websitebanner2014_teal.png" style="max-height: 100%; max-width: 100%; " alt="Donate banner" />
<?php
}

else {

?>
	<a style="display: block; " href="https://donate.creativecommons.org/?utm_campaign=2014fund&utm_source=ccorg2&utm_medium=site_header&utm_medium=site_header">
	<img src="/wp-content/uploads/2014/11/websitebanner2014_veal.png" style="max-height: 100%; max-width: 100%; " alt="Donate banner" />

<?php 

}

?>


	</a>
</div>
