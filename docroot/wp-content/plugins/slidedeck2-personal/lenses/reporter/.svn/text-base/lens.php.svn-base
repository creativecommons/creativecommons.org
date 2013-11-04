<?php
class SlideDeckLens_Reporter extends SlideDeckLens_Scaffold {
	var $dates = array();
	var $titles = array();
	
    var $options_model = array(
        'Appearance' => array(
            'accentColor' => array(
                'value' => "#fff"
            ),
            'hideSpines' => array(
                'type' => 'hidden',
                'value' => true
            ),
            'titleFont' => array(
                'value' => "linden-hill"
            ),
            'bodyFont' => array(
                'value' => "sans-serif"
            ),
			'transparent-background' => array(
				'name' => 'transparent-background',
	            'type' => 'radio',
	            'data' => 'boolean',
	            'label' => "Transparent Background?",
	            'value' => false,
	            'description' => "Should the background of the deck be transparent? (best with slide or flip transitions)",
	            'weight' => 60
			),
			'transparent-image-border' => array(
				'name' => 'transparent-image-border',
	            'type' => 'radio',
	            'data' => 'boolean',
	            'label' => "Transparent Image Border?",
	            'value' => false,
	            'description' => "Should the image border have a subtle transparency to it? (best used with a thicker border)",
	            'weight' => 80
			),
            'image-border-width' => array(
                'type' => 'text',
                'data' => "integer",
                'attr' => array(
                    'size' => 3,
                    'maxlength' => 3
                ),
                'value' => 7,
                'label' => "Image border width",
                'description' => "The width of the border around the image. Enter 0 for no border.",
                'suffix' => "px",
                'weight' => 70,
                'interface' => array(
                    'type' => 'slider',
                    'min' => 0,
                    'max' => 10,
                    'step' => 1
                )
            ),
        ),
		'Navigation' => array(
			'navigation-type' => array(
				'name' => 'navigation-style',
				'type' => 'select',
				'values' => array(
					'nav-dots' => 'Dots',
					'nav-dates' => 'Dates',
					'nav-titles' => 'Titles',
					'no-nav' => 'Turn Navigation Off'
				),
				'value' => 'nav-titles',
				'label' => 'Navigation Type',
				'weight' => 30
			),
            'nav-date-format' => array(
            	'name' => 'nav-date-format',
                'type' => 'select',
                'data' => 'string',
                'label' => "Navigation Date Format",
                'value' => "timeago",
                'description' => "Adjust how the date is shown in the navigation area (if used).",
                'weight' => 40
            ),

        )
    );

    function slidedeck_dimensions( &$width, &$height, &$outer_width, &$outer_height, &$slidedeck ) {
    	global $SlideDeckPlugin;
    	if( $this->is_valid( $slidedeck['lens'] ) ) {
			switch( $slidedeck['options']['navigation-type'] ) {
				case 'nav-dots':
					$height = $outer_height - 30;
				break;
				case 'nav-dates':
					$height = $outer_height - 40;
				case 'nav-titles':
					$height = $outer_height - 47;
				break;
			}
		}
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
        	$slidedeck_classes[] = $this->prefix . $slidedeck['options']['navigation-type'];
        	if( $slidedeck['options']['transparent-background'] == 1 )
	        	$slidedeck_classes[] = $this->prefix . 'transparent-background';

        	if( $slidedeck['options']['date-format'] != 'none' )
	        	$slidedeck_classes[] = $this->prefix . 'date-is-shown';
        	
        	if( $slidedeck['options']['transparent-image-border'] == 1 )
	        	$slidedeck_classes[] = $this->prefix . 'transparent-image-border';
        	
        	// Hide the subtle border on the image tag that isn't adjustable
        	if( $slidedeck['options']['image-border-width'] < 1 )
	        	$slidedeck_classes[] = $this->prefix . 'no-image-border';
        }
        return $slidedeck_classes;
    }
	
	/**
	 * Filters the options model.
	 * 
	 * The purpose of this particular filter is to 
	 * set the fancy options for the dropdown.
	 */
	function slidedeck_options_model( $options_model, $slidedeck ) {
		if( $this->is_valid( $slidedeck['lens'] ) ) {
	        $options_model['Navigation']['nav-date-format']['values'] = array(
	            'timeago' => "2 Days Ago",
	            'human-readable' => date( "F j, Y" ),
	            'human-readable-abbreviated' => date( "M j, Y" ),
	            'raw' => date( "m/d/Y" ),
	            'raw-eu' => date( "Y/m/d" )
	        );
		}
		
		return $options_model;
	}
	
	/**
     * Append the nav-button div to the slide content.
	 * It needs to be done this way as the date is not always output otherwise.
	 * This way we have a dedicated sopt to fetch the nav-buttons from.
     * 
     * @param array $nodes $nodes Various information nodes available to use in the template file
     * 
     * @return array
     */
	function slidedeck_slide_nodes( $nodes, $slidedeck ){
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			$nodes['image_border_style'] = '';
			$nodes['image_style'] = '';
			
			if( $slidedeck['options']['image-border-width'] > 0 ) {
				$border_width = max( $slidedeck['options']['image-border-width'], 0 );
				
				$border_color = "rgb(67,67,67)";
				$shadow_border_color = "rgb(0,0,0)";
				if( $slidedeck['options']['lensVariations'] == 'light' ){
					$border_color = "rgb(236,236,236)";
					$shadow_border_color = "rgb(255,255,255)";
				}
	
				if( $slidedeck['options']['transparent-image-border'] == '1' ){
					// Use Box shadow
					$nodes['image_border_style'] = "box-shadow: inset 0 0 0 {$border_width}px {$border_color};";
				}else{
					$nodes['image_style'] = "border: solid {$border_width}px {$border_color};";
				}
			}

			/**
			 * Taken from the default implementation
			 */
	        if( isset( $nodes['unfiltered_created_at'] ) && !empty( $nodes['unfiltered_created_at'] ) ) {
	            $nodes['nav_created_at'] = is_numeric( $nodes['unfiltered_created_at'] ) ? $nodes['unfiltered_created_at'] : strtotime( $nodes['unfiltered_created_at'] );
	            $date_format = isset( $slidedeck['options']['nav-date-format'] ) ? $slidedeck['options']['nav-date-format'] : "none";
	            switch( $date_format ) {
	                case "timeago":
	                    $nodes['nav_created_at'] = human_time_diff( $nodes['unfiltered_created_at'], current_time( 'timestamp', 1 ) ) . " ago";
	                break;
	                case "human-readable":
	                    $nodes['nav_created_at'] = date( "F j, Y", $nodes['unfiltered_created_at'] );
	                break;
	                case "human-readable-abbreviated":
	                    $nodes['nav_created_at'] = date( "M j, Y", $nodes['unfiltered_created_at'] );
	                break;
	                case "raw":
	                    $nodes['nav_created_at'] = date( "m/d/Y", $nodes['unfiltered_created_at'] );
	                break;
	                case "raw-eu":
					default:
	                    $nodes['nav_created_at'] = date( "Y/m/d", $nodes['unfiltered_created_at'] );
	                break;
	            }
	        }			

			$html = '<span class="nav-button sd2-custom-title-font">';
			$html .= '<span class="sd2-date">';
			$html .= $nodes['nav_created_at'];
			$html .= '</span>';
			$html .= '<span class="sd2-nav-title">';
			$html .= $nodes['title'];
			$html .= '</span>';
			$html .= '</span>';
			
			$nodes['nav_button'] = $html;
		}
		return $nodes;
	}
}
