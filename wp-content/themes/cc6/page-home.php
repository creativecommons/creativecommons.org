<?php
// search handler
if ($_GET['stype']) {
	if ($_GET['stype'] == "content") {
		header("Location: http://search.creativecommons.org/?q=" . $_GET['q']);
	} else {
		header("Location: /?s=" . $_GET['q']);
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />

	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<?php if (is_front_page() || is_404()) {?>
  <title>Creative Commons</title>
  <?php } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <?php } else { ?>
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <?php }?>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" href="/stylesheet" type="text/css" />
	<!--[if IE ]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" /><![endif]--> 

	<link href="<?php bloginfo('stylesheet_directory'); ?>/support.css" rel="stylesheet" type="text/css" />
 	<link href="http://creativecommons.org/includes/total.css" rel="stylesheet" type="text/css" />

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />

	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.min.js"></script>
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js?20110208"></script>

	<?php wp_head(); ?>
</head>
<?php the_post(); ?>
	<body class="home">
		<a id="top"></a>
		<div id="header">
			<div class="container_16">
				<div class="grid_16">
					<h1 id="logo"><a href="<?php echo get_settings('home'); ?>"><span>Creative Commons</span></a></h1>

					<ul class="nav">
						<li><a href="/about">About</a></li>
						<li><a href="/weblog">Blog</a></li>
						<li><a href="https://creativecommons.net/donate">Donate</a></li>
						<li><a href="http://wiki.creativecommons.org/FAQ">FAQ</a></li>
						<li><a href="http://wiki.creativecommons.org/">Wiki</a></li>
						<li><a href="http://wiki.creativecommons.org/CC_Affiliate_Network">International</a></li>
					</ul>
				</div>
			</div>
			<div class="container_16" id="intro">
				<div class="grid_10" id="mission">
					<?php echo get_post_meta(get_the_ID(), "cc_mission", true); ?>
				</div>
				<div id="search_and_buttons" class="grid_6">
					<div class="search_container">
						<form method="get" action="/" id="search_form">
							<div>
								<strong>
									<input type="radio" checked name="stype" value="content" id="find_content" /> <label for="find_content">Find licensed content</label>
									&nbsp;&nbsp;&nbsp;
									<input type="radio" name="stype" value="site" id="find_site" /> <label for="find_site">Search site</label> 
								</strong>
							</div>
							<input type="text" class="search_text" name="q" />
							<input type="submit" class="search_submit" value="Search"/>
						</form>
					</div>
					<div><a class="grid_3 alpha button" href="/about/">Learn More...</a></div>
					<div style=""><a class="grid_3 omega button" href="/choose/">Choose License</a></div>
				</div>
			</div>
		</div>

		<div id="page">
			<?php the_content(); ?>
			<?php edit_post_link("Edit This Page", '<p class="edit">', '</p>'); ?>
		</div>

<?php get_footer(); ?>

