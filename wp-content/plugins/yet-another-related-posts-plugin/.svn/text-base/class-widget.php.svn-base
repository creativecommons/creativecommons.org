<?php

// vaguely based on code by MK Safi
// http://msafi.com/fix-yet-another-related-posts-plugin-yarpp-widget-and-add-it-to-the-sidebar/
class YARPP_Widget extends WP_Widget {
	function YARPP_Widget() {
		parent::WP_Widget(false, $name = __('Related Posts (YARPP)','yarpp'));
	}

	function widget($args, $instance) {
		global $yarpp;
		if ( !is_singular() )
			return;

		extract($args);

		// compatibility with pre-3.5 settings:
		if ( isset($instance['use_template']) )
			$instance['template'] = $instance['use_template'] ? $instance['template_file'] : false;

// 		$choice = false === $instance['template'] ? 'builtin' :
// 			( $instance['template'] == 'thumbnails' ? 'thumbnails' : 'custom' );

		if ( $yarpp->get_option('cross_relate') )
			$instance['post_type'] = $yarpp->get_post_types();
		else if ( 'page' == get_post_type() )
			$instance['post_type'] = array( 'page' );
		else
			$instance['post_type'] = array( 'post' );

		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( !$instance['template'] ) {
			echo $before_title;
			echo $title;
			echo $after_title;
		}

		$instance['domain'] = 'widget';
		$yarpp->display_related(null, $instance, true);
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {		
		if ( $new_instance['use_template'] == 'builtin' )
			$template = false;
		if ( $new_instance['use_template'] == 'thumbnails' )
			$template = 'thumbnails';
		if ( $new_instance['use_template'] == 'custom' )
			$template = $new_instance['template_file'];

		$instance = array(
			'promote_yarpp' => isset($new_instance['promote_yarpp']),
			'template' => $template
		);

		$choice = false === $instance['template'] ? 'builtin' :
			( $instance['template'] == 'thumbnails' ? 'thumbnails' : 'custom' );

		if ( !!$instance['template'] ) // don't save the title change.
			$instance['title'] = $old_instance['title'];
		else // save the title change:
			$instance['title'] = $new_instance['title'];
		
		return $instance;
	}

	function form($instance) {
		global $yarpp;
	
		$instance = wp_parse_args( $instance, array(
			'title' => __('Related Posts (YARPP)','yarpp'),
			'template' => false,
			'promote_yarpp' => false
		) );
	
		// compatibility with pre-3.5 settings:
		if ( isset($instance['use_template']) )
			$instance['template'] = $instance['template_file'];
	
		$choice = false === $instance['template'] ? 'builtin' :
			( $instance['template'] == 'thumbnails' ? 'thumbnails' : 'custom' );

		// if there are YARPP templates installed...
		$templates = $yarpp->admin->get_templates();
		if ( !count($templates) && $choice == 'custom' )
			$choice = 'builtin';
		
		?>

		<p>
			<label style="padding-right: 10px; display: inline-block;" for="<?php echo $this->get_field_id('use_template_builtin'); ?>"><input id="<?php echo $this->get_field_id('use_template_builtin'); ?>" name="<?php echo $this->get_field_name('use_template'); ?>" type="radio" value="builtin" <?php checked( $choice == 'builtin' ) ?> /> <?php _e( "List", 'yarpp' ); ?></label>
		
			<label style="padding-right: 10px; display: inline-block;" for="<?php echo $this->get_field_id('use_template_thumbnails'); ?>"><input id="<?php echo $this->get_field_id('use_template_thumbnails'); ?>" name="<?php echo $this->get_field_name('use_template'); ?>" type="radio" value="thumbnails" <?php checked( $choice == 'thumbnails' ) ?> /> <?php _e( "Thumbnails", 'yarpp' ); ?></label>
			
			<label style="padding-right: 10px; display: inline-block;" for="<?php echo $this->get_field_id('use_template_custom'); ?>"><input id="<?php echo $this->get_field_id('use_template_custom'); ?>" name="<?php echo $this->get_field_name('use_template'); ?>" type="radio" value="custom" <?php checked( $choice == 'custom' ); disabled( !count($templates) ); ?> /> <?php _e( "Custom", 'yarpp' ); ?></label>
		</p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></label></p>

		<p><label for="<?php echo $this->get_field_id('template_file'); ?>"><?php _e("Template file:",'yarpp');?></label> <select name="<?php echo $this->get_field_name('template_file'); ?>" id="<?php echo $this->get_field_id('template_file'); ?>">
			<?php foreach ($templates as $template): ?>
			<option value='<?php echo esc_attr($template['basename']); ?>'<?php selected($template['basename'], $instance['template']);?>><?php echo esc_html($template['name']); ?></option>
			<?php endforeach; ?>
		</select><p>
		<script type="text/javascript">
		jQuery(function($) {
			function ensureTemplateChoice() {
				var custom = $('#<?php echo $this->get_field_id('use_template_custom'); ?>').prop('checked');
				var builtin = $('#<?php echo $this->get_field_id('use_template_builtin'); ?>').prop('checked');
				$('#<?php echo $this->get_field_id('title'); ?>').closest('p').toggle(!!builtin);
				$('#<?php echo $this->get_field_id('template_file'); ?>').closest('p').toggle(!!custom);
			}
			$('input[name="<?php echo $this->get_field_name('use_template'); ?>"]').change(ensureTemplateChoice);
			ensureTemplateChoice();
		});
		</script>

		<p><input class="checkbox" id="<?php echo $this->get_field_id('promote_yarpp'); ?>" name="<?php echo $this->get_field_name('promote_yarpp'); ?>" type="checkbox" <?php checked($instance['promote_yarpp']) ?> /> <label for="<?php echo $this->get_field_id('promote_yarpp'); ?>"><?php _e("Help promote Yet Another Related Posts Plugin?",'yarpp'); ?></label></p>
		<?php
	}
}
// new in 2.0: add as a widget
function yarpp_widget_init() {
	register_widget( 'YARPP_Widget' );
}
add_action( 'widgets_init', 'yarpp_widget_init' );
