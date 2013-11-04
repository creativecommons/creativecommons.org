<?php 
class collapsArchWidget extends WP_Widget {
  function collapsArchWidget() {
    $widget_ops = array('classname' => 'widget_collapsarch', 'description' =>
    'Collapsible archives listing' );
		$control_ops = array (
			'width' => '400', 
			'height' => '400'
			);
    $this->WP_Widget('collapsarch', 'Collapsing Archives', $widget_ops,
    $control_ops);
  }
 
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
 
    $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
    echo $before_widget . $before_title . $title . $after_title;
    $instance['number'] = $this->get_field_id('top');
    $instance['number'] = preg_replace('/[a-zA-Z-]/', '', $instance['number']);
    echo "<ul id='" .  $this->get_field_id('top') . "
        ' class='collapsing archives list'>\n";
       if( function_exists('collapsArch') ) {
        collapsArch($instance);
       } else {
        wp_list_archives();
       }
    echo "</ul>\n";
    echo $after_widget;
  }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    include('updateOptions.php');
    return $instance;
  }
 
  function form($instance) {
  include('defaults.php');
  $options=wp_parse_args($instance, $defaults);
  extract($options);
?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input  id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
    include('options.txt');
?>
  <p>Style can be set from the <a
  href='options-general.php?page=collapsArch.php'>options page</a></p>
<?php
  }
}
function registerCollapsArchWidget() {
  register_widget('collapsArchWidget');
}
	add_action('widgets_init', 'registerCollapsArchWidget');
