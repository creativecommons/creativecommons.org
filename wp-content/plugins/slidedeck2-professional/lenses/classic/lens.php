<?php 
class SlideDeckLens_Classic extends SlideDeckLens_Scaffold {
    var $options_model = array(
        'Appearance' => array(
        	'accentColor' => array(
                'value' => "#ffffff"
            ),
            'hideSpines' => array(
                'value' => false
            )
        ),
        'Navigation' => array(
            'inactiveSpineColor' => array(
                'type' => "text",
                'data' => "string",
                'attr' => array(
                    'class' => "color-picker",
                    'size' => 7
                ),
                'value' => "#ffffff",
                'label' => "Inactive Spine Color",
                'description' => "Pick a color for the inactive spine(vertical bar)",
                'weight' => -10
            ),
            'activeSpineColor' => array(
                'type' => "text",
                'data' => "string",
                'attr' => array(
                    'class' => "color-picker",
                    'size' => 7
                ),
                'value' => "#000000",
                'label' => "Active Spine Color",
                'description' => "Pick a color for the active spine(vertical bar)",
                'weight' => -9
            ),
            'inactiveSpineTitleColor' => array(
                'type' => "text",
                'data' => "string",
                'attr' => array(
                    'class' => "color-picker",
                    'size' => 7
                ),
                'value' => "#000000",
                'label' => "Inactive Spine Title Color",
                'description' => "Pick a color for the titles for the inactive spine(vertical bar)",
                'weight' => -8
            ),
            'activeSpineTitleColor' => array(
                'type' => "text",
                'data' => "string",
                'attr' => array(
                    'class' => "color-picker",
                    'size' => 7
                ),
                'value' => "#ffffff",
                'label' => "Active Spine Title Color",
                'description' => "Pick a color for the titles for the active spine(vertical bar)",
                'weight' => -7
            ),
            'spineTitleLength' => array(
                'type' => 'text',
                'data' => "integer",
                'attr' => array(
                    'size' => 3,
                    'maxlength' => 3
                ),
                'value' => 30,
                'label' => "Spine title Length",
                'description' => "Spine title length displayed",
                'suffix' => "chars",
                'interface' => array(
                    'type' => 'slider',
                    'min' => 10,
                    'max' => 100,
                    'step' => 5
                ),
                'weight' => -6
            ),
            'spineWidth' => array(
                'type' => 'text',
                'data' => "integer",
                'attr' => array(
                    'size' => 3,
                    'maxlength' => 3
                ),
                'value' => 35,
                'label' => "Spine width",
                'description' => "Spine width",
                'suffix' => "px",
                'interface' => array(
                    'type' => 'slider',
                    'min' => 20,
                    'max' => 65,
                    'step' => 1
                ),
                'weight' => -5
            ),
            'show-spine-titles' => array(
                'type' => 'radio',
                'data' => 'boolean',
                'label' => "Show Spine Titles",
                'value' => true,
                'description' => "Show or hide the spine titles when they are available.",
                'weight' => -4
            ),
            'indexType' => array(
                'type' => 'select',
                'weight' => -2
            )
		),
        'Playback' => array(
            'slideTransition' => array(
                'type' => 'hidden',
                'value' => 'slide'
            )
        )
    );
    
    function __construct(){
        parent::__construct();
        add_filter( "{$this->namespace}_get_slides", array( &$this, "slidedeck_get_slides" ), 11, 2 );
        add_filter( "{$this->namespace}_horizontal_spine_title", array( &$this, "slidedeck_horizontal_spine_title" ), 11, 3 );
        add_filter( "{$this->namespace}_footer_styles", array( &$this, "slidedeck_lens_styles" ), 21, 2 );
        add_filter( "{$this->namespace}_horizontal_spine_classes", array( &$this, "slidedeck_spine_classes" ), 21, 3 );
        add_filter( "{$this->namespace}_horizontal_spine_styles", array( &$this, "slidedeck_spine_styles" ), 21, 3 );
        
        add_action( "{$this->namespace}_custom_slide_editor_form", array( &$this, "slidedeck_custom_slide_editor_form" ), 15, 2 );
        add_action( "{$this->namespace}_update_slide", array( &$this, 'slidedeck_update_slide' ), 10, 2 );
    }
    
    /**
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
	
	/**
     * Wrapping spine titles in an additional span for styling
     * 
     * @param string $title Title of the spine
     * @param array $slidedeck The SlideDeck object being rendered
     * @param array $slide The Slide array
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * @uses SlideDeckPlugin::slidedeck_is_dynamic()
     * 
     * @return string
     */
    function slidedeck_horizontal_spine_title( $title, $slidedeck, $slide ){
        global $SlideDeckPlugin, $SlideDeckSlide;
        
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            $title_text = $title;
            
            if( !$SlideDeckPlugin->slidedeck_is_dynamic( $slidedeck ) ){
                $custom_spine_title = get_post_meta( $slide['id'], '_spine_title', true );
                if( $custom_spine_title ){
                    $title_text = $custom_spine_title;
                } else {
                    $title_text = '';
                }
            }
        	$title = slidedeck2_stip_tags_and_truncate_text( $title_text, $slidedeck['options']['spineTitleLength'] );
            $title = "<span class=\"sd2-spine-title\">{$title}</span>";
        }
        return $title;
    }
    
    /**
     * Additional Lens styles for options as set in admin
     * 
     * @param string $styles inline styles
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * @uses SlideDeck::get_unique_id()
     * 
     * @return string
     */
    function slidedeck_lens_styles( $styles, $slidedeck ){
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            global $SlideDeckPlugin;
            $slidedeck_unique_id = $SlideDeckPlugin->SlideDeck->get_unique_id( $slidedeck['id'] );
            
            // Spine Background Colors
            $styles .= '#' . $slidedeck_unique_id . '-frame dt.sd2-spine-background-color{background-color:' . $slidedeck['options']['inactiveSpineColor'] . '}';
            $styles .= '#' . $slidedeck_unique_id . '-frame dt.sd2-spine-background-color:hover{background-color:' . $slidedeck['options']['activeSpineColor'] . '}';
            $styles .= '#' . $slidedeck_unique_id . '-frame dt.sd2-spine-background-color.active{background-color:' . $slidedeck['options']['activeSpineColor'] . '}';
            
            // Spine Title Colors
            $styles .= '#' . $slidedeck_unique_id . '-frame dt.sd2-spine-title-color{color:' . $slidedeck['options']['inactiveSpineTitleColor'] . '}';
            $styles .= '#' . $slidedeck_unique_id . '-frame dt.sd2-spine-title-color:hover{color:' . $slidedeck['options']['activeSpineTitleColor'] . '}';
            $styles .= '#' . $slidedeck_unique_id . '-frame dt.sd2-spine-title-color.active{color:' . $slidedeck['options']['activeSpineTitleColor'] . '}';
            
            // Spine Width content Adjustments
            $styles .= '#' . $slidedeck_unique_id . '-frame.lens-classic dl.slidedeck > dt span.sd2-spine-title{line-height:' . $slidedeck['options']['spineWidth'] . 'px}';
            $styles .= '#' . $slidedeck_unique_id . '-frame dd .sd2-content-wrapper,#' . $slidedeck_unique_id . '-frame dd .video-wrapper,#' . $slidedeck_unique_id . '-frame dd.slide-type-image .slide-content,#' . $slidedeck_unique_id . '-frame.lens-classic dl.slidedeck > dd .sd2-slide-background{left:' . $slidedeck['options']['spineWidth'] . 'px}';
            
            // Reduce spine index font-size when Roman numerals selected - fits better
            if( ($slidedeck['options']['indexType'] == 'uc-roman') || ($slidedeck['options']['indexType'] == 'lc-roman') ){
                $styles .= '#' . $slidedeck_unique_id . '-frame.lens-classic dl.slidedeck > dt.spine .index{font-size:1em;}';
                $styles .= '#' . $slidedeck_unique_id . '-frame.lens-classic.sd2-small dl.slidedeck > dt.spine .index{font-size:0.8em;}';
            }
        }    
        return $styles;
        
    }
    
    /**
     * Appending classes to the lens dt element
     * 
     * @param array $classes Array of classes for the dt element
     * @param array $slidedeck The SlideDeck object being rendered
     * @param array $slide The Slide object
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * @return array
     */
    function slidedeck_spine_classes( $classes, $slidedeck, $slide ){
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            $classes[] = "sd2-spine-title-color";
            $classes[] = "sd2-spine-background-color";
            $classes[] = "sd2-custom-title-font";
        }
        
        return $classes;
    }

    /**
     * Appending inline styles to the lens dt element pre-load - used to set height(width) of dt element
     * 
     * @param string $styles styles for the dt element
     * @param array $slidedeck The SlideDeck object being rendered
     * @param array $slide The Slide object
     * 
     * @uses SlideDeckLens_Scaffold::is_valid()
     * @return string
     */
    function slidedeck_spine_styles( $styles, $slidedeck, $slide ){
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            $styles .= 'height:' . $slidedeck['options']['spineWidth'] . 'px';
        }
        
        return $styles;
    }
    
    /**
     * Hook into slidedeck_custom_slide_editor_form action
     * 
     * Output the editing form for the slide editor modal for this slide type
     * 
     * @param object $slide The Slide object
     * @param array $slidedeck The SlideDeck object
     */
    function slidedeck_custom_slide_editor_form( $slide, $slidedeck ) {
        $form_html = '';
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            if( empty( $slide->meta['_spine_title'] ) || !isset( $slide->meta['_spine_title'] ) ){
                $slide->meta['_spine_title'] = '';
            }
            $form_html .= "<ul class=\"slide-content-fields\">";
            $form_html .= "<li class=\"spine-title\">";
            $form_html .="<label>" . __( "Spine Title", $this->namespace ) . "<br />";
            $form_html .= "<input type=\"text\" name=\"spine_title\" value=\"" . $slide->meta['_spine_title'] ."\" />";
            $form_html .= "</label>";
            $form_html .= "</li>";
        }
        echo $form_html;
    }
    
    /**
     * Hook into slidedeck_update_slide action
     * 
     * Save spine title data when the edit form is submitted.
     * 
     * @param object $slide Slide object
     * @param array $data Santized $_POST data
     * 
     * @uses SlideDeck::get()
     * @uses SlideDeckPlugin::slidedeck_is_dynamic()
     * @uses update_post_meta()
     */
    function slidedeck_update_slide( $slide, $data ) {
        global $SlideDeckPlugin;
        $slidedeck = $SlideDeckPlugin->SlideDeck->get( $slide->post_parent );
    
        if( !$SlideDeckPlugin->slidedeck_is_dynamic( $slidedeck ) ){
            if( isset( $data['spine_title'] ) ) {
                $spine_title = strip_tags( $data['spine_title'] );
                update_post_meta( $slide->ID, "_spine_title", $spine_title );
            }
        }
    }
	
}
