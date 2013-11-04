<?php // handle search stuff first

  if ($_GET['s'] && ($_GET['st'] == site)) {
    // site searchtype redirects to google query
    $query = $_GET['s'];
    header("Location:http://www.google.com/custom?q=" . $query . "&sa=search&cof=GIMP%3Ablack%3BT%3A%23333333%3BLW%3A162%3BALC%3Ared%3BL%3Ahttp%3A%2F%2Fcreativecommons.org%2Fimages%2Flogo_trademark.gif%3BGFNT%3A%2399999%3BLC%3A%235e715e%3BLH%3A40%3BBGC%3Awhite%3BAH%3Aleft%3BVLC%3A%238EA48E%3BS%3Ahttp%3A%2F%2Fcreativecommons.org%2F%3BGALT%3A%23666666%3BAWFID%3Afad503ba397c7a7f%3B&domains=creativecommons.org&sitesearch=creativecommons.org");
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Creative Commons</title>
<?php /*
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.6.0/build/container/assets/skins/sam/container.css" /> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/animation/animation-min.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/container/container-min.js"></script> 
  <script type="text/javascript" src="http://yui.yahooapis.com/2.6.0/build/cookie/cookie-min.js"></script> 
 */ ?>
	<link href="<?php bloginfo('stylesheet_directory'); ?>/index.css?20101012" rel="stylesheet" type="text/css" />
    <link href="<?php bloginfo('stylesheet_directory'); ?>/support.css?20101012" rel="stylesheet" type="text/css" /> 
 	<link href="/includes/total.css" rel="stylesheet" type="text/css" />

	<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_directory'); ?>/style-ie.css" /><![endif]-->

	<script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js?20101012"></script>
		
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />
	
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
  <meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
  <meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />

	<!-- Google Website Optimizer tracking code -->
	<script>
		_udn = "none"; 
		_ulink = "1";
		_uhash = "off";	</script>
<!-- Google Website Optimizer Control Script -->
<script>
function utmx_section(){}function utmx(){}
(function(){var k='4106174143',d=document,l=d.location,c=d.cookie;function f(n){
if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.indexOf(';',i);return c.substring(i+n.
length+1,j<0?c.length:j)}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;
d.write('<sc'+'ript src="'+
'http'+(l.protocol=='https:'?'s://ssl':'://www')+'.google-analytics.com'
+'/siteopt.js?v=1&utmxkey='+k+'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='
+new Date().valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"></sc'+'ript>')})();
</script>
<!-- End of Google Website Optimizer Control Script -->
	
  <?php wp_head(); ?>
	<script type="text/javascript">$ = jQuery.noConflict();</script>
</head>

<body class="yui-skin-sam">
  <div id="globalWrapper">
    <div id="headerWrapper" class="box">
      <div id="headerLogo">
        <h1><a href="/"><span>Creative Commons</span></a></h1>
      </div>
      <div id="headerIntro">
        <h4>Share, Remix, Reuse &mdash; Legally</h4>
        <p>Creative Commons is a nonprofit organization that increases<br/>sharing and improves&nbsp;collaboration. <a href="http://creativecommons.org/about/what-is-cc">Learn More &raquo;</a></p>
      </div>
      <?php require_once "nav.php"; ?>
      <div id="headerSearch">
        <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
          <input type="text" name="s" id="s" size="30" class="inactive" />
          <input type="submit" id="searchsubmit" value="Search" />
        </form>
      </div>
    </div>
    
    <div id="mainContent">
      <div id="topContent" class="box">
		<div id="ccBlurb" >
		 <?php include "index-head-campaign.php";?>
     </div>
      <div id="triple" class="box">
        <div id="ccContent" class="columnBox">
	      <div id="ccTools" class="columnBox">
	        <div class="stdColumn ccTool nohelpLink" id="search">
	          <h2 class="find"><a href="http://search.creativecommons.org/">Find &raquo;</a></h2>
	          <p><a href="http://search.creativecommons.org/">Find <strong>licensed</strong> works you can share, remix, or&nbsp;reuse.</a></p>
	        </div>
			<div class="stdColumn ccTool nohelpLink" id="network">
	          <h2 class="join"><a href="https://creativecommons.net/donate?utm_source=ccorg&utm_medium=homepage&utm_campaign=fall2009">Donate &raquo;</a></h2>
			  <p><a href="https://creativecommons.net/donate?utm_source=ccorg&utm_medium=homepage&utm_campaign=fall2009">Invest in the <strong>future</strong> of creativity and knowledge &mdash; <strong>Donate&nbsp;Today!</strong></a></p>
	        </div>
	        <div class="stdColumn ccTool nohelpLink lastColumn" id="license">
	          <h2 class="license"><a href="/choose">License &raquo;</a></h2>
	          <p><a href="/choose">Use our <strong>free</strong> tools to inform people how they can reuse and share your creative works.</a></p>
	        </div>
	      </div>  
      </div>
      </div>
      <div class="box">
        <div id="ccFeatures" class="columnBox">
        <div id="ccINews">
          <div id="latestNews" class="stdColumn">
            <h4 class="titleStrip subTitle"><a href="<?php echo get_category_link(1);?>">Commons News</a></h4>
            <ul>
<?php
			  query_posts('cat=1');
              while (have_posts()) {
                the_post();
                
                static $count = 0;
                if ($count == "7") { break; } else {
                  if ((!in_category(1) && !is_single()) ) { continue; }
                  
                  if (in_category('notice')) {
                    $liClass = "notice";
                    $noticeTitle = get_post_meta($post->ID, "notice_title", true);
                  }
                  ?>
                  <li class="<?php echo $liClass ?>">
                    <h5 class="postTitle">
                      <a href="<?php the_permalink() ?>">
                        <?php if (in_category(4) || in_category(7)) { ?>CC Talks With: <?php } ?>
                        
                        <?php if (!$noticeTitle) { the_title(); } else { echo $noticeTitle; } ?>
                      </a>
                    </h5>
                    <p><small><?php the_time('F jS, Y')?></small></p>
                  </li>
              <?php
                  $liClass = null;
                  $noticeTitle = null;
                  $count++;
                }
              } ?>
            </ul>
            <p><a href="/weblog/rss"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png" border="0" alt="Feed" /></a><a href="<?php echo get_category_link(1);?>"><strong>Read more...</strong></a></p>
          </div>
          <div class="stdColumn">
            <h4 class="titleStrip subTitle"><a href="http://planet.creativecommons.org/">International Community News</a></h4> 
            <ul>
              <?php 
              $inews = cc_build_external_feed('CC Planet', 5, 0); 
              
              foreach ($inews as $item) {
                $date = date('F dS, Y', $item['date']);
echo <<<HTML
              <li>
                <div class="flag">
                  <a href="/international/{$item['country_code']}/">
                    <img src="http://creativecommons.org/images/international/{$item['flag_code']}.png" alt="{$item['flag_code']}" class="country" />
                  </a>
                </div>
                <h5><a href="{$item['link']}">{$item['title']}</a></h5>
                <p><small>$date</small></p>
              </li>
HTML;
              }
              ?>
            </ul>
            <p><a href="http://planet.creativecommons.org/atom.xml"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/feed.png" border="0" alt="Feed" /></a><a href="http://planet.creativecommons.org/"><strong>Read more...</strong></a></p>
            
          </div>
        </div>

        <div id="ccLinks">
          <?php /* Saving this for later. (09/04/23)
          <div id="donateBlock">
            <a href="http://creativecommons.net/">Help Build the Commons<br/><strong>Support CC</strong></a>
          </div>
          */ ?>
		  <p style="text-align: center;"><a href="http://creativecommons.net/store"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/store-btn.png" alt="store" border="0" /></a></p>
		  <div class="infoLinks">
            <h4 class="titleStrip subTitle">Information</h4>
            <ul class="ccContent">
				<li><a href="/about/licenses/">The Licenses</a></li>
				<li><a href="http://wiki.creativecommons.org/Newsletter">Newsletter</a></li>
				<li><a href="/about/events">Events</a></li>
				<li><a href="https://creativecommons.net/supporters">Supporters</a></li>
				<li><a href="/about/opportunities">Jobs</a></li>
				<li><a href="http://wiki.creativecommons.org/Developers">Developers</a></li>
			</ul>
          </div>
		  <div class="infoLinks">
			<h4 class="titleStrip subTitle">&nbsp;</h4>
			<ul class="ccContent">
				<li><a href="http://wiki.creativecommons.org/Case_Studies">Case Studies</a></li>
				<li><a href="/interviews">CC Talks With...</a></li>
				<li><a href="/international">International</a></li>
				<li><a href="http://creativecommons.org/education">Education</a></li>
				<li><a href="http://sciencecommons.org/">Science</a></li>
				<li><a href="/contact">Contact</a></li>
			</ul>

<? /* Killing international dropdown from homepage for now...
			  <li>
				<div id="help_international_list" class="sideitem topright">
				 <div class="bd">
				   <select id="international" name="international" onchange="window.location = this.options[this.selectedIndex].value">    
				     <option value="">Select a jurisdiction</option>
				     <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
				   </select>
				 </div>
				</div>
				</li> */ ?>
            </ul>
          </div>
        </div>
      </div>
<?php /*
      <!-- YUI -->
      <div id="help_license">
	    <div class="bd">
	      <p>A CC license marks your work with the freedoms of your choice. This notifies the public how your work can be reused, cutting out the middleman.</p>
	    </div>
	  </div>
	  <div id="help_search">
	    <div class="bd">
	      <p>Search millions of licensed works, and discover content you can use in your next great project.</p>
	    </div>
	  </div>
	  <div id="help_network">
	    <div class="bd">
	      <p>Creative Commons needs your support to help build a participatory culture, in which everyone can actively engage in the creativity that surrounds us.</p>
	    </div>
	  </div>
 */ ?>
	  </div>	
    </div>
 </div>
<?php 
$extra_footer = <<<SCRIPT
<!-- Google Website Optimizer Tracking Script -->
<script type="text/javascript">
if(typeof(_gat)!='object')document.write('<sc'+'ript src="http'+
(document.location.protocol=='https:'?'s://ssl':'://www')+
'.google-analytics.com/ga.js"></sc'+'ript>')</script>
<script type="text/javascript">
 try {
		 var gwoTracker=_gat._getTracker("UA-9998295-1");
		 gwoTracker._setDomainName("none");
		 gwoTracker._setAllowLinker(true);
		 gwoTracker._trackPageview("/4106174143/test");
 }catch(err){}</script>
		 <!-- End of Google Website Optimizer Tracking Script -->


SCRIPT;
			  include "footer.php";
			  /*get_footer();*/ ?>
