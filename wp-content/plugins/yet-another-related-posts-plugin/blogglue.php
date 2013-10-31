<?php
function yarpp_blogglue_enqueue() {
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );
}
add_action( 'admin_enqueue_scripts', 'yarpp_blogglue_enqueue' );

function add_yarpp_blogglue_meta_box() {
	class YARPP_Meta_Box_BlogGlue extends YARPP_Meta_Box {
		function display() {
			$pluginurl = plugin_dir_url(__FILE__);
			?>
<style type="text/css">
#blogglue_upsell .center {
	text-align: center;
}
#blogglue_upsell ul {
	text-align: left;
	margin: 10px 0 10px 15px;
}
#blogglue_upsell ul li {
	list-style: disc outside !important;
}
#TB_ajaxContent {
	height: 480px !important;
	padding: 10px;
	overflow: hidden;
}
ul.install_help {
	list-style-type: disc;
	list-style-position: inside;
	text-align: left;
	margin: 20px 0px;
}
</style>
<div id="blogglue_upsell">
	<p class="center"><img src="http://s3.amazonaws.com/arkayne-media/img/logo-md.png" alt="BlogGlue Logo"/></p>
	<p>In addition to related links on your own site, YARPP and BlogGlue have partnered to increase your blog’s audience by:</p>
	<ul>
		<li>delivering a new audience from trusted partners with relevant, crosslinked content</li>
		<li>improving search engine placement</li>
		<li>pulling traffic from Facebook, Twitter and LinkedIn</li>
	</ul>
	<p class="center"><a href="<?php echo  wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=arkayne-site-to-site-related-content'), 'install-plugin_arkayne-site-to-site-related-content'); ?>"><img src="http://s3.amazonaws.com/arkayne-media/img/installnow.png" alt="Install Now!"/></a><img src="http://www.blogglue.com/cohorts/track/yarpp_sidebar.gif"/></p>
	
	<p class="center">Want to learn more? <a href="#TB_inline?title=rar&height=450&width=610&inlineId=blogglue_video" title="Free Upgrade To BlogGlue: More Information" class="thickbox">Watch A Video</a></p>
</div>
<div id="blogglue_video" style="display: none;">
	<img src="http://s3.amazonaws.com/arkayne-media/img/logo.png" alt="BlogGlue Logo"/>

	<iframe src="http://player.vimeo.com/video/33007489?title=0&amp;byline=0&amp;portrait=0&amp;color=ff6300" width="601" height="338" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

	<p style="text-align:center;"><a href="<?php echo  wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=arkayne-site-to-site-related-content'), 'install-plugin_arkayne-site-to-site-related-content'); ?>"><img src="http://s3.amazonaws.com/arkayne-media/img/installnow.png" alt="Install Now!"/></a></p>
</div>
	<?php
		}
	}
	
	add_meta_box('yarpp_display_blogglue', 'Free Upgrade To BlogGlue', array(new YARPP_Meta_Box_BlogGlue, 'display'), 'settings_page_yarpp', 'side', 'core');
}
add_action( 'add_meta_boxes_settings_page_yarpp', 'add_yarpp_blogglue_meta_box' );
