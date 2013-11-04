<?php
/*
 * YARPP's built-in "template"
 *
 * This "template" is used when you choose not to use a template.
 * If you want to create a new template, look at yarpp-templates/yarpp-template-example.php as an example.
 * More information on the custom templates is available at http://mitcho.com/blog/projects/yarpp-3-templates/
*/

get_currentuserinfo();

$options = array( 'before_title', 'after_title', 'show_excerpt', 'excerpt_length', 'before_post', 'after_post', 'before_related', 'after_related', 'no_results' );
extract( $this->parse_args( $args, $options ) );

if (have_posts()) {
	$output .= $before_related . "\n";
	while (have_posts()) {
		the_post();

		$output .= "$before_title<a href='" . get_permalink() . "' rel='bookmark' title='" . esc_attr(get_the_title() ? get_the_title() : get_the_ID()) . "'>".get_the_title()."";
		if (current_user_can('manage_options') && $domain != 'rss' && !is_admin() )
			$output .= ' <abbr title="'.sprintf(__('%f is the YARPP match score between the current entry and this related entry. You are seeing this value because you are logged in to WordPress as an administrator. It is not shown to regular visitors.','yarpp'),round(get_the_score(),1)).'">('.round(get_the_score(),1).')</abbr>';
		$output .= '</a>';
		if ($show_excerpt) {
			$excerpt = strip_tags( (string) get_the_excerpt() );
			preg_replace( '/([,;.-]+)\s*/','\1 ', $excerpt );
			$excerpt = implode( ' ', array_slice( preg_split('/\s+/',$excerpt), 0, $excerpt_length ) ).'...';
			$output .= $before_post . $excerpt . $after_post;
		}
		$output .=  $after_title."\n";

	}
	$output .= $after_related . "\n";
} else {
	$output .= $no_results;
}
