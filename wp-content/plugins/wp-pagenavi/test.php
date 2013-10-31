<?php
// Template Name: WPN Debug

get_header();

query_posts( array( 'post_type' => 'post', 'paged' => get_query_var( 'paged' ) ) );
?>

<div id="primary">
	<div id="content" role="main">

	<ul>
	<?php while ( have_posts() ) : the_post(); ?>
		<li><?php the_title(); ?>
	<?php endwhile?>
	</ul>

	<?php wp_pagenavi(); ?>
	<?php echo wp_pagenavi( array( 'echo' => false ) ); ?>

	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
