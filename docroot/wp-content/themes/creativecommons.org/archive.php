<?php
// Check if we're actually an international page in disguise, 
// and call up the correct template
$category = get_category($cat);

if ($category->category_parent == 21) {
  require (TEMPLATEPATH . '/international-page.php');
  exit();
}
get_header(); 
// Setup category details for template
 ?>
<body>
	<div id="container">
        <?php include 'page-nav.php'; ?>

        <div id="main" role="main">
            <div class="container">
                <div class="sixteen columns">

<div class="first row">
<div id="title">
	<?php if (is_month() || is_year()) { ?> 
	<h1 class="category">
		<a href="<?php echo get_category_link($cat);?>">
		<?php echo $category->name; ?>
		</a>
	</h1>
	<?php }?>
	<h1><?php wp_title('')?></h1>
</div>
</div><!-- end of first row -->
                    <div class="row"><!-- for about page -->
                        <div class="twelve columns alpha">

			<?php if (have_posts()) { 
				while (have_posts()) {
				the_post();?>
			<div class="blog" id="post-<?php the_ID(); ?>">
				<h2 class="title">
					<a href="<?php the_permalink() ?>">
					<?php if (in_category(4) || in_category(7)) { ?>CC Talks With: <?php } ?> 
					<?php the_title() ?>
					</a>
				</h2>
				<p class="meta"><?php the_author() ?>, <?php the_time('F jS, Y');?></p>
				<?php the_content("Read More..."); ?>
        <?php edit_post_link('Edit', '', ' |'); ?> <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?> 
				
				<?php if (get_the_tags()) { ?>
				<div class="postTags">
					<?php the_tags(); ?>
                    <br /><br /><br />
				</div>
				<?php } ?>
			</div>
			<?php } } ?>

            <br /><br />
			<?php
			# Add pretty pagination if the plugin PageNavi is installed,
			# otherwise just use the boring stuff.  nkinkade 2008-01-02
			if ( function_exists('wp_pagenavi') ) {
					wp_pagenavi();
			} else {
					posts_nav_link(' &mdash; ', 'previous page', 'next page');
			}
			?>

            </div><!-- end of twelve columns alpha -->

            <div class="four columns omega">
		    <div id="archives" class="well">
			    <p><strong><a href="<?php echo get_settings('home') . '/' . $category->slug; ?>/feed/rss">Subscribe to RSS</a></strong></p>
			    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()) ?>
		    </div>
            </div><!-- end of twelve columns omega -->
            </div><!-- end of row -->


                </div><!-- end of sixteen columns -->
            </div><!--! end of .container -->
		</div><!--! end of #main -->
<?php get_footer(); ?>

