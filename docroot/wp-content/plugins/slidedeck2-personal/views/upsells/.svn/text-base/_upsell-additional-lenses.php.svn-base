<?php
    $premium_lenses = array(
        'leather' => array(
            'thumbnail' => "https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/leather/thumbnail.jpg",
            'name' => "Leather",
            'utm_content' => "SD2LENSLEATHER"
        ),
        'titles' => array(
            'thumbnail' => "https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/titles/thumbnail.jpg",
            'name' => "Titles",
            'utm_content' => "SD2LENSTITLES",
            'notcustom' => true
        ),
        'polarad' => array(
            'thumbnail' => "https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/polarad/thumbnail.jpg",
            'name' => "Polarad",
            'utm_content' => "SD2LENSPOLARAD",
            'notcustom' => true
        )
    );
?>
<?php foreach( $premium_lenses as $slug => $lens_meta ): ?>
    
    <?php if( !in_array( $slug, $lens_slugs ) ): ?>
        
        <?php
            $output_lens_upsell = true;
            if( ( $slidedeck['source'][0] == 'custom' ) && isset( $lens_meta['notcustom'] ) && ( $lens_meta['notcustom'] == true ) )
                $output_lens_upsell = false;
        ?>
        
        <?php if( $output_lens_upsell ): ?>    
            <a href="http://www.slidedeck.com/premium-lenses-ee0f2/?lens=<?php echo $slug; ?>&utm_source=premium_lenses_tab&utm_medium=link&utm_content=<?php echo $lens_meta['utm_content']; ?>&utm_campaign=upgrade<?php echo self::get_cohort_query_string('&') . slidedeck2_km_link( 'Browse Premium Lens', array( 'name' => $lens_meta['name'], 'location' => 'Lens Choices Tab' ) ); ?>" target="_blank" class="lens placeholder" rel="lenses">
                <span class="thumbnail"><img src="<?php echo $lens_meta['thumbnail']; ?>" /></span>
                <span class="shadow">&nbsp;</span>
                <span class="title"><?php echo $lens_meta['name']; ?></span>
            </a>
        <?php endif; ?>
                
    <?php endif; ?>
    
<?php endforeach; ?>
<?php if( ($tier == 'tier_10') && (!in_array( 'classic', $lens_slugs )) ) : ?>
<a href="#lens-upgrade" class="lens placeholder upgrade-modal" rel="lenses">
    <span class="thumbnail"><img src="https://s3.amazonaws.com/slidedeck-pro/upsell_assets/images/lenses/classic/thumbnail.jpg" /></span>
    <span class="title has-subtitle">Classic</span>
    <span class="subtitle">Professional tier &amp; higher</span>
</a>
<?php endif; ?>