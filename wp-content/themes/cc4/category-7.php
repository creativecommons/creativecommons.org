<?php get_header(); ?>
    <div id="body">
      <div id="splash">

        <div id="splash-menu">
          
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div class="block" id="title">
        <? if (is_month() || is_year()) { ?> 
        <h3 class="category">
          <a href="<?php echo get_settings('home') . "/" . $category_name; ?>">
            <? single_cat_title() ?>
          </a>
        </h3>
        <? }?>
        <h2>
          <?php single_cat_title(); ?>
        </h2>
        <div id="blurb"><?php echo category_description() ?></div>
          </div>
          <div id="alpha" class="content-box">
<?php if (have_posts())  { ?>
<?php while (have_posts()) { the_post(); ?>
            <div class="block" id="post-<?php the_ID(); ?>">
                <? if ($attach = cc_get_attachment ($post->ID)) { ?>
              <a href="<?php the_permalink() ?>">
		<img align="left" src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" class="commoner" border="0" width="150" />
              </a>
              <? } ?>
              <div class="excerpt">
                <h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title();?></a></h1>
                <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
                <?php the_excerpt(); ?>
                <?php edit_post_link('Edit', '', ''); ?>
              </div>
            </div>
<?php } }?>

            <div style="margin: 1ex;">
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

          </div>
          <div id="beta" class="content-box">
            <h4>Archives</h4>
            <ul class="archives">
            <?php cc_get_cat_archives(7, 'monthly', '', 'html', '', '', TRUE); ?>
            </ul>
            
            <?php /* Not used. Consider merging for new uber commoners page...
            <?php include ("featured/$category_name.php"); ?>
            */ ?>
            
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
