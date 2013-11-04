<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <h1>404</h1>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="page">
    
            <div class="post">
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
        </div>
          
<?php get_sidebar(); ?>
<?php get_footer(); ?>
