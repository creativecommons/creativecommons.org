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
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', '<?php echo $analytics_code; ?>', 'auto');
	  ga('send', 'pageview');

	</script>