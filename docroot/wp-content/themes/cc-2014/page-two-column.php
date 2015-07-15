<?php
/**
 * Template Name: Two Columns
 *
 */
get_header(); 
?>
<body>
	<div id="container">
        <?php include 'page-nav.php'; ?>

        <div id="main" role="main">
            <div class="container">
                <div class="sixteen columns">
<?php 
if (have_posts()) { 
		the_post(); ?>

		            <div class="first row">
                        <h1><?php the_title(); ?></h1>
                    </div><!--! end of "first row" -->
                    <div class="row"><!-- for about page -->
                        <div class="eight columns alpha"><!-- for about page -->
			            <?php the_content(); ?>
			            <?php edit_post_link("Edit This Page", '<p>', '</p>'); ?>
                        </div>
                        <?php
                        $second_column = get_post_meta( $post->ID, 'second_column', true );

                        if ( !empty($second_column) ) :
                        ?>
                        <div class="eight columns omega">
                        <?php echo $second_column; ?>
                        </div>
                        <?php endif; ?>

                    </div>
<?php } ?>
                </div>
            </div><!--! end of .container -->
		</div><!--! end of #main -->
<?php get_footer(); ?>
