<?php
    $premium_lenses = array(
        'leather' => array(
            'thumbnail' => "https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/leather/thumbnail-large.jpg",
            'name' => "Leather",
            'description' => "A simple, configurable lens with a skeuomorphic twist.",
            'utm_content' => "SD2LENSLEATHER"
        ),
        'titles' => array(
            'thumbnail' => "https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/titles/thumbnail-large.jpg",
            'name' => "Titles",
            'description' => "Ideal for showcasing 5 or more of your most popular blog posts.",
            'utm_content' => "SD2LENSTITLES"
        ),
        'polarad' => array(
            'thumbnail' => "https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/polarad/thumbnail-large.jpg",
            'name' => "Polarad",
            'description' => "Great for sidebars and profile pages. Also shows Instagram likes.",
            'utm_content' => "SD2LENSPOLARAD"
        )
    );
?>
<?php foreach( $premium_lenses as $slug => $lens_meta ): ?>
    
    <?php if( !in_array( $slug, $lens_slugs ) ) : ?>

        <div class="lens add-lens">
            <div class="inner">
                <img src="<?php echo $lens_meta['thumbnail']; ?>" />
                <h4><?php echo $lens_meta['name']; ?></h4>
                <p><?php echo $lens_meta['description']; ?></p>
                <div class="upgrade-button-cta">
                    <a href="http://www.slidedeck.com/premium-lenses-ee0f2/?lens=<?php echo $slug; ?>&utm_source=premium_lenses_page&utm_medium=link&utm_content=<?php echo $lens_meta['utm_content']; ?>&utm_campaign=upgrade<?php echo self::get_cohort_query_string( '&' ) . slidedeck2_km_link( 'Browse Premium Lens', array( 'name' => $lens_meta['name'], 'location' => 'Lens Management' ) ); ?>" target="_blank" class="upgrade-button green">
                        <span class="button-noise">
                            <span>Add <?php echo $lens_meta['name']; ?> Lens</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="actions"></div>
        </div>
        
    <?php endif; ?>
    
<?php endforeach; ?>
