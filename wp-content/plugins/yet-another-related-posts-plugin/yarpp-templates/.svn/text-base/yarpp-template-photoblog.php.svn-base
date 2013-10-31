<?php
/*
Template Name: Yet Another Photoblog
Description: Requires the Yet Another Photoblog plugin
Author: mitcho (Michael Yoshitaka Erlewine)
*/ ?>
<h3>Related Photos</h3>
<?php if (have_posts()):?>
<ol>
	<?php while (have_posts()) : the_post(); ?>
		<?php if (function_exists('yapb_is_photoblog_post')): if (yapb_is_photoblog_post()):?>
		<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php yapb_get_thumbnail(); ?></a></li>
		<?php endif; endif; ?>
	<?php endwhile; ?>
</ol>

<?php else: ?>
<p>No related photos.</p>
<?php endif; ?>
