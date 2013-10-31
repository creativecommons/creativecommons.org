          <div id="cc_mission">
			<div id="quote">
			 	<a href="/education?utm_source=ccorg&utm_medium=ccedu"><img src="/images/cc-edu.png" alt="CC & Education" border="0" /></a>
			</div>
<?/* Commented out testimonial section, possible return later -- 2010/01/20 ~ Alex

			<script>utmx_section("Testimonial")</script>
		  	<div id="testimonial">
                <div class="photo"><img src="https://support.creativecommons.org/images/75/evanprodromou.jpg" alt="Evan Prodromou" /></div>
                <blockquote style="font-size: 99%">"Within a generation we can open the world’s knowledge to all of its inhabitants and reduce or eliminate the misery caused by lack of access to information, and Creative Commons is a crucial part of the cultural compact that makes that revolution possible."</blockquote>
                <div class="sig">
                    <a href="https://support.creativecommons.org/testimonials#evanprodromou">&mdash; Evan Prodromou</a><br/><span>Founder, Identi.ca</span>
                </div>
<? /*    <p><a href="https://support.creativecommons.org/testimonials">More testimonials &raquo;</a></p>  
			</div>
			<script type="text/javascript">
				jQuery("#testimonial").click(function() { window.location="https://support.creativecommons.org/donate?utm_source=ccorg&utm_medium=homepage_testimonial_cockerill&utm_campaign=fall2009"; });
				jQuery("#testimonial")[0].style.cursor = "pointer";
			</script>
</noscript>
</div>
 */ ?></div>
          <div id="splashBox">
          <div id="splash">
<?php
		if ($sticky_page = cc_get_sticky_page()) {
			// grab attached image from sticky page and display it in the #splash area
			// this ignores any sticky blog posts
			// WARNING: if multiple pages are set sticky (show_on_index) the most 
			//          recently updated one will be used 
			if ($image = cc_get_attachment_image ($sticky_page->ID, 630)) {
			?>
			<a href="<?php echo get_permalink($sticky_page->ID); ?>?utm_source=ccorg&utm_medium=postbanner">
				<img src="<?php echo $image[0] ?>" alt="<?php echo $sticky_page->post_title; ?>" title="<?php echo $sticky_page->post_title; ?>" class="main" />
			</a>
			<?php
			}	
		} else {
            while (have_posts()) { 
              the_post(); 
              
              if (is_sticky() && in_category('splash')) { 
                if ($image = cc_get_attachment_image ($post->ID, 630)) { 
                 
                ?>
            <a href="<?php the_permalink() ?>?utm_source=ccorg&utm_medium=postbanner">
              <img src="<?php echo $image[0] ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" class="main" />
            </a>			
<?php
					$splash_attribution = get_post_meta($post->ID, "splash_attribution", true);
					if ($splash_attribution) {
						?><p class="attribution"><?php echo $splash_attribution ?></p><?php
					}	
                } // if get_attachment_image
                break;
              } // if is_sticky
			} // while
		}
?>
        </div>
        </div>
 
