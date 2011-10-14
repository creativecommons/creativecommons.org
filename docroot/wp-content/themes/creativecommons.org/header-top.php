<?php
    include 'meta.php';
?>
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>		<html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>		<html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" charset="<?php bloginfo('charset'); ?>" />
    <meta name="author" content="fabricatorz" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

	<link href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" title="Icon" type="image/x-icon" rel="icon" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-precomposed.png">

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style.css">

    <script src="<?php bloginfo('stylesheet_directory'); ?>/js/libs/modernizr-2.0.6.min.js"></script>

    <?php if (is_front_page() || is_404()) {?>
    <title>Creative Commons</title>
    <?php } else if (is_search()) { ?>
    <title>Search Creative Commons</title>
    <?php } else { ?>
    <title><?php wp_title(''); ?> - Creative Commons</title>
    <?php }?>

    <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig, sharing, sharism" />
    <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators. <?php if (is_single()) echo cc_post_excerpt() ?>" />

    <?php if (is_single()) { ?>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php } ?>

    <?php if ($category_name == "weblog" || is_front_page() ) { ?>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />
    <?php } else if (is_category()) { ?>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . '/categories/' . $category->slug ?>/feed/rss" />
    <?php } else if (is_tag()) { ?>
    <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_tag_link($tag_id); ?>/feed/rss" />
    <?php } ?>

	<?php wp_head(); ?>
</head>
