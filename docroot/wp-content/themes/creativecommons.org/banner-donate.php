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
<?php
    echo"		<a id='donate-link' href='https://creativecommons.net/donate/?utm_campaign=fall2011&amp;utm_source=${analytics_source}&amp;utm_medium=site_header' title='Donate to Creative Commons'></a>";
?>
        	<div class="container">
                <div class="row">
                <div class="eleven columns">
<?php
    echo "            <h4><a href='https://creativecommons.net/donate/?utm_campaign=fall2011&amp;utm_source={$analytics_source}&amp;utm_medium=site_header'>CC's Annual Campaign is going on now!  Donate.</a></h4>";
?>
		</div>
		<div class="three columns omega">
<?php
    echo "            <h5><a href='https://creativecommons.net/donate/?utm_campaign=fall2011&amp;utm_source={$analytics_source}&amp;utm_medium=site_header' class='btn'>Donate now</a></h5>";
?>
                </div>
                </div>
                </div>
</div>
