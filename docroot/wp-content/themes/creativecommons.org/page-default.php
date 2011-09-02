<?php
/*
Template Name: Default 12col Grid
*/
/*
 * Theme Name: 960 Base Theme
 * Theme URI: http://960basetheme.kiuz.it
 * Description: Wordpress theme based on 960 Grid System
 * Author: Domenico Monaco
 * Author URI: http://www.kiuz.it
 * Version: 0.5
 */
?>

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
			<div class="container_16">
				<div class="grid_12">
					<?php the_content(); ?>
				</div>
			</div>
			<div class="container_16"><div class="grid_16 edit"><?php edit_post_link("Edit This Page", '<p>', '</p>'); ?></div>
		</div>

<?php 
} 
?>
	</div>
<?php get_footer(); ?>
