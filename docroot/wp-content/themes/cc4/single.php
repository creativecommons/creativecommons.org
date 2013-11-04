<?php 
// "Single" template will always, by definition, have a single post.
// I'm quite sure this will not change, except on opposites day, perhaps.
if (have_posts())  {
    the_post(); 
} else {
  require (TEMPLATEPATH . '/404.php');
  exit();
} ?>

<?php get_header(); ?>
    <?php
     // check if this single is a commoner or blog post
     in_category(7) ? $is_commoner = true : $is_commoner = false;
     (in_category(1)  || in_category(128)) ? $is_blog = true : $is_blog = false;

     // check worldwide categories
     in_category(18) ? $is_worldwide_upcoming = true : $is_worldwide_upcoming = false;
     in_category(19) ? $is_worldwide_in_progress = true : $is_worldwide_in_progress = false;
     in_category(20) ? $is_worldwide_completed = true : $is_worldwide_completed = false;
     in_category(21) ? $is_worldwide = true : $is_worldwide = false;

     foreach ((get_the_category()) as $cat) {
       if ($cat->category_parent == 21) {
         $jurisdiction_name = $cat->cat_name;
         $jurisdiction_code = $cat->category_nicename;
       }
     }
    ?>
  
    <div id="body">
       <div id="content">
        <div id="main-content">
          <? if ($is_commoner) {?>
          <div id="alpha" class="content-box">
          <? } ?>
    
    			<div class="block" id="title">
						<!--img src="images/info.png" align="left"/-->
<? if ($is_commoner) {?>
						<h3 class="category">
						  <? $cat = get_the_category(); (count($cat) > 1) ? $cat = $cat[0] : $cat = $cat[0]; ?>
							<a href="<?php echo get_option('home') . "/" . $cat->category_nicename . "/"; ?>">
								<? echo $cat->cat_name; ?>
							</a>
						</h3>
<? } else if ($is_worldwide) { ?>
						<h3 class="category">
							<a href="<?php echo get_settings('home') . "/" ?>international">
								Creative Commons International 
							</a>
						</h3>
<? } else if ($category_name == "weblog") { ?>
						<h3 class="category">
							<a href="<?php echo get_settings('home') . "/" ?>weblog/">
								weblog
							</a>
						</h3>
<? } else if ($category_name == "press-releases") { ?>
						<h3 class="category">
							<a href="<?php echo get_settings('home') . "/" ?>press-releases/">
								press-releases 
							</a>
						</h3>
<? } ?>

<? if ($is_worldwide && $jurisdiction_code != '') { ?>
						<h2>
							<img src="/images/international/<?php echo $jurisdiction_code ?>.png" alt="<?php echo $jurisdiction_code ?> flag" class="flag" /><?php 
								the_title(); ?>
							</h2>
<? } else { ?>
						<h2> <?php the_title(); ?></h2>
<? } ?>
					</div>
    
            <div class="block <? if ($is_worldwide) { ?>international<? } ?>" id="post-<?php the_ID(); ?>">
<? if (!$is_worldwide) { ?>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
<? } ?>

<? if ($is_worldwide) { 

     $license_xml = new LicenseXml();

     $jurisdiction_site = $license_xml->getJurisdictionSite($jurisdiction_code);

     if ($jurisdiction_site) {
?>

<div class="licensebox" style="margin:14px;">
Visit the jurisdiction site <a href="<?=$jurisdiction_site?>">here</a>.
</div>
<? 
     }
} 
?>

<? if ($is_worldwide_completed) { ?>
              <div class="licensebox" style="margin:14px;">
                <p>The <? echo $jurisdiction_name ?> license has now been integrated 
                into <a href="/license/?jurisdiction=<?php echo $jurisdiction_code ?>">the Creative 
                Commons licensing process</a>, so you are able to license your works under this 
                jurisdiction's law. </p> 
                <p>The latest version of the licenses available for this jurisdiction are:</p>
                <ul>
                  <?php
                          $licenses = $license_xml->getLicensesCurrent($jurisdiction_code);
                          foreach ($licenses as $l) {
                              $l[name] = $license_xml->getLicenseName($l[uri]);
                              echo "<li><a href='$l[uri]'>$l[name]</a></li>\n";
                          }
                      /* } */ ?>
                </ul>
                <p>Many thanks to all who contributed to the license-porting process. This page 
                remains for reference.</p>
                <p>Please take a look at the mailing-list archive if you are interested in the 
                academic discussion leading to the <span><?php echo $jurisdiction_name ?></span> 
                final license.</p>
              </div>
              <? } ?>
              <?php the_content(); ?>
              <div class="comments"><?php if ($is_blog) comments_template(); ?></div>
          </div>
          <? if ($is_commoner) { ?>
          </div>
          <div id="beta" class="content-box">
            <? if ($attach = cc_get_attachment ($post->ID)) { ?>
            <img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0" width="150" /><br/>
            <h3><?= the_title() ?></h3>
            <? } ?>
          </div>
          </div>
          <? }?>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
