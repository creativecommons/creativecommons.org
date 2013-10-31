<?php get_header(); 
// Setup category details for template
?>
  <div id="mainContent" class="box">
    <div id="contentPrimary">
			<div class="block" id="title">
			  <h3 class="category">Tag</h3>
              <h2><?php single_tag_title('') ?></h2>
        		</div>
            <div id="blocks">            
            <?php if (have_posts())  { ?>
            <?php while (have_posts()) { 
              the_post(); ?>
              <div class="block blog sideContentSpace" id="post-<?php the_ID(); ?>">
                <h1 class="title">
                  <a href="<? the_permalink() ?>">
                   <?php if (in_category(4) || in_category(7)) { ?>CC Talks With: <?php } ?> 
                   <?php the_title() ?>
                  </a>
                </h1>
                <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
                <?php the_content("Read More..."); ?>
                <?php edit_post_link('Edit', '', ' |'); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> 
<?php if (get_the_tags()) { ?>
				<div class="postTags">
<?php
		the_tags(); 
?>
				</div>
<?php } ?> 
              </div>
            <?php } }?>
            
            <?php
            # Add pretty pagination if the plugin PageNavi is installed,
            # otherwise just use the boring stuff.  nkinkade 2008-01-02
            if ( function_exists('wp_pagenavi') ) {
                wp_pagenavi();
            } else {
                posts_nav_link(' &mdash; ', 'previous page', 'next page');
            }
            ?>
            </div>

            <div id="archives">

			<strong><a href="<?php echo get_tag_link($tag_id) . '/feed/rss';?>">Subscribe to RSS</a></strong><br/><br/>
<!--			  <h4>Archives</h4> 
              <ul class="archives">
                <?php cc_get_cat_archives($cat, 'monthly', '', 'html', '', '', TRUE); ?>
              </ul>
-->
<?php if ( !function_exists('dynamic_sidebar')
        || !dynamic_sidebar() ) ?>


            </div>


    </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
