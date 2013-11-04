<?php
$category_name = "Creative Commoners";

// grab a random selection of commoners
$commoners = $wpdb->get_results( "SELECT * FROM wp_posts, wp_post2cat WHERE wp_posts.ID = wp_post2cat.post_id AND wp_post2cat.category_id = 7 ORDER BY wp_posts.ID DESC;"); // ORDER BY rand() ;" );

?>
<?php get_header(); ?>

<style type="text/css" media="screen">
  .commoner {
    width: 150px;
    height: 150px;
    float: left;
    margin: 10px;
    overflow: hidden;
    border: 1px solid #ddd;
    padding: 3px;
    position: relative;
  }
  .commonerimg {
   height: 150px;
   margin-bottom: 10px;
  }
  .commonerTitle {
    position: absolute;
    top: 8px;
    left: 8px;
    width: 140px;
    height: 140px;
    opacity: 0.8;
    background-color: #fff;
    display:none;
  }
  
  .commonerTitle a {
    display: block;
    color: #000;
    width: 130px;
    height: 130px;
    padding: 5px;
  }
  .commonerTitle a:hover {
    text-decoration: none;
  }
</style>
<script type="text/javascript" charset="utf-8">
  lastCommoner = null;

  /* Make the child div visible 
     And hide previous tag if the mouseOut signal was missed */
  function showTitle(e) {
    if (lastCommoner) hideTitle(lastCommoner);
    div = e.parentNode.getElementsByTagName("div");
    if (div[0]) div[0].style.display = "block";
    lastCommoner = div[0];
  }
  
  /* hide the div directly so we don't into a wacky race condition */
  function hideTitle(e) {
    e.style.display = "none";
    lastCommoner = null;
  }
</script>

    <div id="body">
      <div id="splash">
        <h1>
         Creative Commoners
        </h1>
        <div id="blurb"><?php echo category_description() ?></div>
        <div id="splash-menu">
          
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="page">
<?php if ($commoners)  { ?>
<?php foreach ($commoners as $post) { ?>
            <div class="commoner">
                <? if ($attach = cc_get_attachment ($post->ID)) { ?>
              <a href="<?php the_permalink() ?>" onmouseover="showTitle(this);">
		<img align="left" src="<?= $attach->uri ?>" alt="<?= $post->post_title ?>" title="<?= $post->post_title ?>" class="commonerimg" border="0"/>
              </a><br/>
              <? } ?>
             <div class="commonerTitle" onmouseout="hideTitle(this);">
                <h1 class="title"><a href="<?php the_permalink() ?>"><?php the_title();?></a></h1>
              </div>
            </div>
<?php } }?>
          </div>
        </div>  
<?php get_sidebar(); ?>
<?php get_footer(); ?>
