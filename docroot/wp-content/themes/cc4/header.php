<?php // handle search stuff first

  if ($_GET['s'] && ($_GET['st'] == site)) {
    // site searchtype redirects to google query
    $query = $_GET['s'];
    header("Location:http://www.google.com/custom?q=" . $query . "&sa=search&cof=GIMP%3Ablack%3BT%3A%23333333%3BLW%3A162%3BALC%3Ared%3BL%3Ahttp%3A%2F%2Fcreativecommons.org%2Fimages%2Flogo_trademark.gif%3BGFNT%3A%2399999%3BLC%3A%235e715e%3BLH%3A40%3BBGC%3Awhite%3BAH%3Aleft%3BVLC%3A%238EA48E%3BS%3Ahttp%3A%2F%2Fcreativecommons.org%2F%3BGALT%3A%23666666%3BAWFID%3Afad503ba397c7a7f%3B&domains=creativecommons.org&sitesearch=creativecommons.org");
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
 <head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <? if (is_home() || is_404()) {?>
  <title>Creative Commons</title>
  <? } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <? } else { ?>  
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <? }?>
  <meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
<?php if (is_single()) { ?>
	<meta name="description" content="<?php cc_post_excerpt() ?>" />
<?php } else { ?>
	<meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />
<?php } ?>
  
  <? if (is_home() || ($category_name == "weblog")) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />
  <? } else if (is_category()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . "/" . $category_name; ?>/feed/rss" />
  <? } ?>

  <?php if (is_single()) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php } ?>
  
  <link href="<?php bloginfo('stylesheet_directory'); ?>/style.css?4.6.1" rel="stylesheet" type="text/css" />
  <link href="<?php bloginfo('stylesheet_directory'); ?>/support.css?5.1" rel="stylesheet" type="text/css" />

  <!--[if IE ]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" /><![endif]-->
  <!--[if gte IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie7.css" /><![endif]--> 
  <?php /* wp_get_archives('type=monthly&format=link'); */ ?>
	<script src="/includes/icommons.js" type="text/javascript"></script>
	<script src="/includes/mv_embed/mv_embed.js" type="text/javascript"></script>

  <?php wp_head(); ?>
 </head>
 <body onload="">
 <a name="top"></a>
    <div id="header-wrapper">
      <div id="header-main" class="box">
        <span class="publish">
          <a href="<?php echo get_option('home'); ?>/license/" class="cc-actions">
            <span class="img">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/images/license-8.png" border="0" class="publish" alt="License your work" title="License your work" />
            </span> 
            <span class="option">License</span>Your Work
          </a>
        </span>
        <span class="find">
          <a href="http://search.creativecommons.org/" class="cc-actions">
            <span class="img">
              <img src="<?php bloginfo('stylesheet_directory'); ?>/images/find-8.png" border="0" alt="Find licensed works" title="Find licensed works" />
            </span>
            <span class="option">Search</span>CC Licensed Work
          </a>
        </span>
        
        <span class="logo"><a href="<?php echo get_option('home'); ?>"><span><img src="<?php bloginfo('stylesheet_directory'); ?>/images/cc-title-8.png" alt="creative commons" id="cc-title" border="0"/></span></a></span>
        
<?/*        <div id="strap">Share, Remix, Reuse &mdash; Legally</div> */?>
      </div>
    </div>

    <?php include_once( TEMPLATEPATH . "/nav.php" ); ?>
<? /*
	 <div id="campaign">
	  <div class="box">
	    <div class="title">
	     <a href="http://support.creativecommons.org/"><strong>Annual Fundraising Campaign</strong></a>
        </div>
        
	    <div class="progress" onclick="window.location = 'http://support.creativecommons.org';">
	     <span style="padding-right: <?= $campaign['css'] ?>px;">&nbsp;</span>
		 <div class="results"><a href="http://support.creativecommons.org">$<?= $campaign['matched'] ?> / $50,000</a></div>
	    </div> 
	    
	    
	  </div>
	</div>
*/ ?>
		<div class="clear">&nbsp;</div>
    <div class="box"><div id="wrapper-ie">
    <? /*
    <div class="jurisdictions">
      <h4><a href="/sitemap">Search Site</a>&nbsp;&nbsp;&nbsp;&nbsp;</h4><strong>|</strong>&nbsp;&nbsp;&nbsp;&nbsp;
      <h4><a href="/worldwide">Worldwide</a>&nbsp;</h4>
        <script type="text/javascript" src="/includes/jurisdictions.js"></script>
    </div>
    */ 
    ?>
    

