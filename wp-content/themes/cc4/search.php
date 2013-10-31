<?php get_header(); ?>
    <div id="body">
      <div id="splash">

        <div id="splash-menu">
          
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div class="block" id="title">
        <h2>
          Search Results
        </h2>
          </div>
          <div  class="content-box" id="page">
           <div class="block hero">
          		<?php include (TEMPLATEPATH . '/searchform.php'); ?>
           </div>
          
<?php if (have_posts())  { ?>
<?php while (have_posts()) { the_post(); ?>

			<div class="block blogged">
				<h3 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
				<h4 class="meta"><small><?php the_time('l, F jS, Y') ?></small></h4>
				<?php the_excerpt() ?>
<? /*				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p> */ ?>
			</div>


<?php } ?>

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
<?php } else { ?>
  <h2>No search results found.</h2>

<?php } ?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
