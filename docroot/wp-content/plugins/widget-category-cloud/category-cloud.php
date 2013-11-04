<?php
/*
Plugin Name: Category Cloud Widget
Plugin URI: http://leekelleher.com/wordpress/plugins/category-cloud-widget/
Description: Adds a sidebar widget to display the categories as a tag cloud.
Author: Lee Kelleher
Version: 1.7
Author URI: http://leekelleher.com/
*/

// This gets called at the init action
function widget_catcloud_init()
{
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// This saves options and prints the widget's config form.
	function widget_catcloud_control()
	{
		$options = $newoptions = get_option('widget_catcloud');
		if ( $_POST['catcloud-submit'] )
		{
			$newoptions['title'] = strip_tags(stripslashes($_POST['catcloud-title']));
			$newoptions['small'] = ($_POST['catcloud-small'] != '') ? (int) $_POST['catcloud-small'] : 50;
			$newoptions['big'] = ($_POST['catcloud-big'] != '') ? (int) $_POST['catcloud-big'] : 150;
			$newoptions['unit'] = ($_POST['catcloud-unit'] != '') ? $_POST['catcloud-unit'] : '%';
			$newoptions['align'] = ($_POST['catcloud-align'] != '') ? $_POST['catcloud-align'] : 'justify';
			$newoptions['orderby'] = ($_POST['catcloud-orderby'] != '') ? $_POST['catcloud-orderby'] : 'name';
			$newoptions['order'] = ($_POST['catcloud-order'] != '') ? $_POST['catcloud-order'] : 'ASC';
			$newoptions['min'] = ($_POST['catcloud-min'] != '') ? (int) $_POST['catcloud-min'] : 0;
			$newoptions['hide-empty'] = isset($_POST['catcloud-hide-empty']);
			$newoptions['hide-poweredby'] = isset($_POST['catcloud-hide-poweredby']);
			$exclude_cats = explode(',', trim(strip_tags(stripslashes($_POST['catcloud-exclude']))));
			
			// loop through each excluded cat id, check that it is numeric, otherwise omit
			$exclude = '';
			if ( count($exclude_cats) )
			{
				foreach ($exclude_cats as $exclude_cat)
				{
					$exclude_cat = trim($exclude_cat);
					if ( is_numeric($exclude_cat) )
						$exclude .= "$exclude_cat,";
				}
			}
			$newoptions['exclude'] = ($exclude != '') ? substr($exclude, 0, -1) : '';
		}
		if ( $options != $newoptions )
		{
			$options = $newoptions;
			update_option('widget_catcloud', $options);
		}
		$hide_empty = $options['hide-empty'] ? 'checked="checked"' : '';
		$hide_poweredby = $options['hide-poweredby'] ? 'checked="checked"' : '';
	?>
			<div style="text-align:right">
				<label for="catcloud-title" style="line-height:35px;display:block;">widget title: <input type="text" id="catcloud-title" name="catcloud-title" value="<?php echo htmlspecialchars($options['title']); ?>" /></label>
				<label for="catcloud-small" style="line-height:35px;display:block;">minimum font: <input type="text" id="catcloud-small" name="catcloud-small" value="<?php echo htmlspecialchars($options['small']); ?>" /></label>
				<label for="catcloud-big" style="line-height:35px;display:block;">maximum font: <input type="text" id="catcloud-big" name="catcloud-big" value="<?php echo $options['big']; ?>" /></label>
				<label for="catcloud-unit" style="line-height:35px;display:block;">which font unit would you like to use: <select id="catcloud-unit" name="catcloud-unit"><option value="%" <?php selected('%',$options['unit']); ?>>%</option><option value="px" <?php selected('px',$options['unit']); ?>>px</option><option value="pt" <?php selected('pt',$options['unit']); ?>>pt</option></select></label>
				<label for="catcloud-align" style="line-height:35px;display:block;">cloud alignment: <select id="catcloud-align" name="catcloud-align"><option value="left" <?php selected('left',$options['align']); ?>>left</option><option value="right" <?php selected('right',$options['align']); ?>>right</option><option value="center" <?php selected('center',$options['align']); ?>>center</option><option value="justify" <?php selected('justify',$options['align']); ?>>justify</option></select></label>
				<div style="line-height:35px;display:block;">
					<label for="catcloud-orderby">order by: <select id="catcloud-orderby" name="catcloud-orderby"><option value="count" <?php selected('count',$options['orderby']); ?>>count</option><option value="name" <?php selected('name',$options['orderby']); ?>>name</option></select></label>
					<label for="catcloud-order"><select id="catcloud-order" name="catcloud-order"><option value="ASC" <?php selected('ASC',$options['order']); ?>>asc</option><option value="DESC" <?php selected('DESC',$options['order']); ?>>desc</option></select></label>
				</div>
				<label for="catcloud-min" style="line-height:35px;display:block;">minimum # of posts: <input type="text" id="catcloud-min" name="catcloud-min" value="<?php echo $options['min']; ?>" /></label>
				<label for="catcloud-hide-empty" style="line-height:25px;display:block;">hide empty categories? <input class="checkbox" type="checkbox" <?php echo $hide_empty; ?> id="catcloud-hide-empty" name="catcloud-hide-empty" /></label></p>
				<label for="catcloud-poweredby" style="line-height:25px;display:block;">hide 'powered by ...' link? <input class="checkbox" type="checkbox" <?php echo $hide_poweredby; ?> id="catcloud-hide-poweredby" name="catcloud-hide-poweredby" /></label></p>
				<label for="catcloud-exclude" style="line-height:35px;display:block;">categories ids to exclude (separated by commas): <textarea id="catcloud-exclude" name="catcloud-exclude" style="width:290px;height:20px;"><?php echo $options['exclude']; ?></textarea></label>
				<input type="hidden" name="catcloud-submit" id="catcloud-submit" value="1" />
			</div>
	<?php
	}

	// This prints the widget
	function widget_catcloud($args)
	{
		extract($args);
		$defaults = array('small' => 50, 'big' => 150, 'unit' => '%', 'align' => 'justify', 'orderby' => 'name', 'order' => 'ASC', 'min' => 0, 'hide-empty' => 1, 'hide-poweredby' => 1);
		$options = (array) get_option('widget_catcloud');
	
		foreach ( $defaults as $key => $value )
			if ( !isset($options[$key]) )
				$options[$key] = $defaults[$key];
		
		echo $before_widget;
		
		// omit title if not specified
		if ($options['title'] != '')
			echo $before_title . $options['title'] . $after_title;
		
		if ($options['exclude'] != '')
			$exclude = '&exclude=' . $options['exclude'];
		
		$hide_empty = '&hide_empty=' . $options['hide-empty'];
		
		
		// check which version of wp is being used
		if ( function_exists('get_categories') )
		{
			// new version of wp (2.1+)
			$cats = get_categories("style=cloud&show_count=1&use_desc_for_title=0$exclude&hierarchical=0$hide_empty");
			
			foreach ($cats as $cat)
			{
				$catlink = get_category_link( $cat->cat_ID );
				$catname = $cat->cat_name;
				$count = $cat->category_count;
				if ($count >= $options['min'])
				{
					$counts{$catname} = $count;
					$catlinks{$catname} = $catlink;
				}
			}
			
		}
		else
		{
			// old version of wp (pre-2.1)
			$cats = wp_list_cats("list=0&sort_column=name&optioncount=1&use_desc_for_title=0$exclude&recurse=1&hierarchical=0$hide_empty");
			
			$cats = explode("<br />\n", $cats);
			foreach ($cats as $cat)
			{
				$regs = array(); // initialise the regs array
				eregi("a href=\"(.+)\" ", $cat, $regs);
				$catlink = $regs[1];
				$cat = trim(strip_tags($cat));
				eregi("(.*) \(([0-9]+)\)$", $cat, $regs);
				$catname = $regs[1];
				$count = $regs[2];
				if ($count >= $options['min'])
				{
					$counts{$catname} = $count;
					$catlinks{$catname} = $catlink;
				}
			}
		}
		
		$spread = max($counts) - min($counts); 
		if ($spread <= 0) { $spread = 1; };
		$fontspread = $options['big'] - $options['small'];
		$fontstep = $spread / $fontspread;
		if ($fontspread <= 0) { $fontspread = 1; }
		
		echo '<p class="catcloud" style="text-align:'.$options['align'].';">';
		
		if ('count' == $options['orderby'])
		{
			if ('DESC' == $options['order'])
				arsort($counts);
			else
				asort($counts);
		}
		elseif ('name' == $options['orderby'])
		{
			if ('DESC' == $options['order'])
				uksort($counts, create_function('$a, $b', 'return -(strnatcasecmp($a, $b));'));
			else
				uksort($counts, 'strnatcasecmp');
		}
		
		foreach ($counts as $catname => $count)
		{
			$catlink = $catlinks{$catname};
			echo "\n<a href=\"$catlink\" title=\"$count posts filed under $catname\" style=\"font-size:".
				($options['small'] + ceil($count/$fontstep)).$options['unit']."\">$catname</a> ";
		}
		
		echo '</p>';
		
		if (!$options['hide-poweredby'])
			echo '<p style="font-size:xx-small;font-style:italic;text-align:right;"><a href="http://leekelleher.com/wordpress/plugins/category-cloud-widget/">-- Powered by Category Cloud</a></p>';
		
		echo $after_widget;
		
	}
	
	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget('Category Cloud', 'widget_catcloud');
	register_widget_control('Category Cloud', 'widget_catcloud_control', 315, 420);
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('init', 'widget_catcloud_init');

?>