<?php get_header(); ?>

<div id="mainContent" class="box">
  <div id="contentPrimary">
          <div class="block" id="title"><h2>Sorry...</h2></div>
            <div class="block">
              <p>The page you were looking for was not found.<br/>&nbsp;</p>
              <h4>Other Content</h4>
              <ul class="archives">
                <li><a href="<?php echo get_settings('home'); ?>">Home</a></li>
                <li><a href="<?php echo get_settings('home'); ?>/weblog/">Weblog</a></li>
                <li><a href="http://wiki.creativecommons.org">Wiki</a></li>
                <li><a href="http://search.creativecommons.org">ccSearch</a></li>
              </ul>
          </div>
        </div>
          
<?php get_sidebar(); ?>
<?php get_footer(); ?>
