<div id="sidebar" class="content-box-right">
  <div class="sideitem">
    <h4><a href="http://creativecommons.org/international/">International</a></h4>
      <select id="international" name="sortby" onchange="orderby(this)"> 	 
	       <option value="">Select a jurisdiction</option>
      <script type="text/javascript" src="http://api.creativecommons.org/rest/dev/support/jurisdictions.js"></script>
      </select>  
    <span class="international"><a href="/international/">More information</a></span>
  </div>

<?php if (!is_home() && 0 ) { ?>
  <div class="sideitem">
  <?php include ('progress.php'); ?>
  </div> <?php } ?>

  <div class="sideitem">
    <h4>Search</h4>
    <?php $is_sidebar = true; include (TEMPLATEPATH . '/searchform.php'); ?>
    <div class="clear"></div>
  </div>

<?php if (!is_home()) { 
	$exclude_list = "7486,7476,7471,7472,7473,7506,7474,7475,7487,7505,7682,7793,7794,12354,7499,7502,7501";
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
 } ?>
  <div class="sideitem">
    <ul>
      <li ><a href="/commoners" >Creative Commoners</a></li>
      <li><a href="/projects/documentation">Documentation</a></li>
      <li><a href="/projects/casestudies">Case Studies</a></li>
      <!-- li><a href="/about/opportunities">Opportunities</a></li-->
      <li><a href="http://wiki.creativecommons.org/Events">Events</a></li>
      <li><a href="/about/newsletter">Newsletter</a></li>
      <li><a href="/about/press">Press Room</a></li>
    </ul>
  </div>

  <div class="sideitem">
    <h4>The Commons</h4>
    <ul>
	  <li><a href="https://creativecommons.net/">CC Network</a></li>
      <li><!-- img src="/images/commons/sc.png" alt="Science Commons" /--> <a href="http://sciencecommons.org/">Science Commons</a></li>
      <li><!-- img src="/images/commons/cci.png" alt="ccInternational" /--> <a href="http://creativecommons.org/international/">ccInternational</a></li>
      <li><!-- img src="/images/commons/learn.png" alt="ccLearn" /--> <a href="http://learn.creativecommons.org/">ccLearn</a></li>
	</ul>
  </div>


</div>
