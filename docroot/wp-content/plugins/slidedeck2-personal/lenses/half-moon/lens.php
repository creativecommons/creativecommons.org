<?php
class SlideDeckLens_HalfMoon extends SlideDeckLens_Scaffold {
    var $options_model = array(
		'Setup' => array(
			'navigation-position' => array(
				'name' => 'navigation-position',
				'type' => 'select',
				'values' => array(
					'nav-bottom' => 'Bottom',
					'nav-top' => 'Top'
				),
				'value' => 'nav-bottom',
				'label' => 'Navigation Position'
			)
		),
		'Appearance' => array(
            'hideSpines' => array(
                'type' => 'hidden',
                'value' => true
            )
        ),
		'Navigation' => array(
			'navigation-style' => array(
				'name' => 'navigation-style',
				'type' => 'select',
				'values' => array(
					'nav-inside' => 'Inside',
					'nav-outside' => 'Outside'
				),
				'value' => 'nav-inside',
				'label' => 'Navigation Style',
				'weight' => 30
			)
        ),
        'Content' => array(
            'date-format' => array(
                'value' => "human-readable-abbreviated"
            )
        )
    );
    
    function slidedeck_options_model( $options_model, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            // Check to see if the deck type even supports date-format
            if( $options_model['Content']['date-format'] != 'hidden' ) {
                $options_model['Content']['date-format']['value'] = "human-readable-abbreviated";
                $options_model['Content']['date-format']['values'] = array(
                    'none' => "Do not show",
                    'human-readable-abbreviated' => date( "M j" )
                );
            }
        }
        
        return $options_model;
    }
    
   	/**
     * Add appropriate classes for this Lens to the SlideDeck frame
     * 
     * @param array $slidedeck_classes Classes to be applied
     * @param array $slidedeck The SlideDeck object being rendered
     * 
     * @return array
     */
    function slidedeck_frame_classes( $slidedeck_classes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) ) {
        	$slidedeck_classes[] = $this->prefix . $slidedeck['options']['navigation-position'];
        	$slidedeck_classes[] = $this->prefix . $slidedeck['options']['navigation-style'];
        }
        return $slidedeck_classes;
    }
    
    function slidedeck_dimensions($width, $height, $outer_width, $outer_height, $slidedeck){
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			$width = $outer_width - 2;
			$height = $outer_height - 2;
			
			if( $slidedeck['options']['navigation-style'] == 'nav-outside' ) {
				$height = $outer_height - 35;
			}
		}
	}

	/**
	 * Append options to bottom of the setup list
	 * 
	 * @param array $slidedeck
	 */
	function slidedeck_setup_options_bottom( $slidedeck ) {
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			echo '<li>' . slidedeck2_html_input( "options[navigation-position]", $slidedeck['options']['navigation-position'], $this->options_model['Setup']['navigation-position'], false ) . '</li>';
		}
	}
    
    /**
     * Hook into slidedeck_slide_nodes filter
     * 
     * @param array $nodes Array of slide nodes
     * @param array $slidedeck SlideDeck object
     * 
     * @return array
     */
    function slidedeck_slide_nodes( $nodes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['lens'] ) ) {
            // Revert created_at back to a timestamp so it can be output special in the template
            $nodes['created_at'] = is_numeric( $nodes['created_at'] ) ? $nodes['created_at'] : strtotime( $nodes['created_at'] );
        }
        
        return $nodes;
    }
}
