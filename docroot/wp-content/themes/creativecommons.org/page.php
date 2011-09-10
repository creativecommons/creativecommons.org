<?php get_header(); ?>

<?php 
if (have_posts()) { 
		the_post(); ?>

<?php
if (strcmp(get_post_meta($post->ID, "page_category", true), "about") == 0) {
	$nav = wp_nav_menu(array('menu' => 'ABOUT', 'container_class' => 'subnav', 'echo' => false));

	echo <<<HTML
	<div class="container_16">$nav</div>
HTML;
}
?>

		<div id="title" class="container_16">
			<?php edit_post_link("Edit This Page", '<p class="alignright edit">', '</p>'); ?>
			<h1 class="grid_16">
				<?php the_title(); ?>
			</h1>
		</div>

		<div id="content">
			<?php the_content(); ?>

			<div class="container_16"><div class="grid_16 edit"><?php edit_post_link("Edit This Page", '<p>', '</p>'); ?></div>
		</div>

<?php 
} 
?>
	</div>
<?php get_footer(); ?>
