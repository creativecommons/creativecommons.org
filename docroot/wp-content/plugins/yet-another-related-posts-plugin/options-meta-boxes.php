<?php

class YARPP_Meta_Box {
	protected $template_text = '';
	
	function __construct() {
		$this->template_text = __( "This advanced option gives you full power to customize how your related posts are displayed. Templates (stored in your theme folder) are written in PHP.", 'yarpp' );
	}

	function checkbox( $option, $desc, $class = '' ) {
		echo "<div class='yarpp_form_row yarpp_form_checkbox $class'><div scope='row'>";
		echo "<input type='checkbox' name='$option' id='yarpp-$option' value='true'";
		checked( yarpp_get_option( $option ) == 1 );
		echo "  /> <label for='yarpp-$option'>$desc</label></div></div>";
	}
	
	private function offer_copy_templates() {
		global $yarpp;
		return ( !count( $yarpp->admin->get_templates() ) && $yarpp->admin->can_copy_templates() );
	}
	
	function template_checkbox( $rss = false, $class = '' ) {
		global $yarpp;

		$pre = $rss ? 'rss_' : '';
		$chosen_template = yarpp_get_option( "{$pre}template" );
		$choice = false === $chosen_template ? 'builtin' :
			( $chosen_template == 'thumbnails' ? 'thumbnails' : 'custom' );

		echo "<div class='yarpp_form_row yarpp_form_template_buttons $class'>";
		
			echo "<div data-value='builtin' class='yarpp_template_button";
			if ( 'builtin' == $choice )
				echo ' active';
			echo "'><div class='image'></div><div class='label'>" . __( 'List', 'yarpp' ) . "</div></div>";
	
			echo "<div data-value='thumbnails' class='yarpp_template_button";
			if ( 'thumbnails' == $choice )
				echo ' active';
			if ( !$yarpp->diagnostic_post_thumbnails() )
				echo ' disabled';
			echo "'";
			if ( !$yarpp->diagnostic_post_thumbnails() )
				echo ' data-help="' . esc_attr( __( 'This option is disabled because your theme does not support post thumbnails.', 'yarpp' ) ) . '"';
			echo "><div class='image'></div><div class='label'>" . __( 'Thumbnails', 'yarpp' ) . "</div></div>";
	
			echo "<div data-value='custom' class='yarpp_template_button";
			if ( 'custom' == $choice )
				echo ' active';
			if ( !count( $yarpp->admin->get_templates() ) )
				echo ' disabled';
			echo "'";
			if ( !count( $yarpp->admin->get_templates() ) ) {
				$help = __( 'This option is disabled because no YARPP templates were found in your theme.', 'yarpp' );
				if ( $this->offer_copy_templates() )
					$help .= ' ' . __( "Would you like to copy some sample templates bundled with YARPP into your theme?", 'yarpp' ) . "<input type='button' class='button button-small yarpp_copy_templates_button' value='" . esc_attr( __( 'Copy Templates', 'yarpp' ) ) . "'/>";
				echo " data-help='" . esc_attr( $help ) . "'";
			}
			echo "><div class='image'></div><div class='label'>" . __( 'Custom', 'yarpp' ) . "</div></div>";
	
			echo "<input type='hidden' name='{$pre}use_template' id='yarpp-{$pre}use_template' class='use_template' value='{$choice}' />";

		echo "</div>";

	}
	function template_file( $rss = false, $class = '' ) {
		global $yarpp;
		$pre = $rss ? 'rss_' : '';
		echo "<div class='yarpp_form_row yarpp_form_template_file $class'><div class='yarpp_form_label'>";
		_e( "Template file:", 'yarpp' );
		echo "</div><div><select name='{$pre}template_file' id='{$pre}template_file'>";
		$chosen_template = yarpp_get_option( "{$pre}template" );
		foreach ( $yarpp->admin->get_templates() as $template ) {
			echo "<option value='" . esc_attr( $template['basename'] ) . "'" . selected( $template['basename'], $chosen_template, false );
			foreach ( $template as $key => $value )
				echo " data-{$key}='" . esc_attr( $value ) . "'";
			echo '>' . esc_html( $template['name'] ) . '</option>';
		}
		echo "</select><p class='template_file_wrap'><span id='{$pre}template_file'></span></p><p class='template_author_wrap'>" . __( 'Author:' ) . " <span id='{$pre}template_author'></span></p><p class='template_description_wrap'><span id='{$pre}template_description'></span></p></div></div>";
	}
	function textbox( $option, $desc, $size=2, $class='', $note = '' ) {
		$value = esc_attr( yarpp_get_option( $option ) );
		echo "<div class='yarpp_form_row yarpp_form_textbox $class'><div class='yarpp_form_label'>";
		echo "$desc</div>
				<div><input name='$option' type='text' id='$option' value='$value' size='$size' />";
		if ( !empty( $note ) )
			echo " <em><small>{$note}</small></em>";
		echo "</div></div>";
	}
	function beforeafter( $options, $desc, $size=10, $class='', $note = '' ) {
		echo "<div class='yarpp_form_row yarpp_form_textbox $class'><div class='yarpp_form_label'>$desc</div><div>";
		$value = esc_attr( yarpp_get_option( $options[0] ) );
		echo "<input name='{$options[0]}' type='text' id='{$options[0]}' value='$value' size='$size' /> <span class='yarpp_divider'>/</span> ";
		$value = esc_attr( yarpp_get_option( $options[1] ) );
		echo "<input name='{$options[1]}' type='text' id='{$options[1]}' value='$value' size='$size' />";
		if ( !empty( $note ) )
			echo " <em><small>{$note}</small></em>";
		echo "</div></div>";
	}

	function tax_weight( $taxonomy ) {
		$weight = (int) yarpp_get_option( "weight[tax][{$taxonomy->name}]" );
		$require = (int) yarpp_get_option( "require_tax[{$taxonomy->name}]" );
		echo "<div class='yarpp_form_row yarpp_form_select'><div class='yarpp_form_label'>{$taxonomy->labels->name}:</div><div><select name='weight[tax][{$taxonomy->name}]'>";
		echo "<option value='no'". ( ( !$weight && !$require ) ? ' selected="selected"': '' )."  > " . __( "do not consider", 'yarpp' ) . "</option>";
		echo "<option value='consider'". ( ( $weight == 1 && !$require ) ? ' selected="selected"': '' )."  >" . __( "consider", 'yarpp' ) . "</option>";
		echo "<option value='consider_extra'". ( ( $weight > 1 && !$require ) ? ' selected="selected"': '' )."  >" . __( "consider with extra weight", 'yarpp' ) . "</option>";
		echo "<option value='require_one'". ( ( $require == 1 ) ? ' selected="selected"': '' )."  >" . sprintf( __( "require at least one %s in common", 'yarpp' ), $taxonomy->labels->singular_name ) . "</option>";
		echo "<option value='require_more'". ( ( $require == 2 ) ? ' selected="selected"': '' )."  >" . sprintf( __( "require more than one %s in common", 'yarpp' ), $taxonomy->labels->singular_name ) . "</option>";
		echo "</select></div></div>";
	}
	
	function weight( $option, $desc ) {
		global $yarpp;
		
		$weight = (int) yarpp_get_option( "weight[$option]" );
		
		// both require MyISAM fulltext indexing:
		$myisam = !$yarpp->myisam ? ' readonly="readonly" disabled="disabled"' : '';
		
		echo "<div class='yarpp_form_row yarpp_form_select'><div class='yarpp_form_label'>$desc</div><div>";
		echo "<select name='weight[$option]'>";
		echo "<option $myisam value='no'". ( !$weight ? ' selected="selected"': '' )."  >".__( "do not consider", 'yarpp' )."</option>";
		echo "<option $myisam value='consider'". ( ( $weight == 1 ) ? ' selected="selected"': '' )."  > ".__( "consider", 'yarpp' )."</option>";
		echo "<option $myisam value='consider_extra'". ( ( $weight > 1 ) ? ' selected="selected"': '' )."  > ".__( "consider with extra weight", 'yarpp' )."</option>";
		echo "</select></div></div>";
	}
	
	function displayorder( $option, $class = '' ) {
		echo "<div class='yarpp_form_row yarpp_form_select $class'><div class='yarpp_form_label'>";
		_e( "Order results:", 'yarpp' );
		echo "</div><div><select name='$option' id='<?php echo $option; ?>'>";
		$order = yarpp_get_option( $option );
		?>
			<option value="score DESC" <?php echo ( $order == 'score DESC'?' selected="selected"':'' )?>><?php _e( "score (high relevance to low)", 'yarpp' ); ?></option>
			<option value="score ASC" <?php echo ( $order == 'score ASC'?' selected="selected"':'' )?>><?php _e( "score (low relevance to high)", 'yarpp' ); ?></option>
			<option value="post_date DESC" <?php echo ( $order == 'post_date DESC'?' selected="selected"':'' )?>><?php _e( "date (new to old)", 'yarpp' ); ?></option>
			<option value="post_date ASC" <?php echo ( $order == 'post_date ASC'?' selected="selected"':'' )?>><?php _e( "date (old to new)", 'yarpp' ); ?></option>
			<option value="post_title ASC" <?php echo ( $order == 'post_title ASC'?' selected="selected"':'' )?>><?php _e( "title (alphabetical)", 'yarpp' ); ?></option>
			<option value="post_title DESC" <?php echo ( $order == 'post_title DESC'?' selected="selected"':'' )?>><?php _e( "title (reverse alphabetical)", 'yarpp' ); ?></option>
		<?php
		echo "</select></div></div>";
	}
}

class YARPP_Meta_Box_Pool extends YARPP_Meta_Box {
	function exclude( $taxonomy, $string ) {
		global $yarpp;

		echo "<div class='yarpp_form_row yarpp_form_exclude'><div class='yarpp_form_label'>";
		echo $string;
		echo "</div><div class='yarpp_scroll_wrapper'><div class='exclude_terms' id='exclude_{$taxonomy}'>";

		$exclude_tt_ids = wp_parse_id_list( yarpp_get_option( 'exclude' ) );
		$exclude_term_ids = $yarpp->admin->get_term_ids_from_tt_ids( $taxonomy, $exclude_tt_ids );
		if ( count( $exclude_term_ids ) ) {
			$terms = get_terms( $taxonomy, array( 'include' => $exclude_term_ids ) );
			foreach ( $terms as $term ) {
				echo "<input type='checkbox' name='exclude[{$term->term_taxonomy_id}]' id='exclude_{$term->term_taxonomy_id}' value='true' checked='checked' /> <label for='exclude_{$term->term_taxonomy_id}'>" . esc_html( $term->name ) . "</label> ";
			}
		}

		echo "</div></div></div>";
	}

	function display() {
		global $yarpp;

		echo "<p>";
		_e( '"The Pool" refers to the pool of posts and pages that are candidates for display as related to the current entry.', 'yarpp' );
		echo "</p>\n";
	?>
		<div class='yarpp_form_row'><div class='yarpp_form_label'><?php _e( 'Post types considered:', 'yarpp' ); ?></div><div><?php echo implode( ', ', $yarpp->get_post_types( 'label' ) ); ?> <a href='#help-dev' id='yarpp-help-cpt' class='yarpp_help'>&nbsp;</a></div></div>

	<?php
		foreach ( $yarpp->get_taxonomies() as $taxonomy ) {
			$this->exclude( $taxonomy->name, sprintf( __( 'Disallow by %s:', 'yarpp' ), $taxonomy->labels->singular_name ) );
		}
		$this->checkbox( 'show_pass_post', __( "Show password protected posts?", 'yarpp' ) );
	
		$recent = yarpp_get_option( 'recent' );
		if ( !!$recent ) {
			list( $recent_number, $recent_units ) = explode( ' ', $recent );
		} else {
			$recent_number = 12;
			$recent_units = 'month';
		}
		$recent_number = "<input name=\"recent_number\" type=\"text\" id=\"recent_number\" value=\"".esc_attr( $recent_number )."\" size=\"2\" />";
		$recent_units = "<select name=\"recent_units\" id=\"recent_units\">
			<option value='day'" . ( ( 'day'==$recent_units) ? " selected='selected'" : '' ) . ">" . __( 'day(s)', 'yarpp' )."</option>
			<option value='week'" . ( ( 'week'==$recent_units ) ? " selected='selected'" : '' ) . ">" . __( 'week(s)', 'yarpp' )."</option>
			<option value='month'" . ( ( 'month'==$recent_units ) ? " selected='selected'" : '' ) . ">" . __( 'month(s)', 'yarpp' ) . "</option>
		</select>";
	
		echo "<div class='yarpp_form_row yarpp_form_checkbox'><div><input type='checkbox' name='recent_only' value='true'";
		checked( !!$recent );
		echo " /> ";
		echo str_replace( 'NUMBER', $recent_number, str_replace( 'UNITS', $recent_units, __( "Show only posts from the past NUMBER UNITS", 'yarpp' ) ) );
		echo "</div></div>";

	}
}

add_meta_box( 'yarpp_pool', __( '"The Pool"', 'yarpp' ), array( new YARPP_Meta_Box_Pool, 'display' ), 'settings_page_yarpp', 'normal', 'core' );

class YARPP_Meta_Box_Relatedness extends YARPP_Meta_Box {
	function display() {
		global $yarpp;
	?>
		<p><?php _e( 'YARPP limits the related posts list by (1) a maximum number and (2) a <em>match threshold</em>.', 'yarpp' ); ?> <span class='yarpp_help' data-help="<?php echo esc_attr( __( 'The higher the match threshold, the more restrictive, and you get less related posts overall. The default match threshold is 5. If you want to find an appropriate match threshhold, take a look at some post\'s related posts display and their scores. You can see what kinds of related posts are being picked up and with what kind of match scores, and determine an appropriate threshold for your site.', 'yarpp' ) ); ?>">&nbsp;</span></p>

	<?php
		$this->textbox( 'threshold', __( 'Match threshold:', 'yarpp' ) );
		$this->weight( 'title', __( "Titles: ", 'yarpp' ) );
		$this->weight( 'body', __( "Bodies: ", 'yarpp' ) );
	
		foreach ( $yarpp->get_taxonomies() as $taxonomy ) {
			$this->tax_weight( $taxonomy );
		}
	
		$this->checkbox( 'cross_relate', __( "Display results from all post types", 'yarpp' )." <span class='yarpp_help' data-help='" . esc_attr( __( "When \"display results from all post types\" is off, only posts will be displayed as related to a post, only pages will be displayed as related to a page, etc.", 'yarpp' ) ) . "'>&nbsp;</span>" );
		$this->checkbox( 'past_only', __( "Show only previous posts?", 'yarpp' ) );
	}
}

add_meta_box( 'yarpp_relatedness', __( '"Relatedness" options', 'yarpp' ), array( new YARPP_Meta_Box_Relatedness, 'display' ), 'settings_page_yarpp', 'normal', 'core' );

class YARPP_Meta_Box_Display_Web extends YARPP_Meta_Box {
	function display() {
		global $yarpp;

		echo "<div style='overflow:auto'>";
			echo '<div class="yarpp_code_display"';
			if ( !$yarpp->get_option('code_display') )
				echo ' style="display: none;"';
			echo '><strong>' . __( "Website display code example", 'yarpp' ) . '</strong><br /><small>' . __( "(Update options to reload.)", 'yarpp' ) . "</small><br/><div id='display_demo_web'></div></div>";
			
			echo "<div class='yarpp_form_row yarpp_form_post_types'><div class='yarpp_form_label'>";
			_e( "Automatically display:", 'yarpp' );
			echo " <span class='yarpp_help' data-help='" . esc_attr( __( "This option automatically displays related posts right after the content on single entry pages. If this option is off, you will need to manually insert <code>related_posts()</code> or variants (<code>related_pages()</code> and <code>related_entries()</code>) into your theme files.", 'yarpp' ) ) . "'>&nbsp;</span>";
			echo "</div><div>";
			$post_types = yarpp_get_option( 'auto_display_post_types' );
			foreach ( $yarpp->get_post_types( 'objects' ) as $post_type ) {
				echo "<label for='yarpp_post_type_{$post_type->name}'><input id='yarpp_post_type_{$post_type->name}' name='auto_display_post_types[{$post_type->name}]' type='checkbox' ";
				checked( in_array( $post_type->name, $post_types ) );
				echo "/> {$post_type->labels->name}</label> ";
			}
			echo "</div></div>";
						
			$this->checkbox( 'auto_display_archive', __( "Also display in archives", 'yarpp' ) );
	
			$this->textbox( 'limit', __( 'Maximum number of related posts:', 'yarpp' ) );
			$this->template_checkbox( false );
		echo "</div>";

		$chosen_template = yarpp_get_option( "template" );
		$choice = false === $chosen_template ? 'builtin' :
			( $chosen_template == 'thumbnails' ? 'thumbnails' : 'custom' );

		echo "<div class='postbox yarpp_subbox template_options_custom'";
		if ( $choice != 'custom' )
			echo ' style="display: none;"';
		echo ">";
			echo '<div class="yarpp_form_row"><div>' . $this->template_text . '</div></div>';
			$this->template_file( false );
		echo "</div>";

		echo "<div class='postbox yarpp_subbox template_options_thumbnails'";
		if ( $choice != 'thumbnails' )
			echo ' style="display: none;"';
		echo ">";
			$this->textbox( 'thumbnails_heading', __( 'Heading:', 'yarpp' ), 40 );
			$this->textbox( 'thumbnails_default', __( 'Default image (URL):', 'yarpp' ), 40 );
			$this->textbox( 'no_results', __( 'Default display if no results:', 'yarpp' ), 40, 'sync_no_results' );
		echo "</div>";

		echo "<div class='postbox yarpp_subbox template_options_builtin'";
		if ( $choice != 'builtin' )
			echo ' style="display: none;"';
		echo ">";
			$this->beforeafter( array( 'before_related', 'after_related' ), __( "Before / after related entries:", 'yarpp' ), 15, '', __( "For example:", 'yarpp' ) . ' &lt;ol&gt;&lt;/ol&gt;' . __( ' or ', 'yarpp' ) . '&lt;div&gt;&lt;/div&gt;' );
			$this->beforeafter( array( 'before_title', 'after_title' ), __( "Before / after each related entry:", 'yarpp' ), 15, '', __( "For example:", 'yarpp' ) . ' &lt;li&gt;&lt;/li&gt;' . __( ' or ', 'yarpp' ) . '&lt;dl&gt;&lt;/dl&gt;' );
			
			$this->checkbox( 'show_excerpt', __( "Show excerpt?", 'yarpp' ), 'show_excerpt' );
			$this->textbox( 'excerpt_length', __( 'Excerpt length (No. of words):', 'yarpp' ), 10, 'excerpted' );
	
			$this->beforeafter( array( 'before_post', 'after_post' ), __( "Before / after (excerpt):", 'yarpp' ), 10, 'excerpted', __( "For example:", 'yarpp' ) . ' &lt;li&gt;&lt;/li&gt;' . __( ' or ', 'yarpp' ) . '&lt;dl&gt;&lt;/dl&gt;' );
	
			$this->textbox( 'no_results', __( 'Default display if no results:', 'yarpp' ), 40, 'sync_no_results' );
		echo "</div>";

		$this->displayorder( 'order' );			

		$this->checkbox( 'promote_yarpp', __( "Help promote Yet Another Related Posts Plugin?", 'yarpp' )
		." <span class='yarpp_help' data-help='" . esc_attr( sprintf( __( "This option will add the code %s. Try turning it on, updating your options, and see the code in the code example to the right. These links and donations are greatly appreciated.", 'yarpp' ), "<code>" . htmlspecialchars( sprintf( __( "Related posts brought to you by <a href='%s'>Yet Another Related Posts Plugin</a>.", 'yarpp' ), 'http://yarpp.org' ) ) . "</code>" ) ) ."'>&nbsp;</span>" );
	}
}

add_meta_box( 'yarpp_display_web', __( 'Display options <small>for your website</small>', 'yarpp' ), array( new YARPP_Meta_Box_Display_Web, 'display' ), 'settings_page_yarpp', 'normal', 'core' );

class YARPP_Meta_Box_Display_Feed extends YARPP_Meta_Box {
	function display() {
		global $yarpp;

		echo "<div style='overflow:auto'>";
			echo '<div class="rss_displayed yarpp_code_display"';
			if ( !$yarpp->get_option('code_display') )
				echo ' style="display: none;"';
			echo '><b>' . __( "RSS display code example", 'yarpp' ) . '</b><br /><small>' . __( "(Update options to reload.)", 'yarpp' ) . "</small><br/><div id='display_demo_rss'></div></div>";
	
			$this->checkbox( 'rss_display', __( "Display related posts in feeds?", 'yarpp' )." <span class='yarpp_help' data-help='" . esc_attr( __( "This option displays related posts at the end of each item in your RSS and Atom feeds. No template changes are needed.", 'yarpp' ) ) . "'>&nbsp;</span>", '' );
			$this->checkbox( 'rss_excerpt_display', __( "Display related posts in the descriptions?", 'yarpp' )." <span class='yarpp_help' data-help='" . esc_attr( __( "This option displays the related posts in the RSS description fields, not just the content. If your feeds are set up to only display excerpts, however, only the description field is used, so this option is required for any display at all.", 'yarpp' ) ) . "'>&nbsp;</span>", 'rss_displayed' );
	
			$this->textbox( 'rss_limit', __( 'Maximum number of related posts:', 'yarpp' ), 2, 'rss_displayed' );
			$this->template_checkbox( true, 'rss_displayed' );
		echo "</div>";
		
		$chosen_template = yarpp_get_option( "rss_template" );
		$choice = false === $chosen_template ? 'builtin' :
			( $chosen_template == 'thumbnails' ? 'thumbnails' : 'custom' );
		
		echo "<div class='postbox yarpp_subbox template_options_custom rss_displayed'";
		if ( $choice != 'custom' )
			echo ' style="display: none;"';
		echo ">";
			echo '<div class="yarpp_form_row"><div>' . $this->template_text . '</div></div>';
			$this->template_file( true );
		echo "</div>";
	
		echo "<div class='postbox yarpp_subbox template_options_thumbnails'";
		if ( $choice != 'thumbnails' )
			echo ' style="display: none;"';
		echo ">";
			$this->textbox( 'rss_thumbnails_heading', __( 'Heading:', 'yarpp' ), 40 );
			$this->textbox( 'rss_thumbnails_default', __( 'Default image (URL):', 'yarpp' ), 40 );
			$this->textbox( 'rss_no_results', __( 'Default display if no results:', 'yarpp' ), 40, 'sync_rss_no_results' );
		echo "</div>";
	
		echo "<div class='postbox yarpp_subbox template_options_builtin rss_displayed'";
		if ( $choice != 'builtin' )
			echo ' style="display: none;"';
		echo ">";
			$this->beforeafter( array( 'rss_before_related', 'rss_after_related' ), __( "Before / after related entries:", 'yarpp' ), 15, '', __( "For example:", 'yarpp' ) . ' &lt;ol&gt;&lt;/ol&gt;' . __( ' or ', 'yarpp' ) . '&lt;div&gt;&lt;/div&gt;' );
			$this->beforeafter( array( 'rss_before_title', 'rss_after_title' ), __( "Before / after each related entry:", 'yarpp' ), 15, '', __( "For example:", 'yarpp' ) . ' &lt;li&gt;&lt;/li&gt;' . __( ' or ', 'yarpp' ) . '&lt;dl&gt;&lt;/dl&gt;' );
			
			$this->checkbox( 'rss_show_excerpt', __( "Show excerpt?", 'yarpp' ), 'show_excerpt' );
			$this->textbox( 'rss_excerpt_length', __( 'Excerpt length (No. of words):', 'yarpp' ), 10, 'excerpted' );
		
			$this->beforeafter( array( 'rss_before_post', 'rss_after_post' ), __( "Before / after (excerpt):", 'yarpp' ), 10, 'excerpted', __( "For example:", 'yarpp' ) . ' &lt;li&gt;&lt;/li&gt;' . __( ' or ', 'yarpp' ) . '&lt;dl&gt;&lt;/dl&gt;' );
		
			$this->textbox( 'rss_no_results', __( 'Default display if no results:', 'yarpp' ), 40, 'sync_rss_no_results' );
		echo "</div>";

		$this->displayorder( 'rss_order', 'rss_displayed' );			
					
		$this->checkbox( 'rss_promote_yarpp', __( "Help promote Yet Another Related Posts Plugin?", 'yarpp' ) . " <span class='yarpp_help' data-help='" . esc_attr( sprintf( __( "This option will add the code %s. Try turning it on, updating your options, and see the code in the code example to the right. These links and donations are greatly appreciated.", 'yarpp' ), "<code>" . htmlspecialchars( sprintf( __( "Related posts brought to you by <a href='%s'>Yet Another Related Posts Plugin</a>.", 'yarpp' ), 'http://yarpp.org' ) )."</code>" ) ) . "'>&nbsp;</span>", 'rss_displayed' );
	}
}

add_meta_box( 'yarpp_display_rss', __( 'Display options <small>for RSS</small>', 'yarpp' ), array( new YARPP_Meta_Box_Display_Feed, 'display' ), 'settings_page_yarpp', 'normal', 'core' );

class YARPP_Meta_Box_Contact extends YARPP_Meta_Box {
	function display() {
		global $yarpp;
		$pluginurl = plugin_dir_url( __FILE__ );
		?>
		<ul class='yarpp_contacts'>
		<li><a href="http://wordpress.org/support/plugin/yet-another-related-posts-plugin" target="_blank"><span class='icon icon-wordpress'></span> <?php _e( 'YARPP Forum', 'yarpp' ); ?></a></li>
		<li><a href="http://twitter.com/yarpp" target="_blank"><span class='icon icon-twitter'></span> <?php _e( 'YARPP on Twitter', 'yarpp' ); ?></a></li>
		<li><a href="http://yarpp.org" target="_blank"><span class='icon icon-plugin'></span> <?php _e( 'YARPP on the Web', 'yarpp' ); ?></a></li>
		<li><a href="http://wordpress.org/support/view/plugin-reviews/yet-another-related-posts-plugin" target="_blank"><span class='icon icon-star <?php if ( $yarpp->diagnostic_happy() ) echo 'spin'; ?>'></span> <?php _e( 'Review YARPP on WordPress.org', 'yarpp' ); ?></a></li>
		<li><a href='http://tinyurl.com/donatetomitcho' target='_new'><span class='icon icon-paypal'></span> <img src="https://www.paypal.com/<?php echo $this->paypal_lang(); ?>i/btn/btn_donate_SM.gif" name="submit" alt="<?php _e( 'Donate to mitcho (Michael Yoshitaka Erlewine) for this plugin via PayPal' ); ?>" title="<?php _e( 'Donate to mitcho (Michael Yoshitaka Erlewine) for this plugin via PayPal', 'yarpp' ); ?>"/></a></li>
	 </ul>
<?php
	}
	
	function paypal_lang() {
		if ( !defined( 'WPLANG' ) )
			return 'en_US/';
		switch ( substr( WPLANG, 0, 2 ) ) {
			case 'fr':
				return 'fr_FR/';
			case 'de':
				return 'de_DE/';
			case 'it':
				return 'it_IT/';
			case 'ja':
				return 'ja_JP/';
			case 'es':
				return 'es_XC/';
			case 'nl':
				return 'nl_NL/';
			case 'pl':
				return 'pl_PL/';
			case 'zh':
				if ( preg_match( "/^zh_(HK|TW)/i", WPLANG ) )
					return 'zh_HK/';
				// actually zh_CN, but interpret as default zh:
				return 'zh_XC/';
			default:
				return 'en_US/';
		}
	}
}
add_meta_box( 'yarpp_display_optin', __( 'Help Improve YARPP', 'yarpp' ), array( new YARPP_Meta_Box_Optin, 'display' ), 'settings_page_yarpp', 'side', 'core' );

// longest filter name ever
add_filter( "postbox_classes_settings_page_yarpp_yarpp_display_optin", 'yarpp_make_optin_classy' );
function yarpp_make_optin_classy( $classes ) {
	if ( !yarpp_get_option( 'optin' ) )
		$classes[] = 'yarpp_attention';
	return $classes;
}

class YARPP_Meta_Box_Optin extends YARPP_Meta_Box {
	function display() {
		global $yarpp;
		
		// TODO: fix this text and i18nize it
		echo "<input type='checkbox' id='yarpp-optin' name='optin' value='true'";
		checked( yarpp_get_option( 'optin' ) == 1 );
		echo " /> ";
		
		echo '<label for="yarpp-optin">' . __( 'Send settings and usage data back to YARPP', 'yarpp' ) . '</label>';
		
		echo '<p style="overflow:auto;">';
		echo __( 'This is entirely optional, but will help improve future versions of YARPP.', 'yarpp' );
		echo ' <input type="button" value="' . esc_attr( __( 'Learn More', 'yarpp' ) ) . '" id="yarpp-optin-learnmore" class="button button-small"/></p>';
	}
}

add_meta_box( 'yarpp_display_contact', __( 'Contact YARPP', 'yarpp' ), array( new YARPP_Meta_Box_Contact, 'display' ), 'settings_page_yarpp', 'side', 'core' );

// since 3.3: hook for registering new YARPP meta boxes
do_action( 'add_meta_boxes_settings_page_yarpp' );

