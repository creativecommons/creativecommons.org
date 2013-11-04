<?php
class SlideDeckLens_Fashion extends SlideDeckLens_Scaffold {
    var $options_model = array(
        'Appearance' => array(
            'accentColor' => array(
                'value' => "#f9e836"
            ),
            'titleFont' => array(
                'value' => "lato"
            ),
			'show-title-rule' => array(
				'suffix' => 'Shows/Hides the double bar rule behind the title',
				'label' => 'Show Title Rule',
				'type' => 'radio',
                'data' => "boolean",
                'value' => true,
                'weight' => 70
			),
			'show-shadow' => array(
				'suffix' => 'Shows/Hides white drop shadow around content',
				'label' => 'Show Box Shadow',
				'type' => 'radio',
                'data' => "boolean",
                'value' => true,
                'weight' => 80
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
					'number-nav' => 'Numbers',
					'dot-nav' => 'Dots'
				),
				'value' => 'numbers',
				'label' => 'Navigation Type',
				'description' => "Show dots or numbers on the navigation bar",
				'weight' => 20
			),
        )
    );
	
	/**
     * Modify Slide title to wrap in spans for stlying
     * 
     * @param array $nodes $nodes Various information nodes available to use in the template file
     * 
     * @return array
     */
	function slidedeck_slide_nodes( $nodes, $slidedeck ){
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			$temp_title = $nodes['title'];
			$title_parts = explode( " ", $temp_title );
			$new_title = "";
            $count = 1;
			foreach( $title_parts as $title_part ){
				if( $count == 1 ){
    				$new_title .= '<span class="first">'. $title_part .'</span> ';
				}else{
    				$new_title .= '<span>'. $title_part .'</span> ';
				}
                $count++;
			}
			$nodes['title'] = $new_title;
            
            if( in_array('twitter', $slidedeck['source'] ) ){
                        
                $url_regex = '/((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)|(\\\\))+[\w\d:#@%\/\;$()~_?\+-=\\\.&]*)/';
               
                /**
                 * This preg split takes a tweet (URLs, words, hashtags, usernames) and breaks it up wherever
                 * there is already a html tag (the input has <a> tags wrapped around the aforementioned) and breaks it up.
                 * This gives us an array with strings, and links broken up into elements.
                 * 
                 * This allow us to break each word and "linkified" words in their own spans.
                 */
                $split_html = preg_split( '/<\/?\w+((\s+\w+(\s*=\s*(?:\".*?\\\"|.*?|[^">\s]+))?)+\s*|\s*)\/?>/s', $nodes['excerpt'] );
                
                // Reset the excerpt node for appending to.
                $nodes['excerpt'] = '';
                foreach( $split_html as $segment ){
                    if( preg_match( $url_regex, $segment ) ){
                        // If the current segment looks like a URL, wrap and append it.
                        $nodes['excerpt'] .= '<span><a class="accent-color" href="'. $segment .'" target="_blank">'. $segment .'</a></span>';
                    }elseif( preg_match( '/(\@([a-zA-Z0-9_]+))|(\#([a-zA-Z0-9_]+))/', $segment ) ){
                        // If the current segment looks like a mention or hashtag, wrap and append it. 
                        $nodes['excerpt'] .= '<span><a class="accent-color" href="http://twitter.com/search?q='. $segment .'" target="_blank">'. $segment .'</a></span>';
                    }else{
                        /**
                         * If the current segment is neither, then we can reasonably assume it's a string of words.
                         * Here we'll run the existing split and wrap code.
                         */
                        if( !empty( $segment ) ){
                            $segment = trim( $segment );
                			$temp_excerpt = strip_tags( $segment );
                			$excerpt_parts = explode( " ", $temp_excerpt );
                			$new_excerpt = "";
                			$count = 1;
                			foreach( $excerpt_parts as $excerpt_part ){
                    			if ( $count == 1 ) {
                        			$new_excerpt .= '<span class="first">'. $excerpt_part .'</span> ';
                    			} else {
                        		    $new_excerpt .= '<span>'. $excerpt_part .'</span> ';	
                    			}
                			}
                            $new_excerpt = preg_replace($url_regex, '<a href="$1" target="_blank">'. "$1" .'</a>', $new_excerpt);
                            $new_excerpt = preg_replace( array(
                                '/\@([a-zA-Z0-9_]+)/',
                                '/\#([a-zA-Z0-9_]+)/'
                            ), array(
                                '<a href="http://twitter.com/$1" target="_blank">@$1</a>',
                                '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>'
                            ), $new_excerpt );
                            
                			$nodes['excerpt'] .= $new_excerpt;
                        } // if( !empty( $segment ) )
                    } // else (is a string)
                } // foreach( $split_html as $segment )
            } // if the source is twitter
		} // if is a valid lens
		
		return $nodes;
	}

	function slidedeck_dimensions( &$width, &$height, &$outer_width, &$outer_height, &$slidedeck ) {
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			// Add 44px for the bottom navigation on the lens.
			$height = $height - 44;
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
        }
        
        return $slidedeck_classes;
    }
}
