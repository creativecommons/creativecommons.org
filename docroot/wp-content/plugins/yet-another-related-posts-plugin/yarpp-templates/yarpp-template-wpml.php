<?php
/*
Template Name: WPML
Description: An example template for use with the WPML plugin
Author: mitcho (Michael Yoshitaka Erlewine)
*/

if (function_exists("icl_register_string")) {
	icl_register_string("Yet Another Related Posts Plugin","related posts header","Related Posts");
	icl_register_string("Yet Another Related Posts Plugin","no related posts message","No related posts.");
}

?><h3><?php echo (function_exists("icl_t") ? icl_t("Yet Another Related Posts Plugin","related posts header","Related Posts") : "Related Posts B") ?></h3>
<?php if (have_posts()):?>
<ol>
	<?php while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
	<?php endwhile; ?>
</ol>
<?php else: ?>
<p><?php echo (function_exists("icl_t") ? icl_t("Yet Another Related Posts Plugin","no related posts message","No related posts.") : "No related posts. B") ?></p>
<?php endif; ?>
