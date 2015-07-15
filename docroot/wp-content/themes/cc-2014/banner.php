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
	<a style="display: block;" href="https://donate.creativecommons.org/?utm_campaign=2015fund&utm_source=ccsite_header2015">
		<h1>Keep the internet creative, free and open.</h1>
		<button>Donate to Creative Commons</button>
	</a>
</div>