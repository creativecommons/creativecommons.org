<?php get_header(); ?>

<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); 
  // check if page should have middle column
  $is_single_col = get_post_meta ($post->ID, "single_col", TRUE); ?>
  
  <div id="mainContent" class="box">
    <div id="contentPrimary">
          	<div class="block" id="title">
			        <? if ($post->post_parent) { 
			        $parent = cc_page_parent ($post); ?> 
			        <h3 class="category">
			          <a href="<?php echo get_permalink($post->post_parent); ?>">
			           <?= $parent->post_title ?>
			          </a>
			        </h3>
			        <? }?>
			        <h2><?php the_title(); ?></h2>
        		</div>
            <div class="block page" id="post-<?php the_ID(); ?>">
              <?php the_content("Read More..."); ?>
            </div>
<?php } }?>
    </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
