<?php

class SlideDeckLens_Twitter extends SlideDeckLens_Scaffold {
	
	var $options_model = array(
        'Content' => array(
            'hide-description' => array(
				'label' => 'Hide User Description',
				'type' => 'radio',
                'data' => "boolean",
                'value' => false,
                'description' => "Show or hide the Twitter user's description",
                'weight' => 60
            ),
            'show-tools' => array(
				'label' => 'Show Tweet Controls',
				'type' => 'radio',
                'data' => "boolean",
                'value' => true,
                'description' => "Show or hide the Twitter-specific control buttons, like @reply, RT and others",
                'weight' => 70
            ),
            'hideSpines' => array(
                'type' => 'hidden',
                'value' => true
            )
        )
    );
	
	function slidedeck_render_slidedeck_before($html, $slidedeck){
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			$html .= '<div class="sd-node-twitter-header">';
                $html .= '<div class="sd-node-twitter-user-name">';
                    if( $slidedeck['options']['linkAuthorName'] ) {
                        $html .= '<a href="#" class="sd-node-twitter-header-link" target="' . $slidedeck['options']['linkTarget'] . '">';
                    } else {
                        $html .= '<span class="sd-node-twitter-header-link">';
                    }
                    $html .= ( isset( $slidedeck['author_name'] ) ? $slidedeck['author_name'] : "" );
                    if( $slidedeck['options']['linkAuthorName'] ) {
                        $html .= '</a>';
                    } else {
                        $html .= '</span>';
                    }
                $html .= '</div>';
                $html .= '<div class="sd-node-twitter-user-description">User Experience Web Design Agency. We tweet about design strategy that improves website performance.</div>';
                $html .= '<div class="sd-node-twitter-follow-button"></div>';
            $html .= '</div>';
		}
		
		return $html;
	}
	
	function slidedeck_render_slidedeck_after($html, $slidedeck){
		if( $this->is_valid( $slidedeck['lens'] ) ) {
			$html .= '<div class="sd-node-twitter-tools">';
    			$html .= '<ul class="sd-node-twitter-tools-list">';
	           		$html .= '<li><a class="sd-node-twitter-retweet" href="https://twitter.com/intent/retweet?tweet_id=" title="' . __( "Retweet", $this->namespace ) . '">Retweet</a></li>';
        			$html .= '<li><a class="sd-node-tool-reply" href="#" title="' . __( "Reply", $this->namespace ) . '">Reply</a></li>';
        			$html .= '<li><a class="sd-node-tool-favorite" href="" title="' . __( "Favorite", $this->namespace ) . '">Favorite</a></li>';
        			$html .= '<li><a class="sd-node-open-profile" href="https://twitter.com/dtelepathy" target="_blank" title="' . __( "Open Profile", $this->namespace ) . '">Open Profile</a></li>';
    			$html .= '</ul>';
    			$html .= '<div class="sd-node-twitter-tools-reply">';
	           		$html .= '<div class="sd-node-twitter-reply-header">Reply to @<span class="sd-node-twitter-header-handle">dtelepathy</span>:</div>';
                    $html .= '<form class="sd-node-reply-form">';
                        $html .= '<div class="sd-node-reply-to-container">';
                            $html .= '<textarea class="sd-node-reply-area"></textarea>';
                            $html .= '<div class="faux-reply-to-handle">@dtelepathy</div>';
                        $html .= '</div>';
                        $html .= '<div class="sd-node-reply-count"><span class="sd-node-reply-count-number">0</span>/<span class="sd-node-reply-count-max">140</span></div>';
                        $html .= '<div class="sd-node-reply-actions">';
                            $html .= '<a href="#" class="sd-node-twitter-tools-tweet-text">Tweet</a>';
                            $html .= '<a href="#" class="sd-node-cancel-tweet">cancel</a>';
                        $html .= '</div>';
                    $html .= '</form>';
                $html .= '</div>';
            $html .= '</div>';
		}

		return $html;
	}
	
	function slidedeck_dimensions( &$width, &$height, &$outer_width, &$outer_height, &$slidedeck ) {
	    global $SlideDeckPlugin;
        
		if( $this->is_valid( $slidedeck['lens'] ) ) {
            $closest_size = $SlideDeckPlugin->SlideDeck->get_closest_size( $slidedeck );
            
			if( $slidedeck['options']['hide-description'] != true ) {
                if( $closest_size == 'small' ) {
                    $width = $width;
                    $height = $height - 35;
                } else if( $closest_size == 'medium' ) {
                    $width = $width - 167;
                } else {
                    $width = $width*.7;
                }
			}
		}
	}
	
	
    function slidedeck_process_as_vertical($process_as_vertical, $slidedeck ){
        global $SlideDeckPlugin;
        
    	if( $this->is_valid( $slidedeck['lens'] ) ) {
            $closest_size = $SlideDeckPlugin->SlideDeck->get_closest_size( $slidedeck );
            
	    	if( in_array( $closest_size, array( 'small', 'medium' ) ) ){	
	    		$process_as_vertical = true;
    		}
    	}

    	return $process_as_vertical;
    }
}
