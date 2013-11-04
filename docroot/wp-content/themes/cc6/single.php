<?php 
// "Single" template will always, by definition, have a single post.
// I'm quite sure this will not change, except on opposites day, perhaps.
if (have_posts())  {
    the_post();
} else {
  require (TEMPLATEPATH . '/404.php');
  exit();
} ?>
<?php get_header(); ?>
<?php
// check if this single is a commoner or blog post
$is_commoner = (in_category(7) ? true : false);
$is_blog = ((in_category(1) || in_category(128)) ? true : false);

foreach (get_the_category() as $cat) {
 if ($cat->category_parent == 21) {
	 $jurisdiction_name = $cat->cat_name;
	 $jurisdiction_code = $cat->category_nicename;
 }
}
?>

<div id="title" class="container_16">

	<?php edit_post_link("Edit This Post", '<p class="alignright edit">', '</p>'); ?>
	<? if ($is_commoner) { ?>
	<h3 class="category">
		<? $cat = get_the_category(); (count($cat) > 1) ? $cat = $cat[0] : $cat = $cat[0]; ?>
		<a href="<?php echo get_option('home') . "/" . $cat->category_nicename . "/"; ?>">
		<? echo $cat->cat_name; ?>
		</a>
	</h3>
	<? } else if ($is_blog) { ?>
	<h3 class="category">
		<a href="<?php echo get_category_link(1);?>">
			News
		</a>
	</h3>
	<? } else if ($category_name == "press-releases") { ?>
	<h3 class="category">
		<a href="<?php echo get_settings('home') . "/" ?>press-releases/">
			Press Releases
		</a>
	</h3>
	<? } ?>

	<h1 class="grid_16">
		<?php the_title(); ?>
	</h1>
	
	<h4 class="grid_16 meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
</div>

<div id="content">
	<div class="container_16">
		<div class="grid_12" id="post-<?php the_ID(); ?>">
			<?php the_content(); ?>

			<div class="twitter">
				<? /* FIXME: use official buttons here, include facebook */ ?>
				<a href="http://twitter.com/home?status=<?php the_title() ?> - <?php the_permalink() ?> via @creativecommons">Share on Twitter</a>
				<a href="http://identi.ca/?action=newnotice&amp;status_textarea=<?php the_title() ?>%20-%20<?php the_permalink() ?>%20via%20@creativecommons">Share on Identi.ca</a>
			</div>

			<?php	dynamic_sidebar('Single Post');	?>

			<?php if (get_the_tags()) { ?>
			<div class="postTags"><?php the_tags(); ?></div>
			<?php } ?>

			<div class="comments"><?php if ($is_blog) comments_template(); ?></div>
		</div>
	</div>
</div>

<?php get_footer(); ?>

