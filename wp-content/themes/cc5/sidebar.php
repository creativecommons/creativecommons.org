<div id="sidebar">
<?php  
  if (is_page() or is_single()) {
    edit_post_link('<span>Edit this article...</span>', '<div class="sideitem">', '</div>'); 
  }
?>
<?php if (!is_search()) { ?>
<div class="sideitem">
    <form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
      <div>
        <input type="text" value="" name="s" id="s" class="inactive" /> <input type="submit" id="searchsubmit" value="Go" />
      </div>
    </form>

    <div class="clear"></div>
  </div>
<?php } ?>
  <div class="sideitem">
    <ul>
      <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/license-alt.png" alt="License your work" />&nbsp;&nbsp;<a href="/choose">License your work</a></li>
      <li><img src="<?php bloginfo('stylesheet_directory'); ?>/images/find-alt.png" alt="License your work" width="12" height="12" />&nbsp;&nbsp;<a href="http://search.creativecommons.org/">Find licensed works</a></li>
    </ul>
  </div>
  
  <?php
  	$exclude_list = "23670,7486,7476,7471,7472,7473,7506,7470,7474,7475,7487,7505,7682,7793,7794,12354,7499,7502,7501";
  	$list_pages_query = "&title_li=&echo=0&exclude=".$exclude_list;
  	if ($post->post_parent) {
  		if ($root_post_id = get_post_meta($post->ID, "root", true)) {
  			$child_id = $root_post_id;
  		} else {
  			$child_id = $post->post_parent;
  		}
  	} else {
  		$child_id = $post->ID;
  	}

  	if ($child_id) { $pages_list = wp_list_pages('child_of='.$child_id.$list_pages_query); }

      if ($pages_list) {
  		$pages_list = "<div class=\"sideitem\"><ul>" . $pages_list . "</ul></div>";
  		echo $pages_list;
  	}
   ?> 
<div class="sideitem">
    <ul>
    	<?php include "sidelinks.php"; ?>
    </ul>
  </div>

</div>

</div>
