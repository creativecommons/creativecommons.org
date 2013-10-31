<?php

if (have_posts()) {
	$output .= '<ol>';
	while (have_posts()) {
		the_post();
		$output .= '<li><a href="'.get_permalink().'" rel="bookmark">'.get_the_title().'</a>';
//		$output .= ' ('.round(get_the_score(),3).')';
		$output .= '</li>';
	}
	$output .= '</ol>';
} else {
	$output .= '<p><em>'.__('No related posts.','yarpp').'</em></p>';
}
