<?php

function get_buckets ($bucket_type      = 'Bucket', 
                      $exclude_category = 'Case Studies, CC Store', 
                      $orderby          = 'rating',
                      $debug = false)
{
    $buckets = array();
    $bookmarks = get_bookmarks(array('orderby'        =>  $orderby,
                                     'category_name'  =>  $bucket_type ));
    // print_r($bookmarks);
    $excluded_categories = explode(', ', $exclude_category);
    // print_r($excluded_categories);
    if ( empty($excluded_categories) )
        $excluded_categories[] = $exclude_category;
    // print_r($excluded_categories);

    foreach ($bookmarks as $b) 
    {
        $do_not_save = false;
        $book = get_bookmark($b->link_id);
        // print_r($book);
        foreach ( $book->link_category as $term_id )
        {
            $link_terms = get_term_by('id', $term_id, 'link_category');
            //print_r($link_terms);
            if ( in_array($link_terms->name, $excluded_categories) )
            {
                $do_not_save = true;
            }
            $book->link_terms[] = $link_terms;
            // print_r($term);
        }
        if ( ! $do_not_save )
            $buckets[] = $book;
    }
    // print_r($buckets);
    return $buckets;
}

function get_button ($bucket, $link, $class = 'btn')
{
    $button_text            = '';
    $use_green_button       = false;
    $use_text_button        = false;

/* carousel

												<div class="bucket-follow"><a class="primary btn" href="<?php echo $carousel->link_url; ?>"><?php echo $button_text; ?></a></div>

** bucket normal button
												<div class="bucket-follow"><a class="btn" href="<?php echo $bucket->link_url; ?>"><?php echo $button_text; ?></a></div>

** bucket green button

										<div class="bucket-follow"><a class="btn primary" href="#">Choose a License</a></div>

** text button

<div class="bucket-follow"><a href="http
://wiki.creativecommons.org/CC_Affiliate_Network">Explore the Affiliate Network.
..</a></div>


*/


    if ( count($bucket->link_terms) > 1 )
    {
        foreach ($bucket->link_terms as $term)
        {
            $button_text = '';
            // check if 'Green Button ' at front
            $replace_ct = 0;
            $button_text = str_ireplace('Green Button ', '', $term->name, 
                                        $replace_ct);
            if ( $replace_ct != 0 ) 
            {
                $use_green_button = true;
                break;
            }

            $replace_ct = 0;
            $button_text = str_ireplace('Text Button ', '', $term->name, 
                                        $replace_ct);
            if ( $replace_ct != 0 ) 
            {
                $use_text_button = true;
                break;
            }

            $replace_ct = 0;
            $button_text = str_ireplace('Button ', '', $term->name, 
                                        $replace_ct);
            if ( $replace_ct != 0) 
                break;
        }
    }
    // display the right class for the button
    return '<div class="bucket-follow">' . 
           '<a ' . ($use_text_button ? '' : 
                   ( 'class="' . $class . ($use_green_button ? ' primary' : '')
                   ) ) .  
           '" href="' . $link . '">' . $button_text . '</a></div>';
}


function output_carousel () 
{
    $carousels = get_buckets('Carousel');

    if ( count($carousels) == 0 )
        return;
?>
					<div class="first row">
						<div class="carousel bucket">
							<div id="slides" class="inner">
								<div class="slides_container row">

<?php
    foreach ($carousels as $carousel) 
    {
?>
									<div class="slide">
										<div class="five columns alpha">
											<div class="content">
												<h6><?php echo $carousel->link_name; ?></h6>
												<p><?php echo $carousel->link_notes; ?></p>

                                                <?php echo get_button($carousel, $carousel->link_url, 'primary btn'); ?>
											</div> <!--! end of "content" -->
										</div> <!--! end of "five columns alpha" -->
										<div class="eleven columns omega">
											<div class="film">
												<a href="<?php echo $carousel->link_url; ?>"><img src="<?php echo $carousel->link_image; ?>" alt="<?php echo $carousel->link_name; ?>" /></a>
												<div class="caption">
                                                    <?php echo $carousel->link_description; ?>
												</div>
											</div> <!--! end of "film" -->
										</div> <!--! end of "eleven columns omega" -->
									</div> <!--! end of "slide" -->
<?php
    }
?>

								</div> <!--! end of "slides_container row" --> 
							</div> <!--! end of #slides -->
						</div> <!--! end of "carousel bucket" -->
					</div> <!--! end of "first row" -->

<?php

} // end of output_carousel


function output_buckets () 
{
    $buckets = get_buckets();
    // print_r($buckets);

    if ( count($buckets) == 0 )
        return;

    // classes for the six main buckets
    $top_columns    = array('five columns alpha',
                            'six columns',
                            'five columns omega',
                            'five columns alpha',
                            'five columns omega');
?>
					<div class="short row">
<?php
    $ct = 0;
    // print_r($buckets);
    foreach ($buckets as $bucket) 
    {
?>
						<div class="<?php echo $top_columns[$ct]; ?>">

						<div class="bucket">
						<div class="inner">
							<h3 class="title"><?php echo $bucket->link_name; ?></h3>
							<div class="content"> 
								<h6><?php echo $bucket->link_description; ?></h6>
                                <?php if ( !empty($bucket->link_image) )
                                {
                                ?>
													<div class="slide">
														<a href="https://creativecommons.net/content/1-buttons-set-5">
														<img src="<?php bloginfo('stylesheet_directory'); ?>/img/buttons_1_0_300x200.jpg" alt="CC Buttons" />
														</a>
													</div>
                                <?php
                                } 
                                ?>
                                <?php echo $bucket->link_notes; ?>


                                                <?php echo get_button($bucket, $bucket->link_url); ?>

							</div> <!--! end of "content" --> 
						</div> <!--! end of "inner" -->
						</div> <!--! end of "bucket" -->

						</div> <!--! end of top_columns[$ct] -->

<?php

    if ($ct == 2 ) 
    {
?>
					</div> <!--! end of "short row" -->

					<div class="tall row">
<?php
    }

    if ( $ct == 3  )
    {
        output_case_studies();
        output_store();
    }

    $ct++;
    }
?>
					</div> <!--! end of "tall row" -->

<?php

} // end of output_buckets


function output_case_studies ()
{
    $case_studies = get_buckets('Case Studies', '');
    //print_r($case_studies);
    if ( count($case_studies) > 0 )
    {
?>
						<div class="six columns">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">Case Studies</h3>
									<div class="content">
										<div id="case">
										<div class="studies">
<?php
    foreach ($case_studies as $cs)
    {
?>
									    <div class="slide">
											<div class="sample">
												<img src="<?php echo $cs->link_image; ?>" alt="<?php echo $cs->link_name; ?>"/>
											</div>

											<h5><?php echo $cs->link_name; ?></h5>
											<h6><?php echo $cs->link_description; ?></h6>

                                            <?php echo $cs->link_notes; ?>

										</div>

<?php
    }
?>
										</div> <!--! end of "studies -->
										</div> <!--! end of #case -->
										<div class="bucket-follow"><a href="http://wiki.creativecommons.org/Case_Studies">Read more Case Studies...</a></div>
									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "six columns" -->

<?php
    }
} // end of output_case_studies()

function output_store ()
{
    $store_items = get_buckets('CC Store', '');
    // print_r($store_items);
    if ( count($store_items) > 0 )
    {
?>
						<div class="five columns omega">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">CC Store</h3>
									<div class="content">
										<div class="sample">
											<div id="store">
												<div class="swag">
<?php

    foreach ($store_items as $si)
    {
    ?>
													<div class="slide">
														<a href="<?php echo $si->link_rss; ?>">
														<img src="<?php echo $si->link_image; ?>" alt="<?php echo $si->link_name; ?>" />
														</a>
													</div>
    <?php 
    }
    ?>
												</div>
											</div>
										</div>
										<h5><?php echo $si->link_description; ?></h5>
										<?php echo $si->link_notes; ?>
										<div class="bucket-follow"><a href="https://creativecommons.net/store/">Visit the Store</a></div>

									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "five columns omega" -->
        <?php
    }
}

output_carousel();
output_buckets();

?>
