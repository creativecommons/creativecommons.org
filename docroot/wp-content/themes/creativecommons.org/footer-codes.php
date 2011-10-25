	<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php bloginfo('stylesheet_directory'); ?>/js/libs/jquery-1.6.2.min.js"><\/script>')</script>

	<!-- scripts concatenated and minified via build script -->
	<script defer src="<?php bloginfo('stylesheet_directory'); ?>/js/plugins.js"></script>
	<script defer src="<?php bloginfo('stylesheet_directory'); ?>/js/script.js"></script>
	<!-- end scripts -->

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
			 chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7 ]>
		<script defer src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->
