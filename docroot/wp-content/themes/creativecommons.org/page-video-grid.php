<?php
/**
 * Template Name: Video Grid
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
			            <?php the_content(); ?>
			            <?php edit_post_link("Edit This Page", '<p>', '</p>'); ?>

                    </div>
<?php } ?>
                </div>
            </div><!--! end of .container -->
		</div><!--! end of #main -->
<?php get_footer(); ?>
