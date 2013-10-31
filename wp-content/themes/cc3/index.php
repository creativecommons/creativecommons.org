<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <h2 class="tagline"><?php bloginfo('description'); ?></h2>
        <div id="blurb"><?= cc_intro_blurb() ?></div>
        <div id="splash-menu">
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
            <div class="post feature">
              <?= cc_current_feature(); ?>
            </div>
            <h4>Latest News</h4>
<?php // Get the last 5 posts in the blog category. ?>
<?php // FIXME: perhaps make this configurable in theme settings...? ?>
<?php query_posts('category_name=weblog&showposts=4'); ?>
<?php if (have_posts())  { 
  while (have_posts()) { the_post(); ?>
            <div class="post blogged" id="post-<?php the_ID(); ?>">
              <h1 class="title">
                <a href="<?php the_permalink() ?>">
                  <?php the_title(); ?>
                </a>
              </h1>
              <h4 class="meta"><?php the_author() ?>, <?php the_time('F jS, Y')?></h4>
              <?php the_content("Read More..."); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } }?>
            <ul class="archives">
            <li><h3><a href="/weblog/archive">Weblog Archives</a></h3></li>
	    <li><h3><a href="/weblog/rss">RSS Feed</a></h3></li></ul>
          </div>

          <div id="features">
            <div style="text-align:center;"><a href="/support"><img src="/images/support/2007/support-btn-big.png" border="0" alt="support cc"/></a></div>
            <div class="content-foot">&nbsp;</div>

            <h4>Featured Projects</h4>
<?php $my_query = new WP_Query('category_name=featured-projects&showposts=2'); ?>
<?php while ($my_query->have_posts()) { $my_query->the_post(); ?>
            <div class="post">
  <?php if ($attach = cc_get_attachment ($post->ID)) { ?>
              <a href="<?= get_post_meta ($post->ID, "url", TRUE)?>"><img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0" /></a><br/>
  <?php } ?>
	      <strong><a href="<?= get_post_meta ($post->ID, "url", TRUE)?>"><?= $post->post_title ?></a></strong>
              <?php the_content(); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } ?>
            <div class="content-foot"><a href="<?php echo get_settings('home'); ?>/featured-projects"><em>More...</em></a></div>

            <h4>Featured Commoners</h4>
<?php $my_query = new WP_Query('category_name=commoners&showposts=2'); ?>
<?php while ($my_query->have_posts()) { $my_query->the_post(); ?>
            <div class="post">
  <?php if ($attach = cc_get_attachment ($post->ID)) { ?>
                <a href="<?php the_permalink() ?>"><img src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" border="0"/></a>
  <?php } ?>
              <h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
              <div style="float:left;">
                <em><?php the_time('F Y') ?></em> &mdash;
              </div>
              <?php the_excerpt(); ?>
              <?php edit_post_link('Edit', '', ''); ?>
            </div>
<?php } ?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
