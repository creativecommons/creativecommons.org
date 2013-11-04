<?php get_header(); ?>

<?php if (have_posts())  { ?>
<?php while (have_posts()) { 
  the_post(); 
  // check if page should have middle column
  $is_single_col = get_post_meta ($post->ID, "single_col", TRUE); ?>
  
    <div id="body">
      <div id="splash">
         <? if ($post->post_parent) { 
           $parent = cc_page_parent ($post); ?> 
          <h3 class="category">
            <a href="./../">
              <?= $parent->post_title ?>
            </a>
          </h3>
          <? }?>
        <h1><?php the_title(); ?></h1>
        <div id="splash-menu">
          <? /*cc_list_pages($post->ID);*/ ?>
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="<? /*($is_single_col && 0) ? 'page' : 'blog'*/ ?>page">
            <div class="post" id="post-<?php the_ID(); ?>">
              <?php the_content("Read More..."); ?>
            </div>
<?php } }?>
          </div>
        <? if (!$is_single_col && 0) { ?>
          <div id="features">
            <ul>
          <? /*wp_list_pages ("title_li=&depth=2");*/ ?>
	<?php echo wpfm_create("elsewhere",true,'list',true); ?>  
            </ul>
          </div>
        <? } ?>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
