<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <? if (is_month() || is_year()) { ?> 
        <h3 class="category">
          <a href="<?php echo get_settings('home') . "/" ?>press-releases/">
           press releases 
          </a>
        </h3>
        <? }?>
        <h1><? wp_title('') ?></h1>
        <div id="splash-menu">
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); ?>
            <div class="post" id="post-<?php the_ID(); ?>">
              <h1 class="title"><a href="<? the_permalink() ?>"><?the_title()?></a></h1>
              <h4 class="meta"><?php the_time('F jS, Y')?></h4>
              <div class="clearer"></div>
              <?php the_excerpt(); ?>
	      <a href="<? the_permalink() ?>">Read More...</a>
              <?php edit_post_link('| Edit', '',''); ?>
            </div>
<?php } }?>
            <?php posts_nav_link(' &mdash; ', 'previous page', 'next page'); ?>
          </div>
          <div id="features">
            <h4>Archives</h4>
            <ul class="archives">
            <?php cc_get_cat_archives(6, 'monthly', '', 'html', '', '', TRUE); ?>
            </ul>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
