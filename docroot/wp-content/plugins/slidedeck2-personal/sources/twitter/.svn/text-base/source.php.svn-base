<?php
/**
 * SlideDeck Social Deck Class
 * 
 * More information on this project:
 * http://www.slidedeck.com/
 * 
 * Full Usage Documentation: http://www.slidedeck.com/usage-documentation 
 * 
 * @package SlideDeck
 * @subpackage SlideDeck 2 Pro for WordPress
 * @author dtelepathy
 */

/*
Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This file is part of SlideDeck.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/

class SlideDeckSource_Twitter extends SlideDeck {
    var $label = "Twitter Timeline/Search";
    var $name = "twitter";
    var $default_lens = "twitter";
    var $taxonomies = array( 'social' );
    
    var $twitter_fetch_user_cache = 2880;
    
    var $options_model = array(
        'Setup' => array(
            'twitter_search_or_user' => array(
                'data' => "string",
                'value' => "user",
                'type' => 'radio', 
                'attr' => array( 
                	'class' => 'fancy' 
				), 
				'label' => "Tweets from", 
				'values' => array(
                    'user' => "Username",
                    'search' => "Search Term"
                )
            ),
            'twitter_q' => array(
            	'data' => "string",
            	'value' => "#wordpress",
            	'type' => "text",
				'label' => "Search Term",
				'attr' => array(
					'size' => 20,
					'maxlength' => 255
				),
				'required' => true
			),
            'twitter_username' => array(
                'data' => "string",
                'value' => "dtelepathy",
                'type' => "text",
                'label' => "Twitter Username",
                'attr' => array(
                	'size' => 20,
                	'maxlength' => 255
				),
				'required' => true
            ),
            'twitter_scrape_images' => array(
                'value' => true,
                'type' => 'checkbox',
                'label' => "Image Scraping?",
                'attr' => array(
                	'class' => 'fancy'
				)
            ),
            'useGeolocationImage' => array(
                'type' => 'radio',
                'data' => "boolean",
                'value' => false,
                'label' => "Get Geolocation Images",
                'attr' => array(
                	'class' => "fancy"
				),
                'description' => "If an image cannot be found, use the user's location map as a background image"
            ),
            'size' => array(
            	'value' => 'small'
			)
        )
    );
    
    
    function add_hooks() {
        add_filter( "{$this->namespace}_classes", array( &$this, 'slidedeck_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_frame_classes", array( &$this, 'slidedeck_frame_classes' ), 10, 2 );
        add_filter( "{$this->namespace}_iframe_scripts", array( &$this, 'slidedeck_iframe_scripts' ), 10, 2 );
        
        add_action( "{$this->namespace}_form_content_source", array( &$this, "slidedeck_form_content_source" ), 10, 2 );
    }
    
    /**
     * Twitter Source
     */
    function slidedeck_form_content_source( $slidedeck, $source ) {
        // Fail silently if the SlideDeck is not this type or source
        if( !$this->is_valid( $source ) ) {
            return false;
        }
        
        $default_options = array();
        foreach( $this->options_model['Setup'] as $key => $props ) {
            $default_options[$key] = $props['value'];
        }
        $slidedeck['options'] = array_merge( $default_options, $slidedeck['options'] );
        
        if( !isset( $slidedeck['options']['twitter_q'] ) ){
            $slidedeck['options']['twitter_q'] = 'What\'s Happening?';
        }
        
        include( dirname( __FILE__ ) . '/views/show.php' );
    }
    
    /**
     * Get Twitter User Description
     * 
     * Fetches the details for a specific user.
     * This is neeeded as the Twitter search does not return a
     * full profile for users in the search results. This is slow! Caching is a must.
     * 
     * @param string $twitter_username
     * @param object $slidedeck
     * 
     * @return string The Twitter user's profile (description) wrapped in a <span>
     */
    function get_twitter_user_description( $twitter_username, $slidedeck ) {
        // Attempt to get the user
        $twitter_user = $this->get_twitter_user( $twitter_username, $slidedeck );
        
        if( !empty( $twitter_user ) ) {
            if(!empty($twitter_user->description)){
                return '<span class="sd-node-twitter-span-desc">'.$twitter_user->description.'</span>';
            }
        }
        
        return '<span class="sd-node-twitter-span-desc not-found">&hellip;</span>';
    }
        
    /**
     * Get Twitter User Background Image
     * 
     * Fetches the background iamge of a user
     * 
     * @param string $twitter_username
     * @param object $slidedeck
     * 
     * @return array
     */
    function get_twitter_user_background( $twitter_username, $slidedeck ) {
        $background_image = array();
        
        // Attempt to get the user
        $twitter_user = $this->get_twitter_user( $twitter_username, $slidedeck );
        if( !empty( $twitter_user ) ) {
            if( isset( $twitter_user->profile_background_image_url ) && !empty( $twitter_user->profile_background_image_url ) ){
                $background_image = array(
                    'background_image_url' => $twitter_user->profile_background_image_url,
                    'tile' => $twitter_user->profile_background_tile
                );
            }
        }
        
        return $background_image;
    }
        
    /**
     * Get Twitter User
     * 
     * Fetches the user
     * 
     * @param string $twitter_username
     * @param object $slidedeck
     * 
     * @return object
     */
    function get_twitter_user( $twitter_username, $slidedeck ) {
        $args = array(
            'sslverify' => false
        );
        $feed_url = 'https://api.twitter.com/1/users/show.json?screen_name='.$twitter_username.'&include_entities=true';
        
        // Create a cache key
        $cache_key = 'twitter' . $twitter_username . 'twitter_user';
        
        $response = slidedeck2_cache_read( $cache_key );
        
        if( !$response ) {
            $response = wp_remote_get( $feed_url, $args );
            
            if( !is_wp_error( $response ) ) {
                // Write the cache
                slidedeck2_cache_write( $cache_key, $response, $this->twitter_fetch_user_cache );
            }
        }
        
        if( !is_wp_error( $response ) ) {
            $twitter_user = json_decode( $response['body'] );
            
            if( !empty( $twitter_user ) ) {
                if( empty( $twitter_user->error ) ) {
                    return $twitter_user;
                }
            }
        }
        
        return (object) array();
    }
        
    /**
     * Linkify Twitter Text
     * 
     * @param string s Tweet
     * 
     * @return string a Tweet with the links, mentions and hashtags wrapped in <a> tags 
     */
    function linkify_twitter_text($tweet){
        $url_regex = '/((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)|(\\\\))+[\w\d:#@%\/\;$()~_?\+-=\\\.&]*)/';
        $tweet = preg_replace($url_regex, '<a href="$1" target="_blank">'. "$1" .'</a>', $tweet);
        $tweet = preg_replace( array(
            '/\@([a-zA-Z0-9_]+)/',    # Twitter Usernames
            '/\#([a-zA-Z0-9_]+)/'    # Hash Tags
        ), array(
            '<a href="http://twitter.com/$1" target="_blank">@$1</a>',
            '<a href="http://twitter.com/search?q=%23$1" target="_blank">#$1</a>'
        ), $tweet );
        
        return $tweet;
    }
        
    /**
     * Load slides for Twitter sourced SlideDecks
     * 
     * @uses fetch_feed()
     * 
     * @return array
     */
    function get_slides_nodes( $slidedeck ) {
        $slidedeck_id = $slidedeck['id'];
        $args = array(
            'sslverify' => false
        );
        
        switch( $slidedeck['options']['twitter_search_or_user'] ){
            case 'search':
                $feed_url = 'http://search.twitter.com/search.json?q=' . urlencode( $slidedeck['options']['twitter_q'] ) . '&include_entities=true&result_type=mixed&lang=en&rpp=' . $slidedeck['options']['total_slides'];
            break;
            case 'user':
                $feed_url = 'https://api.twitter.com/1/statuses/user_timeline.json?screen_name=' . $slidedeck['options']['twitter_username'] . '&include_entities=true&include_rts=true&contributor_details=true&count=' . $slidedeck['options']['total_slides'];
            break;
        }
        
        // Set a reference to the current SlideDeck for reference in actions
        $this->__transient_slidedeck &= $slidedeck;

        // Create a cache key
        $cache_key = $slidedeck_id . $feed_url . $slidedeck['options']['twitter_scrape_images'] . $slidedeck['options']['useGeolocationImage'] . $slidedeck['options']['cache_duration'] . $this->name;
        
        // Attempt to read the cache
        $twitter_posts = slidedeck2_cache_read( $cache_key );
        
        // If cache doesn't exist
        if( !$twitter_posts ){
            $twitter_posts = array();
            
            $response = wp_remote_get( $feed_url, $args );
            if( !is_wp_error( $response ) ) {
                $response_json = json_decode( $response['body'] );
                
                
                switch( $slidedeck['options']['twitter_search_or_user'] ){
                    case 'search':
                        if( !empty( $response_json->results ) ){
                            foreach( $response_json->results as $index => $result ){
                                $background = $this->get_twitter_user_background( $result->from_user, $slidedeck );
                                
                                $tile = false;
                                if( isset( $background['tile'] ) && !empty( $background['tile'] ) )
                                    $tile = $background['tile'];

                                $twitter_posts[$index] = array(
                                    'id' => $result->id_str,
                                    'title' => $result->text,
                                    'permalink' => 'http://twitter.com/' . $result->from_user . '/status/' . $result->id_str,
                                    'image' => $this->try_fetching_tweet_image( $result, $slidedeck, $result ),
                                    'image_tile' => $tile,
                                    'author_username' => $result->from_user,
                                    'author_name' => $result->from_user_name,
                                    'author_url' => 'http://twitter.com/' . $result->from_user,
                                    'author_email' => false,
                                    'author_avatar' => $result->profile_image_url,
                                    'content' => $this->linkify_twitter_text($result->text),
                                    'comment_count' => false,
                                    'plusone_count' => false,
                                    'reshare_count' => false,
                                    'excerpt' => $this->linkify_twitter_text($result->text),
                                    'created_at' => strtotime( $result->created_at ),
                                    'local_created_at' => $result->created_at,
                                    'description' => $this->get_twitter_user_description($result->from_user, $slidedeck),
                                );
                            }
                        }
                    break;
                    case 'user':
                        
                        foreach( $response_json as $index => $result ){
                            // If the result is valid
                            if( is_object( $result ) ) {
                                if( isset( $result->retweeted_status ) ){
                                    // Retweet
                                    $twitter_posts[ $index ] = array(
                                        'id' => $result->retweeted_status->id_str,
                                        'title' => $result->retweeted_status->text,
                                        'permalink' => 'http://twitter.com/' . $result->retweeted_status->user->screen_name . '/status/' . $result->retweeted_status->id_str,
                                        'image' => $this->try_fetching_tweet_image( $result->retweeted_status, $slidedeck, $result->retweeted_status->user ),
                                        'image_tile' => $result->retweeted_status->user->profile_background_tile,
                                        'author_username' => $result->retweeted_status->user->screen_name,
                                        'author_name' => $result->retweeted_status->user->name,
                                        'author_url' => 'http://twitter.com/' . $result->retweeted_status->user->screen_name,
                                        'author_email' => false,
                                        'author_avatar' => $result->retweeted_status->user->profile_image_url,
                                        'content' => $this->linkify_twitter_text($result->retweeted_status->text),
                                        'comment_count' => false,
                                        'plusone_count' => false,
                                        'reshare_count' => false,
                                        'excerpt' => $this->linkify_twitter_text($result->retweeted_status->text),
                                        'created_at' => strtotime( $result->retweeted_status->created_at ),
                                        'local_created_at' => $result->retweeted_status->created_at,
                                        'description' => $result->retweeted_status->user->description,
                                    );
                                    
                                    // Add Replying to data for original tweet:
                                    if( !empty( $result->retweeted_status->in_reply_to_screen_name ) ){
                                        $twitter_posts[ $index ]['in_reply_to_screen_name'] = $result->retweeted_status->in_reply_to_screen_name;
                                    }
                                    if( !empty( $result->retweeted_status->in_reply_to_status_id_str ) ){
                                        $twitter_posts[ $index ]['in_reply_to_status_id_str'] = $result->retweeted_status->in_reply_to_status_id_str;
                                    }
                                } else {
                                    // Normal Tweet
                                    $twitter_posts[ $index ] = array(
                                        'id' => $result->id_str,
                                        'title' => $result->text,
                                        'permalink' => 'http://twitter.com/' . $result->user->screen_name . '/status/' . $result->id_str,
                                        'image' => $this->try_fetching_tweet_image( $result, $slidedeck, $result->user ),
                                        'image_tile' => $result->user->profile_background_tile,
                                        'author_username' => $result->user->screen_name,
                                        'author_name' => $result->user->name,
                                        'author_url' => 'http://twitter.com/' . $result->user->screen_name,
                                        'author_email' => false,
                                        'author_avatar' => $result->user->profile_image_url,
                                        'content' => $this->linkify_twitter_text($result->text),
                                        'comment_count' => false,
                                        'plusone_count' => false,
                                        'reshare_count' => false,
                                        'excerpt' => $this->linkify_twitter_text($result->text),
                                        'created_at' => strtotime( $result->created_at ),
                                        'local_created_at' => $result->created_at,
                                        'description' => $result->user->description
                                    );
                                    
                                    // Add Replying to data:
                                    if( !empty( $result->in_reply_to_screen_name ) ){
                                        $twitter_posts[ $index ]['in_reply_to_screen_name'] = $result->in_reply_to_screen_name;
                                    }
                                    if( !empty( $result->in_reply_to_status_id_str ) ){
                                        $twitter_posts[ $index ]['in_reply_to_status_id_str'] = $result->in_reply_to_status_id_str;
                                    }
                                }
                            }
                        }
                    break;
                }
            }else{
                return false;
            }
            // Write the cache
            slidedeck2_cache_write( $cache_key, $twitter_posts, $slidedeck['options']['cache_duration'] );
        }
        
        return $twitter_posts;
    }
    
    /**
     * Scans the URLs and Media Entities for something
     * that looks like an image.
     * 
     * @param object $twitter JSON Entities Object
     * @param object $slidedeck
     * 
     * @return string An Image URL or empty string
     */
    function try_fetching_tweet_image( $tweet, $slidedeck, $user = null ){
        $entities = $tweet->entities;
        
        // Image extraction attempt:
        if( !empty( $entities->media ) ) {
            $first_item = reset( $entities->media );
            return $first_item->media_url;
        } elseif( !empty( $entities->urls ) ) {
            /**
             * If the URL extension matches the mime types we're
             * looking for then we can try to fetch it.
             */
            foreach( $entities->urls as $url ) {
                if( preg_match( '/\.(jpg|png|gif)$/i', $url->expanded_url ) ){
                    return $url->expanded_url;
                }
                if( preg_match( '/yfrog\.com/i', $url->expanded_url ) ){
                    return $url->expanded_url . ':medium';
                }
                if( preg_match( '/twitpic\.com\/(.*)$/i', $url->expanded_url, $twitpic ) ){
                    return 'http://twitpic.com/show/thumb/' . $twitpic[1] ;
                }
                // Add YouTube
                if( preg_match( '/youtube\.com[^v]+v.(.{11}).*/i', $url->expanded_url, $youtube_matches)){
                    return 'http://img.youtube.com/vi/' . $youtube_matches[1] . '/0.jpg';
                }elseif( preg_match( '/youtube.com\/user\/(.*)\/(.*)$/i', $url->expanded_url, $youtube_matches)){
                    return 'http://img.youtube.com/vi/' . $youtube_matches[2] . '/0.jpg';
                }elseif( preg_match( '/youtu.be\/(.*)$/i', $url->expanded_url, $youtube_matches)){
                    return 'http://img.youtube.com/vi/' . $youtube_matches[1] . '/0.jpg';
                }
                
            }
            
            if( false ) {
                // Try curl?
                foreach( $entities->urls as $url ) {
                    $url = $url->expanded_url;
                    if( $slidedeck['options']['twitter_scrape_images'] ) {
                        $args = array(
                            'sslverify' => false,
                            'redirection' => 3,
                            'user-agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1',
                            'timeout' => 5
                        );
                        $response = wp_remote_get( $url, $args );
                        if( !is_wp_error( $response ) ) {
                            $content = $response['body'];
                            
                            $url_regex = '/<img[a-zA-Z0-9\s\"\'=\-_:;&]+src=[\"\']((https?|ftp|gopher|telnet|file|notes|ms-help):((\/\/)|(\\\\))+[\w\d:#@%\/\;$()~_?\+-=\\\.&]*)[\"\']/';
                            preg_match_all( $url_regex, $content, $matches );
                            
                            foreach( (array) $matches[1] as $match ){
                                if( isset( $match ) ){
                                    if( !preg_match( '/(tweetmeme|youtube|gravatar|logo|stats|advertisement|commindo|valueclickmedia|imediaconnection|adify|traffiq|premiumnetwork|advertisingz|gayadnetwork|vantageous|networkadvertising|advertising|digitalpoint|viraladnetwork|decknetwork|burstmedia|doubleclick).|feeds\.[a-zA-Z0-9\-_]+\.com\/~ff|wp\-digg\-this|feeds\.wordpress\.com|\/media\/post_label_source/i', $match ) ) {
                                        $valid_matches[] = $match;
                                    }
                                }
                            }
                            
                            if( !empty( $valid_matches ) ){
                                return $valid_matches[0];
                            }
                        }
                    }
                }
            } //curl

        }
        
        // Try location?
        if( $slidedeck['options']['useGeolocationImage'] == true ){
            if( isset( $user->location ) && !empty( $user->location ) ){
                return 'http://maps.googleapis.com/maps/api/staticmap?center='.urlencode( $user->location ).'&zoom=10&size=512x512&maptype=roadmap&sensor=false';
            }
        }

        // Try background image?
        if( isset( $user->profile_background_image_url ) && !empty( $user->profile_background_image_url ) ){
            // TODO: Make the background image tile if this is true: profile_background_tile
            return $user->profile_background_image_url;
        } else {
            $screen_name = '';
            if( isset( $user->screen_name ) && !empty( $user->screen_name ) ){
                $screen_name = $user->screen_name;
            }elseif( isset( $tweet->from_user_screen_name ) && !empty( $tweet->from_user_screen_name ) ){
                $screen_name = $tweet->from_user_screen_name;
            }elseif( isset( $tweet->from_user ) && !empty( $tweet->from_user ) ){
                $screen_name = $tweet->from_user;
            }else{
                return '';
            }

            $background = $this->get_twitter_user_background( $screen_name, $slidedeck );
            if( isset( $background ) && !empty( $background ) ){
                return $background['background_image_url'];
            }
        }
        
        
        return '';
    }
    
    /**
     * SlideDeck element class hook-in
     * 
     * Add additional variation classes to the SlideDeck element
     * 
     * @return array
     */
    function slidedeck_classes( $slidedeck_classes, $slidedeck ) {
        if( $this->is_valid( $slidedeck['source'] ) ) {
            if( !empty( $slidedeck['options']['lensVariation'] ) ) {
                $slidedeck_classes[] = $slidedeck['options']['lensVariation'];
            }
        }
        
        return $slidedeck_classes;
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
        if( $this->is_valid( $slidedeck['source'] ) ) {
            $slidedeck_classes[] = "date-format-{$slidedeck['options']['date-format']}";
        }
        
        return $slidedeck_classes;
    }
    
    /**
     * Hook into slidedeck_get_source_file_basedir filter
     * 
     * Modifies the source's basedir value for relative file referencing
     * 
     * @param string $basedir The defined base directory
     * @param string $source_slug The slug of the source being requested
     * 
     * @uses SlideDeck::is_valid()
     * 
     * @return string
     */
    function slidedeck_get_source_file_basedir( $basedir, $source_slug ) {
        if( $this->is_valid( $source_slug ) ) {
            $basedir = dirname( __FILE__ );
        }
        
        return $basedir;
    }
    
    /**
     * Hook into slidedeck_get_source_file_baseurl filter
     * 
     * Modifies the source's basedir value for relative file referencing
     * 
     * @param string $baseurl The defined base directory
     * @param string $source_slug The slug of the source being requested
     * 
     * @uses SlideDeck::is_valid()
     * 
     * @return string
     */
    function slidedeck_get_source_file_baseurl( $baseurl, $source_slug ) {
        if( $this->is_valid( $source_slug ) ) {
           $baseurl = SLIDEDECK2_URLPATH . '/sources/' . basename( dirname( __FILE__ ) );
        }
        
        return $baseurl;
    }
    
    /**
     * Get slides for SlideDecks of this type
     * 
     * Loads the slides associated with this SlideDeck if it matches this Deck type and returns
     * an array of structured slide data.
     * 
     * @param array $slides_arr Array of slides
     * @param object $slidedeck SlideDeck object
     * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeckPlugin::process_slide_content()
     * @uses SlideDeck_Posts::get_slides()
     * 
     * @return array
     */
    function slidedeck_get_slides( $slides, $slidedeck ) {
        global $SlideDeckPlugin;
        
        // Fail silently if not this Deck type
        if( !$this->is_valid( $slidedeck['source'] ) ) {
            return $slides;
        }
        
        $slides_nodes = $this->get_slides_nodes( $slidedeck );
        
        // Loop through all slide nodes to build a structured slides array
        foreach( $slides_nodes as &$slide_nodes ) {
            $slide = array(
                'source' => $this->name,
                'title' => $slide_nodes['title'],
                'created_at' => $slide_nodes['created_at'],
                'classes' => array( 'no-excerpt' )
            );
            $slide = array_merge( $this->slide_node_model, $slide );
			
            // Look to see if an image is associated with this slide
            $has_image = !empty( $slide_nodes['image'] );
            
            if( $has_image ) {
                $slide['thumbnail'] = $slide_nodes['image'];
                $slide['classes'][] = "has-image";
                $slide['type'] = "image";
            } else {
                $slide['classes'][] = "no-image";
            }
			
            $slide_nodes['source'] = $slide['source'];
            $slide_nodes['type'] = $slide['type'];
            
            // Excerpt node
            if( !array_key_exists( 'excerpt', $slide_nodes) || empty( $slide_nodes['excerpt'] ) )
                $slide_nodes['excerpt'] = $slide_nodes['content'];
            
            if( !empty( $slide['title'] ) ) {
                $slide['classes'][] = "has-title";
            } else {
                $slide['classes'][] = "no-title";
            }
            
            // Twitter allows us to set the image as tiling or not
            if( $slide_nodes['image_tile'] )
                $slide['classes'][] = "tile-image";
            
            // Set link target node
            $slide_nodes['target'] = $slidedeck['options']['linkTarget'];

            $slide['content'] = $SlideDeckPlugin->Lens->process_template( $slide_nodes, $slidedeck );
            
            $slides[] = $slide;
        }
        
        return $slides;
    }
    
    /**
     * Hook into iframe script output
     * 
     * @param array $scripts Array of registered script keys being loaded
     * @param array $slidedeck The SlideDeck object
     * 
     * @return array
     */
    function slidedeck_iframe_scripts( $scripts, $slidedeck ) {
        // Load the Twitter intent API if this is a Twitter sourced SlideDeck
        if( $this->is_valid( $slidedeck['source'] ) ) {
            $scripts[] = "twitter-intent-api";
        }
        
        return $scripts;
    }
}