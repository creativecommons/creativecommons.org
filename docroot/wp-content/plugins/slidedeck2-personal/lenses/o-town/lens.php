<?php
class SlideDeckLens_OTown extends SlideDeckLens_Scaffold {
    var $options_model = array(
        'Appearance' => array(
            'accentColor' => array(
                'value' => "#ff00ff"
            ),
            'titleFont' => array(
                'value' => "oswald"
            ),
            'hideSpines' => array(
                'type' => 'hidden',
                'value' => true
            )
        ),
        'Navigation' => array(
			'navigation-type' => array(
				'name' => 'navigation-type',
				'type' => 'select',
				'values' => array(
                    'nav-numbers' => 'Numbers',
                    'nav-dots' => 'Dots',
                    'nav-thumb' => "Thumbnails"
				),
				'value' => 'nav-numbers',
				'label' => 'Navigation Type',
				'description' => "Note: Dots Navigation Type is limited to a max of 10. If you have more than 10 slides, Thumbnails is better for your users.",
				'weight' => 20
			),
		),
        'Content' => array(
            'date-format' => array()
        ),
        'Playback' => array(
			'autoplay-indicator' => array(
                'name' => 'autoplay-indicator',
                'type' => 'select',
                'values' => array(
                    'autoplay-straight' => 'Straight',
                    'autoplay-snake' => 'Snake',
                    'autoplay-hide' => 'None'
                ),
                'value' => 'autoplay-snake',
                'label' => 'AutoPlay Indicator',
                'description' => "Choose the style of the animated timer when AutoPlay and Thumbnails are enabled",
                'weight' => 30
            ),
            'slideTransition' => array(
                'type' => "hidden",
                'value' => "slide"
            )
		)
    );
	
    function __construct(){
        parent::__construct();
        add_filter( "{$this->namespace}_get_slides", array( &$this, "slidedeck_get_slides" ), 11, 2 );
    }
    
    /**
     * Add appropriate classes for this Lens to the SlideDeck frame
     * 
     * @param array $slidedeck_classes Classes to be applied
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * 
     * @return array
     */
    function slidedeck_frame_classes( $slidedeck_classes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            $slidedeck_classes[] = $this->prefix . $slidedeck['options']['navigation-type'];
            $slidedeck_classes[] = $this->prefix . $slidedeck['options']['autoplay-indicator'];
        }
        
        return $slidedeck_classes;
    }
    /**
     * Making the SlideDeck process as a vertical deck
     * 
     * @param boolean $process_as_vertical default boolean - false
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * 
     * @return boolean
     */
    function slidedeck_process_as_vertical( $process_as_vertical, $slidedeck ){
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            $process_as_vertical = true;
        }
        return $process_as_vertical;
    }
    
    
    /**
     * Removing the background-image inline style from the slide DD element
     * Background image is being used within the template on an internal element
     * Adding the accent-color class to the <a> tags if Twitter
     *
     * @param array $slides Array of Slides
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * 
     * @return array
     */
    function slidedeck_get_slides( $slides, $slidedeck ){
        if( $this->is_valid( $slidedeck['lens'] ) ){
            
            foreach( $slides as &$slide ){
                if( in_array( 'twitter', $slidedeck['source'] ) ){
                    $slide['content'] = preg_replace( '/\<a /', '<a class="accent-color" ', $slide['content'] );
                }
            }
        }
        return $slides;
    }
}
