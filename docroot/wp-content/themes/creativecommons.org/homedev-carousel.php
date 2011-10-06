					<div class="first row">
						<div class="carousel bucket">
							<div id="slides" class="inner">
								<div class="slides_container row">
									<div class="slide">
										<div class="five columns alpha">
											<div class="content">
												<h6>A Culture of Sharing</h6>
												<p>Creative Commons is building a culture of sharing.</p>
												<p>By <strong>allowing your work to be available</strong> to millions of other creators on the web, you might be responsible for <strong>the next big thing.</strong></p>
												<p>Learn more by <strong>watching this video</strong>!</p>

												<div class="bucket-follow"><a class="primary btn" href="<?php bloginfo('home');?>/videos/a-shared-culture">Watch the Video</a></div>
											</div> <!--! end of "content" -->
										</div> <!--! end of "five columns alpha" -->
										<div class="eleven columns omega">
											<div class="film">
												<a href="<?php bloginfo('home');?>/videos/a-shared-culture"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/2251780221_5a21c2591a_o.jpg" alt="A Shared Culture" /></a>
												<div class="caption">
													<p>A Shared Culture is a short video about CC by renowned filmmaker Jesse Dylan. <a href="<?php bloginfo('home');?>/videos/a-shared-culture">Watch it now</a>.</p>
												</div>
											</div> <!--! end of "film" -->
										</div> <!--! end of "eleven columns omega" -->
									</div> <!--! end of "slide" -->
									<div class="slide">
										<div class="five columns alpha">
											<div class="content">
												<h6>Global Summit</h6>

												<p>For three days from 16-18 September 2011, the Creative Commons Global Summit, <strong>Powering an Open Future</strong>, will bring together the CC community in Warsaw, Poland</p>

												<p><strong>Registration for the Summit is open!</strong> You can register now.<p>
												<div class="bucket-follow"><a class="primary btn" href="http://wiki.creativecommons.org/Global_Summit_2011">Learn more</a></div>
											</div> <!--! end of "content" -->
										</div> <!--! end of "five columns alpha" -->
										<div class="eleven columns omega">
											<div class="film">
												<a href="http://wiki.creativecommons.org/Global_Summit_2011"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/ccline.png" alt="Creative Commons Global Summit 2011" /></a>
												<div class="caption">
													<p>The CC Global Summit is taking place 16–17 September 2011 in Warsaw Poland. <a href="http://wiki.creativecommons.org/Global_Summit_2011">Learn more!</a></p>
												</div>
											</div><!--! end of "film" -->
										</div><!--! end of "eleven columns omega" -->
									</div> <!--! end of "slide" -->
									<div class="slide">
										<div class="five columns alpha">
											<div class="content">
												<h6>The Power of Open</h6>
												<p>Stories of creators sharing knowledge, art, &amp; data.</p>
												<p>The stories in <strong>The Power of Open</strong> demonstrate the breadth and creativity of the individuals and organizations using CC.</p>
												<p>Learn more by <strong>reading the book</strong>!</p>

												<div class="bucket-follow"><a class="primary btn" href="http://thepowerofopen.org/">Read the Book</a></div>
											</div> <!--! end of "content" -->
										</div> <!--! end of "five columns alpha" -->
										<div class="eleven columns omega">
											<div class="film">
											<a href="<?php bloginfo('home');?>/weblog/entry/27742"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/poo.jpg" alt="The Power of Open" /></a>
												<div class="caption">
													<p>The Power of Open: Stories of creators sharing knowledge, art, & data using Creative Commons. <a href="http://thepowerofopen.org/">Read it now</a>.</p>
												</div>
											</div><!--! end of "film" -->
										</div><!--! end of "eleven columns omega" -->
									</div> <!--! end of "slide" -->
								</div> <!--! end of "slides_container row" --> 
							</div> <!--! end of #slides -->
						</div> <!--! end of "carousel bucket" -->
					</div> <!--! end of "first row" -->

<?php


function get_buckets ($bucket_type = 'Bucket', $orderby = 'rating')
{

    $buckets = get_bookmarks(array('orderby'        =>  $orderby,
                                   'category_name'  =>  $bucket_type ));

$top_columns    = array('five columns alpha',
                        'six columns',
                        'five columns omega');

$bottom_columns = array();

foreach ($buckets as $buck) {
    // print_r($buck);
    // echo $buck->term_id;
    $book = get_bookmark($buck->link_id);
    print_r($book);
    foreach ( $book->link_category as $term_id )
    {
        $term = get_term_by('id', $term_id, 'link_category');
        print_r($term);
    }
    echo "-----\n";
}
}

get_buckets();
// get_buckets('Carousel');
?>


					<div class="short row">
						<div class="five columns alpha">

						<div class="bucket">
						<div class="inner">
							<h3 class="title">Learn</h3>
							<div class="content"> 
								<h6>What is Creative Commons?</h6>
								<p>Creative Commons helps you get your ideas out there. We're creating a world where content is open for use by everyone.</p>

								<p><strong>Learn about the licenses</strong> we offer to get started!</p>

								<div class="bucket-follow"><a class="btn" href="#">Learn about CC</a></div>
							</div> <!--! end of "content" --> 
						</div> <!--! end of "inner" -->
						</div> <!--! end of "bucket" -->

						</div> <!--! end of "five columns alpha" -->
						<div class="six columns">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">License</h3>
										<div class="content">
										<h6>How can I license my work?</h6>
										<p>Licensing your work so others can use it is simple. <strong>Just tell us a bit about yourself</strong> and what you are submitting.</p>

										<p><strong>License here</strong> and we'll get you our famous CC logos for your work!</p>

										<div class="bucket-follow"><a class="btn primary" href="#">Choose a License</a></div>
									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "six columns" -->
						<div class="five columns omega">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">Explore</h3>
										<div class="content">
										<h6>Looking to create?</h6>
										<p>Looking for music, video, writing, code, or other creative works?</p><p><strong>Creative Commons has got you covered</strong>. Search for creative work through sources like Google and Flickr right here.</p>

										<div class="bucket-follow"><a class="btn" href="#">Start Creating</a></div>
									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "five columns omega" -->
					</div> <!--! end of "short row" -->

					<div class="tall row">
						<div class="five columns alpha">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">Global Network</h3>
									<div class="content">
										<div class="sample">
										</div>

										<h5>CC Affiliate Network</h5>
										<h6>Promoting CC Around the World</h6>

										<p>The CC Affiliate Network consists of 100+ affiliates working working in over 70 jurisdictions to support and promote CC activities around the world.</p>

										<p>The teams enage in public outreach, community building, translation, research, publicity, and in general, promoting sharing and our mission.</p>

										<div class="bucket-follow"><a href="http://wiki.creativecommons.org/CC_Affiliate_Network">Explore the Affiliate Network...</a></div>
									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "five columns alpha" -->

						<div class="six columns">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">Case Studies</h3>
									<div class="content">
										<div id="case">
										<div class="studies">
											<div class="slide">
											<div class="sample">
												<img src="<?php bloginfo('stylesheet_directory'); ?>/img/120px-Aljazeera.svg.png" alt="Al Jazeera"/>
											</div>

											<h5>Al Jazeera</h5>
											<h6>Satellite television network</h6>

										<p>The Al Jazeera Creative Commons Repository hosts select broadcast quality footage that Al Jazeera has released under various Creative Commons licenses. Read our <a href="http://wiki.creativecommons.org/Case_Studies/Al_Jazeera"> case study for Al Jazeera</a></p>
										</div>
										<div class="slide">
										<div class="sample">
											<img src="<?php bloginfo('stylesheet_directory'); ?>/img/US-WhiteHouse-Logo.png" alt="Whitehouse.gov"/>
										</div>

										<h5>Whitehouse.gov</h5>
										<h6>The official website of the President of the United States</h6>

										<p>The official website of the Obama-Biden Administration incorporates a Creative Commons Attribution 3.0 Licence. Read <a href="http://wiki.creativecommons.org/Case_Studies/Whitehouse.gov">our case study for Whitehouse.gov</a></p>
                                                                                        </div>

										</div> <!--! end of "studies -->
										</div> <!--! end of #case -->
										<div class="bucket-follow"><a href="http://wiki.creativecommons.org/Case_Studies">Read more Case Studies...</a></div>
									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "six columns" -->

						<div class="five columns omega">
							<div class="bucket">
								<div class="inner">
									<h3 class="title">CC Store</h3>
									<div class="content">
										<div class="sample">
											<div id="store">
												<div class="swag">
													<div class="slide">
														<a href="https://creativecommons.net/content/1-buttons-set-5">
														<img src="<?php bloginfo('stylesheet_directory'); ?>/img/buttons_1_0_300x200.jpg" alt="CC Buttons" />
														</a>
													</div>
                                                                                                        <div class="slide">
                                                                                                                <a href="https://creativecommons.net/content/t-shirt-science">
                                                                                                                <img src="<?php bloginfo('stylesheet_directory'); ?>/img/XKCD-Science-back_300x200.jpg" alt="T-shirt: Science@creativecommons" />
                                                                                                                </a>
                                                                                                        </div>
													<div class="slide">
														<a href="https://creativecommons.net/sites/default/files/imagecache/product_list/black-cc.jpg">
														<img src="<?php bloginfo('stylesheet_directory'); ?>/img/black-cc_300x200.jpg" alt="T-Shirt: CC Logo" />
														</a>
													</div>

												</div>
											</div>
										</div>

										<h5>Support CC</h5>
										<h6>Buy Swag and Goodies</h6>

										<p>We have T-shirts, vinyl stikers, buttons and lapel pins.</p>

										<div class="bucket-follow"><a href="https://creativecommons.net/store/">Visit the Store</a></div>
									</div> <!--! end of "content" -->
								</div> <!--! end of "inner" -->
							</div> <!--! end of "bucket" -->
						</div> <!--! end of "five columns omega" -->
					</div> <!--! end of "tall row" -->
