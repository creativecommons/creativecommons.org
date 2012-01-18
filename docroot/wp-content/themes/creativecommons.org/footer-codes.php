	<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php bloginfo('stylesheet_directory'); ?>/js/libs/jquery-1.6.2.min.js"><\/script>')</script>
    <script type="text/javascript">
    var j = $.noConflict();
    </script>

	<!-- scripts concatenated and minified via build script -->
	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/plugins.js"></script>
	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/script.js"></script>
	<!-- end scripts -->

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
			 chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7 ]>
		<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

	<?php
	switch ($_SERVER['HTTP_HOST']) {
		case 'creativecommons.net':
			$analytics_code = 'UA-201076-4';
			break;
		case 'search.creativecommons.org':
			$analytics_code = 'UA-201076-3';
			break;
		case 'wiki.creativecommons.org':
			$analytics_code = 'UA-201076-5';
			break;
		case 'labs.creativecommons.org':
			$analytics_code = 'UA-201076-2';
			break;
		default:
			// Default to the GA code for CC.org
			$analytics_code = 'UA-201076-1';
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

