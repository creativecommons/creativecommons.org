<?php // handle search stuff first

  if ($_GET['s'] && ($_GET['st'] == site)) {
    // site searchtype redirects to google query
    $query = $_GET['s'];
    header("Location:http://www.google.com/custom?q=" . $query . "&sa=search&cof=GIMP%3Ablack%3BT%3A%23333333%3BLW%3A162%3BALC%3Ared%3BL%3Ahttp%3A%2F%2Fcreativecommons.org%2Fimages%2Flogo_trademark.gif%3BGFNT%3A%2399999%3BLC%3A%235e715e%3BLH%3A40%3BBGC%3Awhite%3BAH%3Aleft%3BVLC%3A%238EA48E%3BS%3Ahttp%3A%2F%2Fcreativecommons.org%2F%3BGALT%3A%23666666%3BAWFID%3Afad503ba397c7a7f%3B&domains=creativecommons.org&sitesearch=creativecommons.org");
  }

// Cookie handling for the testimonial/thermometer test
// if (!isset($_COOKIE['cc_showtestimonial'])) {
//	$showTestimonial = rand(0, 1);
// 	setcookie('cc_showtestimonial', $showTestimonial, time() + 60 * 60 * 24 * 30);
// }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<?php if (is_home() || is_404()) {?>
  <title>Creative Commons</title>
  <?php } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <?php } else { ?>  
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <?php }?>

<?php /*  Not using any YUI stuff afaik - alex
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.2/build/container/assets/skins/sam/container.css" /> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.5.2/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.5.2/build/animation/animation-min.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.5.2/build/container/container-min.js"></script> 
 */ ?>

	<link href="<?php bloginfo('stylesheet_directory'); ?>/style.css?20101013" rel="stylesheet" type="text/css" />
	<link href="<?php bloginfo('stylesheet_directory'); ?>/print.css" rel="stylesheet" media="print" type="text/css" />

	<link href="<?php bloginfo('stylesheet_directory'); ?>/support.css?20101012" rel="stylesheet" type="text/css" />
 	<link href="/includes/total.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js?20101012"></script>
	
  <?php if (is_home() || ($category_name == "weblog")) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />
  <?php } else if (is_category()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_settings('home') . '/categories/' . $category->slug ?>/feed/rss" />
  <?php } else if (is_tag()) { ?>
  <link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo get_tag_link($tag_id); ?>/feed/rss" />
  <?php } ?>
  	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <?php if (is_single()) { ?>
  	<meta name="description" content="<?php cc_post_excerpt() ?>" />
  <?php } else { ?>
  	<meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />
  <?php } ?>

  <?php if (is_single()) { ?>
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
  <?php } ?>

  <?php wp_head(); ?>

	<script type="text/javascript">$ = jQuery.noConflict();</script>
  <?php if (is_page()) { ?>
<script src="/includes/jquery/jquery.carousel.min.js" type="text/javascript"></script>
  <?php } ?>
</head>

<body class="yui-skin-sam ccPage">
  <div id="globalWrapper">
    <div id="headerWrapper" class="box">
      <div id="headerLogo">
        <h1><a href="<?php echo get_option('home'); ?>"><span>Creative Commons</span></a></h1>
      </div>
      <?php require_once "nav.php"; ?>
<?php echo $tag_link; ?>    
      <!-- <div id="headerSearch">
        <form method="get" id="searchform" action="http://creativecommons.org/">
          <input type="text" name="s" id="s" size="30" class="inactive" />
          <input type="submit" id="searchsubmit" value="Go" />
        </form>
      </div> -->
    </div>
