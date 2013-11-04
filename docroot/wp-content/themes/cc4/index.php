<?php get_header(); ?>

    <div id="body">

      <div id="content">
        <div id="main-content">
		 <?php /* echo cc_current_feature(); */ ?>

			<div style="float: left; width: 365px; margin-right:15px;">
				<a href="http://creativecommons.org/weblog/entry/12540"><img src="/images/splash/flickr100m-banner2.jpg" alt="100 million CC images on Flickr!" border="0" /></a>
			</div>
	<div class="block hero" style="clear: none; margin-left: 375px;" id="title">
            <div id="blurb"><?= cc_intro_blurb() ?></div>
            
		  </div>
		<div id="banner">
		 <a href="/weblog/entry/14023">Noncommercial Use Study, Round Two &mdash; Information &raquo;</a>
		</div>
	  <div id="alpha" class="content-box">
            <h4><a href='/weblog'>CC News</a></h4>
<?php // Get the latest 5 posts that aren't in the worldwide category. ?>
<?php 
  while (have_posts()) { 
    the_post(); 
    
    static $count = 0;
    if ($count == "5") { break; } else {
      if (!in_category(1) && !is_single()) { continue; }?>
            <div class="block blogged" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<?php the_permalink() ?>">
                  <?php if (in_category(4) || in_category(7)) { ?>Featured Commoner: <?php } ?>
                  <?php the_title(); ?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ' |'); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
            </div>
<?php $count++; } }?>
            <ul class="archives">
	    <!-- START nkinkade mods -->
            <!--<li><h3><a href="/weblog/archive">Weblog Archives</a></h3></li>-->
            <li><h3><a href="/weblog">Weblog Archives</a></h3></li>
	    <!-- END nkinkade mods -->
	    <li><h3><a href="/weblog/rss">RSS Feed</a></h3></li></ul>
	    </div>
	    <div id="beta" class="content-box">
	     <h4><a href='http://planet.creativecommons.org/jurisdictions/'>Jurisdiction News</a></h4>
	     <?php cc_build_external_feed('Planet CC'); ?>
	     <div style="margin-left: 1ex;"><a href="http://planet.creativecommons.org/jurisdictions/">[More jurisdiction news]</a></div>
	    </div>
          </div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
