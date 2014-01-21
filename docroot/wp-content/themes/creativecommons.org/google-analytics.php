<?php

	switch ($_SERVER['HTTP_HOST']) {
		case 'creativecommons.net':
			// For CC.net we don't want to track any OpenID stuff
			if ( ! (preg_match('/openid\/provider/', $_SERVER['REQUEST_URI']) ||
					preg_match('/openid\/provider/', $_SERVER['HTTP_REFERER']) ||
					preg_match('/o\/endpoint/', $_SERVER['REQUEST_URI'])) ) {
				$analytics_code = 'UA-2010376-4';
			} else {
				$analytics_code = '';
			}
			break;
		case 'labs.creativecommons.org':
			$analytics_code = 'UA-2010376-2';
			break;
		case 'search.creativecommons.org':
			$analytics_code = 'UA-2010376-3';
			break;
		case 'staging.creativecommons.net':
			$analytics_code = 'UA-2010376-23';
			break;
		case 'staging.creativecommons.org':
			$analytics_code = 'UA-2010376-22';
			break;
		case 'wiki.creativecommons.org':
			$analytics_code = 'UA-2010376-5';
			break;
		case 'wiki-staging.creativecommons.org':
			$analytics_code = 'UA-2010376-24';
			break;
		default:
			// Default to the GA code for CC.org
			$analytics_code = 'UA-2010376-1';
	}

?>

	<script type="text/javascript">
        	var _gaq = _gaq || [];
        	_gaq.push(['_setAccount', '<?php echo $analytics_code; ?>']);
        	_gaq.push(['_trackPageview']);

        	(function() {
                	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        	})();
	</script>

