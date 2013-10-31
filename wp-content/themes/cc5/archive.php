<?php get_header(); 
// Setup category details for template
$category = get_category($cat); ?>
  <div id="mainContent" class="box">
    <div id="contentPrimary">
          	<div class="block" id="title">
			        <? if (is_month() || is_year()) { ?> 
              <h3 class="category">
                <a href="<?php echo get_category_link($cat);?>">
                 <?php echo $category->name; ?> 
                </a>
              </h3>
              <? }?>
              <h2><? wp_title('') ?></h2>
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

			<strong><a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) . '/rss';?>">Subscribe to RSS</a></strong><br/><br/>
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
