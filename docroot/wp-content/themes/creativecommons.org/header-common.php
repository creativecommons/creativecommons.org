    <meta name="author" content="fabricatorz" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
	<link href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" title="Icon" type="image/x-icon" rel="icon" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-precomposed.png">

    <?php 
    if ( is_not_old_ie() )
    {
    ?>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style.css">
    <?php 
    }
    ?>

    <!--[if !IE]>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style.css">
    <![endif]-->

    <!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/ie8-and-down.css">
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="<?php bloginfo('stylesheet_directory'); ?>/js/libs/modernizr-2.0.6.min.js"></script>

    <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig, sharing, sharism" />
    <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators. <?php if ( function_exists(is_single) && function_exists(cc_post_excerpt) && is_single()) echo cc_post_excerpt() ?>" />
