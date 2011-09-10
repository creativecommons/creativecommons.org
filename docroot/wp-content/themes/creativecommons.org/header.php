<?php include 'header-top.php';?>
<body>
	<div id="top_bar">
		<div id="head-region" class="header-widget">
			<?php	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Header Region 0') ) : ?><?php endif; ?> 
		</div><!-- END head-widget -->
	</div><!-- END top_bar -->

	<div class="clear">&nbsp;</div>

	<a id="top"></a>
	<div id="header" class="container_16">
		<div class="container_16">
			<div class="grid_16 ">
				<h1 id="logo"><a href="<?php echo get_settings('home'); ?>"><span>Creative Commons</span></a></h1>

				<ul class="nav">
					<li><a href="/about">About</a></li>
					<li><a href="/weblog/">Blog</a></li>
					<li><a href="https://creativecommons.net/donate">Donate</a></li>
					<li><a href="http://wiki.creativecommons.org/FAQ">FAQ</a></li>
					<li><a href="http://wiki.creativecommons.org/">Wiki</a></li>
					<li><a href="http://wiki.creativecommons.org/CC_Affiliate_Network">International</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div id="page">

