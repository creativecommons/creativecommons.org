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

     foreach ((get_the_category()) as $cat) {
       if ($cat->category_parent == 21) {
         $jurisdiction_name = $cat->cat_name;
         $jurisdiction_code = $cat->category_nicename;
       }
     }
    ?>
  
    <div id="mainContent" class="box single">
      <div id="contentPrimary">
          <? if ($is_commoner) {?>
          <div id="alpha" class="content-box">
          <? } ?>
    
    			<div class="block" id="title">
						<!--img src="images/info.png" align="left"/-->
<? if ($is_commoner) { ?>
						<h3 class="category">
						  <? $cat = get_the_category(); (count($cat) > 1) ? $cat = $cat[0] : $cat = $cat[0]; ?>
							<a href="<?php echo get_option('home') . "/" . $cat->category_nicename . "/"; ?>">
								<? echo $cat->cat_name; ?>
							</a>
						</h3>
<? } else if ($is_blog) { ?>
						<h3 class="category">
							<a href="<?php echo get_category_link(1);?>">
								News
							</a>
						</h3>
<? } else if ($category_name == "press-releases") { ?>
						<h3 class="category">
							<a href="<?php echo get_settings('home') . "/" ?>press-releases/">
								press-releases 
							</a>
						</h3>
<? } ?>

						<h2> <?php the_title(); ?></h2>
					</div>
    
            <div class="block" id="post-<?php the_ID(); ?>">
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>


			  <?php the_content(); ?>

				<div class="twitter">
					<a href="http://twitter.com/home?status=<?php the_title() ?> - <?php the_permalink() ?> via @creativecommons">Share on Twitter</a>
				</div>
        <?php 
        	dynamic_sidebar('Single Post');
        ?>
<?php if (get_the_tags()) { ?>
                <div class="postTags">
<?php
						          the_tags(); 
						  ?>      
                </div>
<?php } ?>

			  <div class="comments"><?php if ($is_blog) comments_template(); ?></div>
          </div>
          <? if ($is_commoner) { ?>
          </div>
          <? }?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
