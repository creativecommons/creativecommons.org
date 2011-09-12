<?php
/**
 * Template Name: HOME DEV PAGE
 *
 */
// search handler
if ($_GET['stype']) {
	if ($_GET['stype'] == "content") {
		header("Location: http://search.creativecommons.org/?q=" . $_GET['q']);
	} else {
		header("Location: /?s=" . $_GET['q']);
	}
}
?>
<?php get_header(); ?>
<?php the_post(); ?>
<body>
	<div id="container">
        <?php include 'page-nav.php'; ?>

        <div id="main" role="main">
            <div class="container">
                <div class="sixteen columns">
                <?php 
                    // if ( $_SERVER["REQUEST_URI"] == '/' ||
                    //      $_SERVER["REQUEST_URI"] == '/index.php' ) {
                        include 'homedev-carousel.php'; 
                        include 'homedev-buckets.php'; 

                    // } else { 
                    ?>

			<?php // the_content(); ?>
			<?php // edit_post_link("Edit This Page", '<p class="edit">', '</p>'); ?>
            <?php // } ?>

                </div>
            </div><!--! end of .container -->
		</div><!--! end of #main -->

<?php get_footer(); ?>

