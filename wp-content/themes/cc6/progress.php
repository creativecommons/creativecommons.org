<?php function showTestimonial() { 
	$url = "https://creativecommons.net/testimonials";
	$utm = "?utm_campaign=superhero&utm_source=ccorg&utm_medium=testimonial";
?>

<div id="sidebarTestimonial">
	<h3>Join Jonathan in supporting&nbsp;CC!</h3>
	<p class="quote">
		<a href="<?= $url ?><?= $utm ?>"><img src="https://creativecommons.net/images/75/jonathancoulton.jpg" align="left" alt="Jonathan Coulton" border="0" /></a>
		"With Creative Commons, the act of creation becomes not the end, but the beginning of a creative process that links complete strangers together in collaboration"
	</p>
	<p class="source">
		<a href="<?= $url?><?= $utm ?>">&mdash; Jonathan Coulton</a><br/>
		<small>Musician</small><br/>
	</p>
</div>
	<script>jQuery("#sidebarTestimonial").click(function() { window.location="https://creativecommons.net/donate<?= $utm ?>"; });</script>
<?php 
	/* end of showTestimonial() */
	return; 
}?>

<?php 
function showThermometer() { 	

	if (is_home()) {
		$utm = "?utm_campaign=superhero&utm_source=ccorg&utm_medium=homepage_thermometer";
	} else { 
		$utm = "?utm_campaign=superhero&utm_source=ccorg&utm_medium=thermometer"; 
	} 
?>

   			<div id="campaign">  
				<div class="progress <?php if (is_home()) {?>home<?}?>" onclick="window.location='https://creativecommons.net/donate<?= $utm ?>';">
					<div class="inner"><span>&nbsp;</span></div>
				</div>
				<?php if (is_home()) { ?><div class="homeGoal"><a href="https://creativecommons.net/donate<?= $utm ?>">$550,000</a></div><? } ?>
				<div class="results<?php if (is_home()) {?>Home<?}?>">
					<a href="https://creativecommons.net/donate<?= $utm ?>">
					<?php if (is_home()) { ?><strong><?php cc_progress_total() ?> Raised</strong> &mdash; Thank you!<?php } else { ?>
						<?php cc_progress_total() ?> / $550,000 by&nbsp;Dec&nbsp;31 
						<br/>
						<em>Help us reach our goal!</em>
					<?php } ?>
					</a>
				</div>
			</div>

<?php /* end of showThermometer() */ 
	return;
} 


if (is_home()) {
	showThermometer();
} else {
/*	Test killed - 09/12/02
 	if (isset($_COOKIE['cc_showtestimonial'])) {
		$showTestimonial = $_COOKIE['cc_showtestimonial'];
	} else {
		$showTestimonial = rand(0, 1);
	}

	if ($showTestimonial) {
		showTestimonial();
	} else {
		showThermometer();
	}
 */
	showThermometer();
}
?>


