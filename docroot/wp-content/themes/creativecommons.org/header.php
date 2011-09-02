<?php
/*
 * Theme Name: 960Base Theme SIMPLE
 * Theme URI: http://960basetheme.kiuz.it
 * Description: Wordpress theme based on 960 Grid System
 * Author: Domenico Monaco
 * Author URI: http://www.kiuz.it
 * Version: 1.0.0 BETA
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
	<?php if (is_404()) {?>
  <title>Creative Commons</title>
  <?php } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <?php } else { ?>
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <?php }?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>?20110103" type="text/css" media="screen" />
	<link rel="stylesheet" href="/stylesheet" type="text/css" />
	<!--[if IE ]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" /><![endif]--> 


  <?php if ($category_name == "weblog") { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />
  <?php } else if (is_category()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . '/categories/' . $category->slug ?>/feed/rss" />
  <?php } else if (is_tag()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_tag_link($tag_id); ?>/feed/rss" />
  <?php } ?>
	
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <?php if (is_single()) { ?>
  	<meta name="description" content="<?php cc_post_excerpt() ?>" />
  <?php } else { ?>
  	<meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />
  <?php } ?>

  <?php if (is_single()) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php } ?>

	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.carousel.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js"></script>


	<?php wp_head(); ?>
</head>

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

