<?php 

// "Single" template will always, by definition, have a single post.
// I'm quite sure this will not change, except on opposites day, perhaps.
if ( have_posts() )  {
    the_post(); 
} else {
    require (TEMPLATEPATH . '/404.php');
    exit();
}

get_header();

// check worldwide categories
in_category(18) ? $is_worldwide_upcoming = true : $is_worldwide_upcoming = false;
in_category(19) ? $is_worldwide_in_progress = true : $is_worldwide_in_progress = false;
in_category(20) ? $is_worldwide_completed = true : $is_worldwide_completed = false;
//in_category(21) ? $is_worldwide = true : $is_worldwide = false;

foreach ( get_the_category() as $cat ) {
    if ( $cat->category_parent == 21 ) {
        $jurisdiction_name = $cat->cat_name;
        $jurisdiction_code = $cat->category_nicename;
    }
}

$api_url = "http://api.creativecommons.org/rest/dev/license/standard/jurisdiction/";

if ( $is_worldwide_completed ) {
    $jurisdiction_dom = new DOMDocument();
    $jurisdiction_dom->loadXML( file_get_contents($api_url . $jurisdiction_code) );

    $jurisdiction_dom_root = $jurisdiction_dom->documentElement;
    $jurisdiction_site = $jurisdiction_dom_root->getAttribute('local_url'); 
    $license_elements = $jurisdiction_dom->getElementsByTagName('license');
    $licenses = array();

    for ( $i=0; $i < $license_elements->length; $i++ ) {
        $license_url = $license_elements->item($i)->getAttribute('url');
        $license_name = $license_elements->item($i)->getAttribute('name');
        preg_match('/licenses\/(.*)\/\d\.\d/', $license_url, $matches);
        $licenses[$matches[1]]['url'] = $license_url;
        $licenses[$matches[1]]['name'] = $license_name;
    }
  
    ksort($licenses);
}

$home_settings = get_settings('home');

echo <<<HTML
<div id="mainContent" class="box single">
    <div id="contentPrimary">
        <div class="block" id="title">
            <h3 class="category">
                <a href="{$home_settings}/international">
                    CC Affiliate Network
                </a>
            </h3>
HTML;

$the_title = get_the_title();
$the_ID = get_the_ID();

if ( $jurisdiction_code != '' ) {
    echo <<<HTML
            <h2>
                <img src="/images/international/{$jurisdiction_code}.png" alt="{$jurisdiction_code} flag" class="flag" /> 
                $the_title
            </h2>
        </div>
        <div class="block international" id="post-{$the_ID}">
HTML;
}

$jurisdiction_site_url_parts = parse_url($jurisdiction_site);

if ( $jurisdiction_site && $jurisdiction_site_url_parts['host'] != 'creativecommons.org' ) {
    echo <<<HTML
        <div class="licensebox" style="margin:14px;">
            Visit the <a href="{$jurisdiction_site}">jurisdiction's site</a>.
        </div>
HTML;
}

if ( $is_worldwide_completed ) {
    echo <<<HTML
        <div class="licensebox" style="margin:14px;">
            <p>
               The Creative Commons $jurisdiction_name license suite is available in the following version. <a href='/choose/?jurisdiction=$jurisdiction_code'>License your work</a> under these licenses, or <a href='/choose'>choose</a> the international licenses. <a href='http://wiki.creativecommons.org/FAQ#Should_I_choose_an_international_license_or_a_ported_license.3F'>More info</a>.
            </p> 
            <ul>
HTML;

    foreach ( $licenses as $license ) {
        echo "          <li><a href='{$license['url']}'>{$license['name']}</a></li>\n";
    }

    echo <<<HTML
            </ul>
            <p>
		Many thanks to all who contributed to the localization of the
                license suite. The information below remains for reference.
            </p>
            <p>
		Please take a look at the mailing-list archive and <a
		href='http://wiki.creativecommons.org/Jurisdiction_Database'>Jurisdiction
		Database</a> if you are interested in the discussion leading to the most recent
		localization effort and other activities in this jurisdiction.
            </p>
        </div>
HTML;
}

the_content();

dynamic_sidebar('Single Post');

echo "  </div>";
echo "</div>";

get_sidebar();
get_footer();

?>
