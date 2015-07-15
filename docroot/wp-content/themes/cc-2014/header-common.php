    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="globalsign-domain-verification" content="Ld-LezcptQDO8upxQ8oQKI5FOFhQWj-9s3pbS89jlP" />
	<link href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" title="Icon" type="image/x-icon" rel="icon" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-precomposed.png">

    <?php 
    if(! preg_match('/MSIE [1-8]/i', $_SERVER['HTTP_USER_AGENT']))
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
	<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="<?php bloginfo('stylesheet_directory'); ?>/js/libs/modernizr-2.0.6.min.js"></script>


<style>
 @media screen and (max-width: 770px) {
   #email-signup-2014 form * { text-align: center;}
   #email-signup-2014 label { font-size: 14px !important; width: auto; height: auto !important;}
   #email-signup-2014 input { width: 100%; padding-top: 5px;}
 }

 @media screen and (min-width: 771px) {
   #email-signup-2014 {height: 58px;}
   #email-signup-2014 label { font-size: 18px; margin: 0px 0px 0.75em; color: #000; width: auto; padding-right: 20px; text-align: left; font-weight: normal !important;}
 }

 @media screen and (min-width: 940px) {
   #email-signup-2014 label { font-size: 28px; margin: 0px 0px 0.75em; color: #000; width: 540px; text-align: left; font-weight: normal !important;}
 }
</style>

    <meta name="keywords" content="creative commons, commons, free culture, free software, open source, attribution, non-commercial, share-alike, no derivatives, ryan merkley, copyleft, lessig, sharing" />
    <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators. <?php if ( function_exists(is_single) && function_exists(cc_post_excerpt) && is_single()) echo cc_post_excerpt() ?>" />
