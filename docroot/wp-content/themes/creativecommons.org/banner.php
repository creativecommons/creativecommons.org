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

<div id="top-banner">
	<a style="display: block; height: 100%; width: 100%;" href="https://donate.creativecommons.org/?utm_campaign=2013fund&amp;utm_source=<?php echo $analytics_source; ?>&utm_medium=site_header">
	<img src="/wp-content/uploads/2014/01/banner-matchinggifts.png" alt="Donate banner" />
	</a>
</div>
