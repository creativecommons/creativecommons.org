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

<div id="title" class="container_16">
	<? if (is_month() || is_year()) { ?> 
	<h3 class="category">
		<a href="<?php echo get_category_link($cat);?>">
		<?php echo $category->name; ?>
		</a>
	</h3>
	<? }?>

	<h1 class="grid_16"><? wp_title('')?></h1>
</div>

<div id="content">
	<div class="container_16">
		<div class="grid_12">
			<?php if (have_posts()) { ?>
			<?php
				while (have_posts()) {
				the_post();?>
			<div class="blog" id="post-<?php the_ID(); ?>">
				<h2 class="title">
					<a href="<? the_permalink() ?>">
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
				</div>
				<?php } ?>
			</div>
			<?php } } ?>
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
		<div id="archives" class="grid_3 prefix_1">
			<strong><a href="<?php echo get_settings('home') . '/' . $category->slug; ?>/feed/rss">Subscribe to RSS</a></strong><br/><br/>
			<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar()) ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>

