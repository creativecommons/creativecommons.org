<?php /* License jurisdiction category page */ ?>

<?php function get_the_jurisdiction($post_id) {
    $cats = get_the_category($post_id);
    $jurisdiction->code = '';
    $jurisdiction->status = '';
    $jurisdiction->name = '';
    foreach ( $cats as $cat ) {
       if ( $cat->category_parent == 21 ) {
           $jurisdiction->code = $cat->category_nicename;
           $jurisdiction->name = $cat->cat_name;
       }
       if ( $cat->cat_ID == 18 ) {
           $jurisdiction->status = 'upcoming';
       } else if ( $cat->cat_ID == 19 ) {
           $jurisdiction->status = 'in-progress';
       } else if ( $cat->cat_ID == 20 ) {
           $jurisdiction->status = 'completed';
       }
    }
    return $jurisdiction;
} ?>

<?php get_header(); ?>

    <div id="body">
      <div id="splash">
        <h1>Worldwide</h1>
        <div id="splash-menu">
        </div>
      </div>

      <div id="content">
        <div id="main-content">
          <div id="blog">
            <p>
             Creative Commons International (CCi) works to "port" the core Creative Commons Licenses to different copyright legislations around the world. The porting work involves both linguistically translating the licenses and legally adapting them to particular jurisdictions. 
            </p>
            <p>
             This work is lead by CCi director <a href="http://creativecommons.org/about/people#65">Catharina Maracke</a> (<a href="mailto:catharina@creativecommons.org">email</a>) and volunteer teams in each jurisdiction who are committed to introducing CC to their country and who consult extensively with members of the public and key stakeholders as part of the porting process.
            </p>
            <p>
             A complete overview of the porting process can be found <a href="http://wiki.creativecommons.org/Worldwide_Overview">here</a>.
            </p>
            <!--p>We are excited to announce Creative Commons International &mdash; an offshoot of our licensing project dedicated to the drafting and eventual adoption of jurisdiction-specific licenses. Creative Commons International is being lead by <a href="http://creativecommons.org/about/people#65">Catharina Maracke</a> (<a href="mailto:catharina@creativecommons.org">email</a>), with help from member jurisdictions.</p-->
            <h3>Completed Licenses</h3>
            <p>We have completed the process and developed licenses for the following jurisdictions:</p>
<?php 
    query_posts("cat=21&orderby=title&order=ASC&posts_per_page=-1");
    if (have_posts())  { ?>
            <div class="icontainer">

<?php while (have_posts()) { the_post();  
    $jurisdiction = get_the_jurisdiction($post->ID);

    if ($jurisdiction->code == '' || $jurisdiction->status == '') {
       // Store post content for placement elsewhere in site
       $block_content[$post->post_name] = $post->post_content;
       continue;
    } else if ($jurisdiction->status != 'completed') {
       continue;
    }
    $img = "/images/international/$jurisdiction->code.png";
?>
              <div class="ifloat"><a href="/worldwide/<?= $jurisdiction->code ?>/"><img class="flag" border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>"  /></a><br /><p><a href="/worldwide/<?= $jurisdiction->code ?>/"><?= $jurisdiction->name ?></a></p></div>
<?php } ?>
            </div>
            <br clear="all" />

            <h3>Project Jurisdictions</h3>

            <p>The process of developing licenses and discussing them are still in progress for the following jurisdictions:</p>

            <div class="icontainer">
<?php rewind_posts(); while (have_posts()) { the_post();
    $jurisdiction = get_the_jurisdiction($post->ID);
    if ($jurisdiction->code == '' || $jurisdiction->status != 'in-progress') {
        continue;
    }
    $img = "/images/international/$jurisdiction->code.png";
?>
              <div class="ifloat"><a href="/worldwide/<?= $jurisdiction->code ?>/"><img class="flag" border="1" src="<?= $img ?>" alt="<?= $jurisdiction->name ?>" /></a><br /><p><a href="/worldwide/<?= $jurisdiction->code ?>/"><?= $jurisdiction->name ?></a></p></div>
<?php } ?>
            </div>
<br clear="all" />
<a name="more"></a>

<?php echo $block_content['more-information']; ?>

          </div>
          <div id="features">
            <h4>Upcoming Project Jurisdictions</h4>
            <ul>
<?php rewind_posts(); while (have_posts()) { the_post();
    $jurisdiction = get_the_jurisdiction($post->ID);
    if ($jurisdiction->code == '' || $jurisdiction->status != 'upcoming') {
       continue;
    }
?>
              <li><strong><?= $jurisdiction->name ?></strong>: <?= $post->post_excerpt ?></li>
<?php } ?>
            </ul>
            <br />

            <?php echo $block_content['upcoming-launch-dates']; ?>

          </div>
        </div>  
<?php } ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
