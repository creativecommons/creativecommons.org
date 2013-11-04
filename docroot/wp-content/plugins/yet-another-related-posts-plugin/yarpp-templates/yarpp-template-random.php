<?php
/*
Template Name: Random
Description: This template gives you a random other post in case there are no related posts
Author: mitcho (Michael Yoshitaka Erlewine)
*/ ?>
<h3>Related Posts</h3>
<?php if (have_posts()):?>
<ol>
	<?php while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a><!-- (<?php the_score(); ?>)--></li>
	<?php endwhile; ?>
</ol>

<?php else:
query_posts("orderby=rand&order=asc&limit=1");
the_post();?>
<p>No related posts were found, so here's a consolation prize: <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>.</p>
<?php endif; ?>
