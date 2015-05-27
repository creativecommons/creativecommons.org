<?php
/*
 * Theme Name: 960 Base Theme
 * Theme URI: http://960basetheme.kiuz.it
 * Description: Wordpress theme based on 960 Grid System
 * Author: Domenico Monaco
 * Author URI: http://www.kiuz.it
 * Version: 0.5
 */

?>
</div><!-- end content -->

<div id="sidebars" class="grid_6 right-sidebar">
	<div id="r-sidebar">


<?php	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar 1') ) : ?>
<div class="widget widget_category  box" id="category">
	<h2><a href="#"><?php _e('Category',TEMPLATE_DOMAIN); ?></a></h2>
	<div class="category">
	<ul><?php wp_list_categories('show_count=1&title_li='); ?></ul>
	</div>
</div>



<div class="widget widget_cerca box menu" id="cerca">
	<h2><a href="#">Search</a></h2>
	<div class="search-widget">
	<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/"><input type="text" name="s" id="s" value="Inserisci testo" onfocus="document.forms['searchform'].s.value='';" onblur="if (document.forms['searchform'].s.value == '') document.forms['searchform'].s.value='Search Keywords';" /><input type="submit" id="searchsubmit" value="Cerca" /></form>
	</div>
</div>


<div class="widget widget_tags box menu" id="tags">
	<h2><a href="#">Search</a></h2>
	<div class="tags">
		<?php wp_tag_cloud('smallest=8&largest=18&number=100&orderby=name&order=ASC'); ?>
	</div>
</div>

<div class="widget widget_meta box menu" id="meta">
	<h2><a href="#"><?php _e('Archives',TEMPLATE_DOMAIN); ?></a></h2>
	<div class="archive">
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</div>
</div>

<?php endif; ?>



	</div><!-- end #r-sidebar -->
</div><!-- end #sidebars -->

