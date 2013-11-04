<?php 
/**
 * Based on the work of Anton Andriyevskyy. https://github.com/meglio/wp-upgrademe
 */

 if( !class_exists( 'SlideDeckAutoUpgrade' ) ) {
	class SlideDeckAutoUpgrade{
		/**
		 * Stores parsed and validated data returned by unofficial APIs.
		 * @var array
		 */
		private static $data;

		private static $WP_FILTER_PREFIX = 'wpFilter_';

		public static function register() {
		    add_action( 'in_plugin_update_message-' . plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/slidedeck2.php', array( 'SlideDeckAutoUpgrade', 'in_plugin_update_message_slidedeck2' ), 10, 2 );
            
			$r = new ReflectionClass( __CLASS__ );
			$methods = $r->getMethods( ReflectionMethod::IS_PUBLIC );
			foreach( $methods as $m ) {
				/** @var ReflectionMethod $m */
				if ( $m->isStatic() && strpos( $m->getName(), self::$WP_FILTER_PREFIX ) === 0 )
					add_filter( substr($m->getName(), strlen( self::$WP_FILTER_PREFIX ) ), array( get_class(), $m->getName() ), 10, $m->getNumberOfParameters() );
			}
		}

		public static function wpFilter_http_response( $response, $args, $url ) {
			# Control recursion
			static $recursion = false;
			if ( $recursion )
				return $response;

			if ( empty( $response ) || !is_array($response) || !isset($response['body']) )
				return $response;

			# Guess if it's time to take action
			if( $url == 'http://api.wordpress.org/plugins/update-check/1.0/' ){
				$show_time = true;
			} elseif ( stripos( $url, 'update-check' ) !== false ) {
    			# Prevent failures if WordPress changes url for updates; we will detect if it still contains "update-check" token
    			# and called from withing wp_update_plugins() function
				$show_time = false;
				$traces = debug_backtrace( false );
				foreach( $traces as $trace ) {
					# http request made from within wp_update_plugins
					if ( isset( $trace['function'] ) && $trace['function'] == 'wp_update_plugins' ) {
						$show_time = true;
						break;
					}
				}
				unset( $traces, $trace );
			} else {
				$show_time = false;
			}
            
			if ( !$show_time )
				return $response;

			# Loop over plugins who provided <pluginName>_auto_upgrade() function and use returned url to request for up-to-date version signature.
			# Collect retrieved (only valid) data into $upgrademe
			$plugins = get_plugins();
			$upgrademe = array();
			foreach( $plugins as $file => $info ) {
				# Get url if function exists
				$slug_name = str_replace( '-', '_', basename( $file, '.php' ) );
                
				# Request latest version signature from custom url (non-WP plugins repository api) && validate response variables
				$recursion = true;
				$vars = self::load_plugin_data( $slug_name );
				$recursion = false;
				if ( empty($vars) )
					continue;

				$upgrademe[$file] = $vars;
			}
            
			if ( !count( $upgrademe ) )
				return $response;

			$body = $response['body'];
			if( !empty( $body ) )
				$body = unserialize($body);
            
			if( empty( $body ) )
				$body = array();
            
			foreach( $upgrademe as $file => $upgradeVars ) {
				# Do not override data returned by official WP plugins repository API
				if ( isset( $body[$file] ) )
					continue;

				# If new version is different then current one, only then add info
				if( !isset( $plugins[$file]['Version'] ) || $plugins[$file]['Version'] == $upgradeVars['new_version'] )
					continue;

				$upgradeInfo = new stdClass();
				$upgradeInfo->id = $upgradeVars['id'];
				$upgradeInfo->slug = $upgradeVars['slug'];
				$upgradeInfo->new_version = $upgradeVars['new_version'];
				$upgradeInfo->url = $upgradeVars['url'];
				$upgradeInfo->package = $upgradeVars['package'];
				$body[$file] = $upgradeInfo;
			}
			$response['body'] = serialize( $body );
			return $response;
		}

		public static function wpFilter_plugins_api( $value, $action, $args ) {
			// If for some reason value available already, do not change it
			if( !empty( $value ) )
				return $value;

			if( $action != 'plugin_information' || !is_object( $args ) || !isset( $args->slug ) || empty( $args->slug ) )
				return $value;

			$vars = self::load_plugin_data( $args->slug );
			if( empty( $vars ) )
				return $value;

			return (object) $vars['info'];
		}

		public static function wpFilter_http_request_args( $args, $url ) {
			if( strpos( $url, 'wp-upgrademe' ) === false || !is_array( $args ) )
				return $args;

			$args['sslverify'] = false;
			return $args;
		}

		private static function load_plugin_data( $slug ) {
			if( isset( self::$data[$slug] ) )
				return self::$data[$slug];

			$func_name = $slug.'_auto_upgrade';
			if( !function_exists( $func_name ) )
				return self::$data[$slug] = null;

			$upgrade_url = filter_var( call_user_func( $func_name ), FILTER_VALIDATE_URL );
			if( empty( $upgrade_url ) ) {
				return self::$data[$slug] = null;
			}

			# Request latest version signature from custom url (non-WP plugins repository api) && validate response variables
			$r = wp_remote_post( $upgrade_url, array(
        			'method' => 'POST', 
        			'timeout' => 4, 
        			'redirection' => 5, 
        			'httpversion' => '1.0', 
        			'blocking' => true,
        			'headers' => array(
                        'SlideDeck-Version' => SLIDEDECK2_VERSION,
                        'User-Agent' => 'WordPress/' . get_bloginfo("version"),
                        'Referer' => get_bloginfo("url")
                    ),
        			'body' => null, 
        			'cookies' => array(),
        			'sslverify' => false
                )
            );

			if( is_wp_error( $r ) || !isset( $r['body'] ) || empty( $r['body'] ) ) {
				return self::$data[$slug] = null;
			}

			$vars = json_decode($r['body'], true);
            
            // Capture the extra vairables
            if( isset( $r['headers']['x-sd2-license-tier'] ) ) {
                update_option( 'slidedeck2_cached_tier', $r['headers']['x-sd2-license-tier'] );
            }
            if( isset( $r['headers']['x-sd2-license-expires'] ) ) {
                update_option( 'slidedeck2_cached_expiration', $r['headers']['x-sd2-license-expires'] );
            }
            
			if( empty($vars) || !is_array($vars) || count($vars) > 4 || !isset($vars['new_version']) || !isset($vars['url']) || !isset($vars['package']) || !isset($vars['info'])) {
				return self::$data[$slug] = null;
			}

			# 2 147 483 648 - max int32
			# 16 777 215 - ffffff = max possible value of 6-letters hex
			# 50 000 000 - reasonable offset
			# Finally generate ID between 50 000 000 and 66 777 215
			$vars['id'] = 50000000 + hexdec( substr( md5( $slug ), 1, 6 ) );

			$vars['slug'] = $slug;

			# Sanitize variables of "info"
			if ( !is_array( $vars['info'] ) ) {
    			$vars['info'] = array();
			}

			$info = array();
            $fields = array(
                'name',
                'slug',
                'version',
                'author',
                'author_profile',
                'contributors',
                'requires',
                'tested',
                'compatibility',
                'rating',
                'rating',
                'num_ratings',
                'downloaded',
                'last_updated',
                'added',
                'homepage',
                'sections',
                'download_link',
                'tags'
            );
			foreach( $vars['info'] as $key => $val ) {
				if( !in_array( $key, $fields ) ) {
					continue;
                }
				$info[$key] = $val;
			}
			$info['slug'] = $slug;
			$info['version'] = $vars['new_version'];
			$info['download_link'] = $vars['url'];
			$vars['info'] = $info;

			return self::$data[$slug] = $vars;
		}
        
        /**
         * Plugin Update Message - SlideDeck
         * 
         * Displays a helpful message if an update is available
         * but the user does not have a valid license key entered (no package is returned).
         */
        public static function in_plugin_update_message_slidedeck2( $plugin_data, $response ){
            // If we're in SlideDeck and the package response is empty
            if( $response->slug == 'slidedeck2' && $response->package == '' ){
                global $SlideDeckPlugin;
                echo ' <em class="auto-update-license">';
                printf(__('Enter %1$syour license key%2$s  for updates.', $SlideDeckPlugin->namespace ), "<a href=\"" . 'admin.php?page=' . SLIDEDECK2_BASENAME . '/options' . "\">", '</a>');
                echo '</em>';
            }
        }
	}// End of SlideDeckAutoUpgrade Class
	SlideDeckAutoUpgrade::register();
    
    /**
     * Auto Upgrade function for SlideDeck2
     */
	function slidedeck2_auto_upgrade() {
	    global $SlideDeckPlugin;
	    $key = '';
	    
	    if( $SlideDeckPlugin ){
            $key = (string) $SlideDeckPlugin->get_license_key();
            $license = $SlideDeckPlugin->is_license_key_valid( $key );
            if( $license->tier < 10 ) return false;
	    }
		return SLIDEDECK2_UPDATE_SITE . '/wordpress-update/' . md5( $key ) . '/tier_10';
	}
    
    /**
     * Auto Upgrade function for SlideDeck2 Professional
     */
	function slidedeck2_tier20_auto_upgrade() {
	    global $SlideDeckPlugin;
	    $key = '';
	    
	    if( $SlideDeckPlugin ){
            $key = (string) $SlideDeckPlugin->get_license_key();
            $license = $SlideDeckPlugin->is_license_key_valid( $key );
            if( $license->tier < 20 ) return false;
	    }
		return SLIDEDECK2_UPDATE_SITE . '/wordpress-update/' . md5( $key ) . '/tier_20';
	}
    
    /**
     * Auto Upgrade function for SlideDeck2 Developer
     */
	function slidedeck2_tier30_auto_upgrade() {
	    global $SlideDeckPlugin;
	    $key = '';
	    
	    if( $SlideDeckPlugin ){
            $key = (string) $SlideDeckPlugin->get_license_key();
            $license = $SlideDeckPlugin->is_license_key_valid( $key );
            if( $license->tier < 30 ) return false;
	    }
		return SLIDEDECK2_UPDATE_SITE . '/wordpress-update/' . md5( $key ) . '/tier_30';
	}
    
}// End of if class_exists()
?>