		<footer id="fixfooter">
		<div class="container">
		
			<div id="globe"></div>

			<div id="colophon">
				<div class="sixteen columns">
					<div class="first row">
						<div class="five columns">
							<div class="bucket">
								<a href="http://www.facebook.com/creativecommons" title="Creative Commons on Facebook"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/facebook.png" alt="Creative Commons on Facebook"/></a>
								<a href="http://twitter.com/creativecommons" title="Creative Commons on Twitter"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/twitter.png" alt="Creative Commons on Twitter"/></a>
								<a href="http://identi.ca/creativecommons" title="Creative Commons on Identi.ca"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/identica.png" alt="Creative Commons on Identi.ca"/></a>
							</div>
						</div>
						<div class="four offset-by-six columns">
						  <div class="bucket">
						    <p>
						      <a rel = "license"
							 href="http://creativecommons.org/licenses/by/3.0/"
							 title="Creative Commons Attribution 3.0 License"
							 ><img src="http://i.creativecommons.org/l/by/3.0/88x31.png"
							       alt="License"/></a>
						    </p>
						    <div style="display: inline;" xmlns:cc="http://creativecommons.org/ns#"
							 about="http://creativecommons.org">
						      <p>
							<small>
							  Except where otherwise
							  <a class="subfoot" href="/policies#license">noted</a>,
							  content on
							  <span 
							     href="http://creativecommons.org" 
							     property="cc:attributionName" 
							     rel="cc:attributionURL">this site</span> 
							  is licensed under a 
							  <a rel="license" 
							     href="http://creativecommons.org/licenses/by/3.0/"
							     class="subfoot">
							    Creative Commons Attribution 3.0 License</a>.
							</small>
						      </p>
						    </div>
						  </div>
						</div>
					</div>
				</div>
				<div class="sixteen columns">
					<div class="row">
						<div class="six columns">
							<ul class="nav">
							<li><a href="http://creativecommons.org/policies">Policies</a></li>
							<li><a href="http://creativecommons.org/privacy">Privacy</a></li>
							<?php
							if ( preg_match('/(staging\.)?creativecommons\.net/', $_SERVER['HTTP_HOST']) ) {
								echo '<li><a href="https://creativecommons.net/h/policies/tou">Terms of Use</a></li>';
							} else {
								echo '<li><a href="http://creativecommons.org/terms">Terms of Use</a></li>';
							}
							?>
							<li><a href="http://creativecommons.org/contact">Contact CC</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
