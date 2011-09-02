<?php
/* Superhero cards */

$hero[0][0] = "robin-sloan"; $hero[0][1] = "robin";
$hero[1][0] = "elizabeth-stark"; $hero[1][1] = "elizabeth";
$hero[2][0] = "mitchell-baker"; $hero[2][1] = "mitchell";
$hero[3][0] = "neeru-khosla"; $hero[3][1] = "neeru";
$hero[4][0] = "gautam-john"; $hero[4][1] = "gautam";
$hero[5][0] = "andrew-rens"; $hero[5][1] = "andrew";
$hero[6][0] = "cern"; $hero[6][1] = "cern";
$hero[7][0] = "delia-browne"; $hero[7][1] = "delia";
$hero[8][0] = "josh-sommer"; $hero[8][1] = "josh";


$i = rand(0, count($hero)-1);

?>

	<div class="splash grid_16 alpha omega" style="position:relative; overflow:visible;">
<script>utmx_section("Banner")</script>
		<a onclick="gwoTracker._link('https://creativecommons.net/donate'); return false;" href="https://creativecommons.net/donate?utm_source=ccorg&utm_medium=homepage_banner&utm_campaign=superhero"><img src="/images/support/2010/cc-org-banner-Strength-2.png" border="0" align="center" alt="Invest in the future of creativity and knowledge. Donate Today." /></a>
</noscript>
		<?php include ('progress.php'); ?>
	</div>
</div>

