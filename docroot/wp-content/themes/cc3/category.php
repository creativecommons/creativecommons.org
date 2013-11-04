<?php get_header(); ?>
    <div id="body">
      <div id="splash">
        <? if (is_month() || is_year()) { ?> 
        <h3 class="category">
          <a href="<?php echo get_settings('home') . "/" . $category_name; ?>">
            <?= ucfirst($category_name) ?>
          </a>
        </h3>
        <? }?>
        <h1>
          <img src="/images/categories/<?= $category_name ?>.png" alt="[ <?= $category_name ?> ]" border="0" class="category-icon"/>
          <?php wp_title(''); ?>
        </h1>
        <div id="blurb"><?php echo category_description() ?></div>
        <div id="splash-menu">
          
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
<?php if (have_posts())  { ?>
<?php while (have_posts()) { the_post(); ?>
            <div class="post" id="post-<?php the_ID(); ?>">
                <? if ($attach = cc_get_attachment ($post->ID)) { ?>
              <a href="<?php the_permalink() ?>">
		<img align="left" src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" class="commoner" border="0"/>
              </a>
              <? } ?>
              <div class="excerpt">
                <h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title();?></a></h1>
                <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
                <?php the_excerpt(); ?>
                <?php edit_post_link('Edit', '', ''); ?>
              </div>
            </div>
<?php } }?>
            <?php posts_nav_link(' &mdash; ', 'previous page', 'next page'); ?>
          </div>
          <div id="features">
            <h4>Archives</h4>
            <ul class="archives">
            <?php cc_get_cat_archives(cc_cat_to_id($category_name), 'monthly', '', 'html', '', '', TRUE); ?>
            </ul>

            <?php include ("featured/$category_name.php"); ?>
            
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
