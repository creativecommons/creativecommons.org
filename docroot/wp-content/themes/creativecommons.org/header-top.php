<?php
    include 'meta.php';
?>
<?php include 'header-doctype.php'; ?>
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" charset="<?php bloginfo('charset'); ?>" />
    <?php include 'header-common.php'; ?>

    <?php if (is_front_page() || is_404()) {?>
    <title>Creative Commons</title>
    <?php } else if (is_search()) { ?>
    <title>Search Creative Commons</title>
    <?php } else { ?>
    <title><?php wp_title(''); ?> - Creative Commons</title>
    <?php }?>

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
