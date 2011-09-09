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
<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>		<html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>		<html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<!-- Use the .htaccess and remove these lines to avoid edge case issues.
			 More info: h5bp.com/b/378 -->
	<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

	<?php if (is_front_page() || is_404()) {?>
  <title>Creative Commons</title>
  <?php } else if (is_search()) { ?>
  <title>Search Creative Commons</title>
  <?php } else { ?>
  <title><?php wp_title(''); ?> - Creative Commons</title>
  <?php }?>

	<meta name="keywords" content="creative commons, commons, free culture, open source, attribution, non-commercial, share-alike, no derivatives, lessig" />
	<meta name="description" content="Creative Commons licenses provide a flexible range of protections and freedoms for authors, artists, and educators." />

	<!-- Mobile viewport optimized: j.mp/bplateviewport -->
	<!--<meta name="viewport" content="width=device-width,initial-scale=1">-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
	
	<link href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" title="Icon" type="image/x-icon" rel="icon" />
	
	<!-- For iPhone 4 with high-resolution Retina display: -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-114x114-precomposed.png">
	<!-- For first-generation iPad: -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-72x72-precomposed.png">
	<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
	<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon-precomposed.png">

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/style.css">
	
	<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

	<!-- All JavaScript at the bottom, except this Modernizr build incl. Respond.js
			 Respond is a polyfill for min/max-width media queries. Modernizr enables HTML5 elements & feature detects; 
			 for optimal performance, create your own custom Modernizr build: www.modernizr.com/download/ -->
	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/libs/modernizr-2.0.6.min.js"></script>

	<!-- <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" /> -->
	<!-- <link rel="stylesheet" href="/stylesheet" type="text/css" /> -->

	<!-- <link href="<?php bloginfo('stylesheet_directory'); ?>/support.css" rel="stylesheet" type="text/css" /> -->
 	<!-- <link href="http://creativecommons.org/includes/total.css" rel="stylesheet" type="text/css" /> -->

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('home')?>/weblog/rss" />

	<!-- <script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/jquery.min.js"></script> -->
	<!-- <script type="text/javascript" charset="utf-8" src="<?php bloginfo('stylesheet_directory'); ?>/site.js?20110208"></script> -->

	<?php wp_head(); ?>
</head>
<?php the_post(); ?>
<body class="home">
	<div id="container">
		<header> 
		
		<div class="topbar">
			<div class="topbar-inner">
				<div class="container">
				<a id="skip-navigation" href="#start-content" title="Skip Navigation">Skip Navigation</a>
				<a href="http://creativecommons.org" title="Home"><span id="home-link">Home</span><span id="home-button"></span></a>
				<div id="logo"><span>Creative Commons</span></div>
					<ul id="short-menu" class="nav">
						<li class="dropdown">
							<a href="#about" class="dropdown-toggle">Menu</a>
								<ul class="menu-dropdown">
									<li><a href="http://creativecommons.org/about">About</a></li>
									<li><a href="http://creativecommons.org/licenses/">Licenses</a></li>
									<li><a href="http://creativecommons.org/about/cc0">Public Domain</a></li>
									<li><a href="https://creativecommons.net/donate/">Support CC</a></li>
									<li><a href="http://creativecommons.org/culture">Projects</a></li>
									<li><a href="http://creativecommons.org/weblog">News</a></li>
								</ul>
						</li>
					</ul>
					<ul id="wide-menu" class="nav">
				    <li class="dropdown">
				    	<a href="http://creativecommons.org/about" class="dropdown-toggle">About</a>
				    	<ul class="menu-dropdown">
						    <li><a href="http://creativecommons.org/about">Our Mission</a></li>
						    <li><a href="https://creativecommons.org/about/history">History of CC</a></li>
						    <li class="divider"></li>
						    <li><a href="http://creativecommons.org/who-uses-cc">Who Uses CC?</a></li>
						    <li><a href="http://wiki.creativecommons.org/Case_Studies">Case Studies</a></li>
						    <li><a href="http://creativecommons.org/videos/">Videos about CC</a></li>
						    <li class="divider"></li>
						    <li><strong><span>The Team</span></strong></li>
						    <li><a href="https://creativecommons.org/board">Board of Directors</a></li>
						    <li><a href="https://creativecommons.org/staff">Staff</a></li>
						    <li><a href="https://creativecommons.org/fellows">Fellows</a></li>
						    <li><a href="http://wiki.creativecommons.org/CC_Affiliate_Network">Affiliate Network</a></li>
						    <li><a href="https://creativecommons.org/opportunities">Job Opportunities</a></li>
						    <li class="divider"></li>
						    <li><a href="http://wiki.creativecommons.org/FAQ">Frequently Asked Questions</a></li>
						    <li><a href="http://creativecommons.org/contact">Contact Us</a></li>
						    
				      </ul>
				    </li>
				    <li class="dropdown">
				    	<a href="http://creativecommons.org/licenses/" class="dropdown-toggle">Licenses</a>
				    	<ul class="menu-dropdown">
						    <li><a href="http://creativecommons.org/licenses/">About the Licenses</a></li>
						    <li><a href="http://creativecommons.org/choose/">Choose a License</a></li>
						    <li><a href="http://labs.creativecommons.org/demos/search/?beta=1">Find Licensed Content</a></li>
						    <li><a href="http://wiki.creativecommons.org/Marking">HOWTO Use Licensed Content</a></li>
						    <li class="divider"></li>
						    <li><span>Licensors</span></li>
						    <li><a href="http://wiki.creativecommons.org/FAQ#Do_I_need_to_sign_something_or_register_to_obtain_a_Creative_Commons_license.3F">Do I need to register my work?</a></li>
						    <li><a href="http://wiki.creativecommons.org/Marking/Creators">Marking my work with CC licenses</a></li>
						    <li class="divider"></li>
						    <li><span>Licensees</span></li>
						    <li><a href="http://wiki.creativecommons.org/FAQ#Will_Creative_Commons_give_me_permission_to_use_a_work.3F">Getting permission</a></li>
						    <li><a href="http://wiki.creativecommons.org/FAQ#How_do_I_properly_attribute_a_Creative_Commons_licensed_work.3F">Giving attribution</a></li>
						    <li><a href="http://wiki.creativecommons.org/Marking/Users">Marking work with CC licenses</a></li>
						    <li class="divider"></li>
						    <li><span>Developers</span></li>
						    <li><a href="http://wiki.creativecommons.org/Integrate">App Integration</a></li>
						    <li><a href="http://wiki.creativecommons.org/Developer_Challenges">Developer Challenges</a></li>
						    <li><a href="http://wiki.creativecommons.org/Translate">Translation</a></li>
				      </ul>
				    </li>
				    <li class="dropdown">
				    	<a href="http://creativecommons.org/about/cc0" class="dropdown-toggle">Public Domain</a>
				    	<ul class="menu-dropdown">
						    <li><a href="http://creativecommons.org/about/cc0">About CC0 Public Domain Dedication</a></li>
						    <li><a href="http://creativecommons.org/choose/zero/">Choose CC0 Public Domain Dedication </a></li>
						    <li class="divider"></li>
						    <li><a href="http://creativecommons.org/about/pdm">About Public Domain Mark</a></li>
						    <li><a href="http://creativecommons.org/choose/mark/">Use Public Domain Mark</a></li>
						 </ul>
				    </li>
				    
				    <li class="dropdown">
				    	<a href="https://creativecommons.net/donate/" class="dropdown-toggle">Support CC</a>
				    	<ul class="menu-dropdown">
				    		<li><a href="https://creativecommons.net/donate/">Donate to Creative Commons</a></li>
						    <li class="divider"></li>
				    		<li><a href="https://creativecommons.net/store/">Buy CC Merchandise</a></li>
				    		<li><a href="https://creativecommons.net/supporters/">Our Supporters</a></li>
				    		<li><a href="https://creativecommons.net/corporate/">Corporate Giving</a></li>
				    		<li><a href="https://creativecommons.net/figures/">Facts &amp; Figures </a></li>
				    	</ul>
				    </li>
				    
				    <li class="dropdown">
				    	<a href="http://creativecommons.org/culture" class="dropdown-toggle">Projects</a>
				    	<ul class="menu-dropdown">
						    <li><a href="http://creativecommons.org/culture">Culture</a></li>
						    <li><a href="http://creativecommons.org/education">Education</a></li>
						    <li><a href="http://creativecommons.org/science">Science</a></li>
						    <li><a href="http://creativecommons.org/government">Government</a></li>
						    <li class="divider"></li>
						    <li><a href="http://wiki.creativecommons.org/LRMI">Learning Resource Metadata Initiative (LRMI) </a></li>
						    <li><a href="http://creativecommons.org/taa-grant-program">Gates OPEN</a></li>
				      </ul>
				    </li>
				    <li class="dropdown">
				    <a href="http://creativecommons.org/weblog" class="dropdown-toggle">News</a>
				    	<ul class="menu-dropdown">
						    <li><a href="http://creativecommons.org/weblog">Blog</a></li>
						    <li class="divider"></li>
						    <li><a href="http://creativecommons.org/interviews">Interviews</a></li>
						    <li><a href="http://wiki.creativecommons.org/Events">Events</a></li>
						    <li><a href="https://creativecommons.net/civicrm/mailing/subscribe?reset=1&gid=121">CC Newsletter</a></li>
						    <li class="divider"></li>
						    <li><a href="http://creativecommons.org/about/press">Press Room</a></li>
						 </ul>
					</li>
				</ul>
				<form action="/" id="search_form">
					<input type="hidden" name="stype" value="site" id="find_site" />
					<input type="text" name="q" placeholder="Search" />
					<input type="submit" id="glass" title="Search" value="Submit">
				</form>
				</div>
			</div>
		</div>

		</header>

        <div id="main" role="main">
            <div class="container">
                <div class="sixteen columns">
                <?php 
                    if ( $_SERVER["REQUEST_URI"] == '/' ||
                         $_SERVER["REQUEST_URI"] == '/index.php' ) {
                        include('home-guts.php'); 

                    } else { 
                    ?>

			<?php the_content(); ?>
			<?php edit_post_link("Edit This Page", '<p class="edit">', '</p>'); ?>
            <?php } ?>

                </div>
            </div><!--! end of .container -->
		</div><!--! end of #main -->

<?php get_footer(); ?>

