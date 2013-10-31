<?php
/**
 * SlideDeck Lens Model
 * 
 * Model for handling CRUD and other basic functionality for Lens management
 * 
 * @author dtelepathy
 * @package SlideDeck
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
class SlideDeckLens {
    var $namespace = "slidedeck";
    
    // Expected meta values and types
    var $lens_meta = array(
        "name" => "",
        "uri" => "",
        "sources" => array(),
        "description" => "",
        "default_nav_styles" => true,
        "version" => "",
        "variations" => array(),
        "author" => "",
        "autor_uri" => "",
        "contributors" => array(),
        "tags" => array()
    );
    
    /**
     * Indents a flat JSON string to make it more human-readable.
     * 
     * Script courtesy of recursive-design.com. Original post:
     * http://recursive-design.com/blog/2008/03/11/format-json-with-php/
     *
     * @param string $json The original JSON string to process.
     *
     * @return string Indented version of the original JSON string.
     */
    private function _indent_json( $json ) {
        $result      = '';
        $pos         = 0;
        $strLen      = strlen($json);
        $indentStr   = '    ';
        $newLine     = "\n";
        $prevChar    = '';
        $outOfQuotes = true;
        
        for ($i=0; $i<=$strLen; $i++) {
        
            // Grab the next character in the string.
            $char = substr($json, $i, 1);
        
            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;
            
            // If this character is the end of an element, 
            // output a new line and indent the next line.
            } else if(($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j=0; $j<$pos; $j++) {
                    $result .= $indentStr;
                }
            }
            
            // Add the character to the result string.
            $result .= $char;
        
            // If the last character was the beginning of an element, 
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
                
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            
            $prevChar = $char;
        }
        
        // Add spacing after colons between key/value pairs in JSON object
        $result = preg_replace( "/\":(\"|\{|\[|\d)/", '": $1', $result );
        
        return $result;
    }
	
	/**
	 * Copy a directory's contents recursively
	 * 
	 * @param string $src The source directory to copy from
	 * @param string $src The destination directory to copy to
	 */
	private function _rcopy( $src, $dst ) {
		$dir = opendir( $src );
		
		if( !is_dir( $dst ) )
			mkdir( $dst );
		
		while( false !== ( $file = readdir( $dir ) ) ) {
			if( ( $file != '' ) && ( !in_array( $file, array( '.', '..' ) ) ) ) {
				if( is_dir( $src . '/' . $file ) ) {
					$this->_rcopy( $src . '/' . $file, $dst . '/' . $file );
				} else {
					copy( $src . '/' . $file, $dst . '/' . $file );
				}
			}
		}
		closedir( $dir );
	}

	private function _rdelete( $dir ) {
	    if( substr( $dir, -1 ) == "/" ) {
	        $dir = substr( $dir, 0, -1 );
	    }
	
	    if( !file_exists( $dir ) || !is_dir( $dir ) ) {
	        return false;
	    } elseif( !is_writable( $dir ) ) {
	        return false;
	    } else {
	        $handle = opendir( $dir );
	       
	        while( $contents = readdir( $handle ) ) {
	            if( $contents != '.' && $contents != '..' ) {
	                $path = $dir . "/" . $contents;
	               
	                if( is_dir( $path ) ) {
	                    $this->_rdelete( $path );
	                } else {
	                    unlink( $path );
	                }
	            }
	        }
			
	        closedir( $handle );

            if( !rmdir( $dir ) ) {
                return false;
            }
	       
	        return true;
	    }
	}
    
    /**
     * Sort lenses
     * 
     * Sort lenses alphabetically by their lens name. Used by usort() function.
     * 
     * @param object $a
     * @param object $b
     * 
     * @return boolean
     */
    private function _sort( $a, $b ) {
        $a_name = strtolower( $a['meta']['name'] );
        $b_name = strtolower( $b['meta']['name'] );
        
        return $a_name > $b_name;
    }
	
	/**
	 * Copy a lens
	 * 
	 * Copies the lens' directory recursively to the /wp-content/plugins/slidedeck-lenses folder. 
	 * Returns the lens object returned by SlideDeckLens::get(). This method will automatically
     * increment any directory name suggested or found and will modify any non-unique lens name.
	 * 
	 * @param string $slug The slug of the lens to duplicate
     * @param string $new_lens_name An optional suggested new lens name
     * @param string $new_lens_slug An optional suggested new lens slug
	 * @param boolean $replace_js Optionaly attempt to replace class references in the JavaScript (may break JavaScript)
	 * 
     * @global $SlideDeckPlugin
     * 
     * @uses SlideDeckLens::get()
     * @uses SlideDeckLens::copy_inc()
     * @uses SlideDeckLens::_rcopy()
     * @uses SlideDeckLens::save()
     * @uses SlideDeckPlugin::get_classname()
     * 
	 * @return array
	 */
	function copy( $slug, $new_lens_name = "", $new_lens_slug = "", $replace_js = false ) {
	    global $SlideDeckPlugin;
        
		// Lookup the requested lens to copy to make sure it exists
		$original_lens = $this->get( $slug );
		
		// Set the default return value
		$lens = false;
		
		// Begin copy process if the original lens to be copied exists
		if( $original_lens ) {
			// The original lens' directory name
			$original_dir = dirname( $original_lens['files']['css'] );
            
            if( empty( $new_lens_slug ) ) {
                $new_lens_slug = $original_lens['slug'];
            }
            // Clean invalid characters to only match [a-zA-Z0-9\-_]
            $new_lens_slug = preg_replace( "/[^a-zA-Z0-9\-_]/", "", html_entity_decode( $new_lens_slug ) );
            
			// Find a number to increment the lens' slug to
			$inc = $this->copy_inc( $new_lens_slug );
			// Create a new slug based off the lens' slug and its increment
			$new_slug = $new_lens_slug . ( $inc > 0 ? "-{$inc}" : "" );
            
			if( is_writable( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
				$destination_dir = SLIDEDECK2_CUSTOM_LENS_DIR . "/" . $new_slug;
				
				$this->_rcopy( $original_dir, $destination_dir );
			}
			// Lens could not be copied
			else {
                return false;
			}
			
			// Get the recently copied lens with its meta
			$lens = $this->get( $new_slug );
			
            if( empty( $new_lens_name ) ) {
                $new_lens_name = "{$lens['meta']['name']} (Copy $inc)";
            }
			$lens['meta']['name'] = $new_lens_name;
			
			$this->save( $lens['files']['meta'], "", $new_slug, $lens['meta'] );
			
			$lens_files = glob( $destination_dir . "/lens.*" );
			
			foreach( (array) $lens_files as $lens_file ) {
				// Get the type of file this is for JavaScript and CSS processing
				$file_ext = substr( $lens_file, strrpos( $lens_file, "." ) + 1 );
				
				switch( $file_ext ) {
                    case "css":
    					$lens_file_content = file_get_contents( $lens_file );
    					$lens_file_content = str_replace( ".lens-" . $original_lens['slug'], ".lens-{$new_slug}", $lens_file_content );
                        $modify = true;
                    break;
                    
                    case "php":
                        $old_classname = slidedeck2_get_classname_from_filename( $original_lens['slug'] );
                        $new_classname = slidedeck2_get_classname_from_filename( $new_slug );
                        
                        $lens_file_content = file_get_contents( $lens_file );
                        $lens_file_content = str_replace( "class SlideDeckLens_{$old_classname}", "class SlideDeckLens_{$new_classname}", $lens_file_content );
                        $modify = true;
                    break;
                    
                    case "json":
                    case "js":
                        $modify = false;
						if( $replace_js === true ) {
	    					$lens_file_content = file_get_contents( $lens_file );
	    					$lens_file_content = str_replace( $original_lens['slug'], $new_slug, $lens_file_content );
							$modify = true;
						}
                    break;
				}
				
				if( $modify && is_writeable( $lens_file ) ) {
					//is_writable() not always reliable, check return value. see comments @ http://uk.php.net/is_writable
					$f = fopen( $lens_file, 'w+' );
					if( $f !== false ) {
						fwrite( $f, ( $lens_file_content ), strlen( $lens_file_content ) );
						fclose( $f );
					}
				}
			}
		}
		
		return $lens;
	}

    /**
     * Find a unique folder name
     * 
     * Looks for a unique destination directory. Returns either the original
     * directory passed to the function or one with an incremented suffix.
     * 
     * @param string $slug The slug to make a new, incremented version of
     * @param integer $inc The increment for the new folder
     * 
     * @return integer
     */
    function copy_inc( $slug = "", $inc = 0 ) {
        if( is_dir( SLIDEDECK2_DIRNAME . '/lenses/' . $slug ) || is_dir( SLIDEDECK2_CUSTOM_LENS_DIR . '/' . $slug ) ) {
            $inc++;
            
            if( preg_match( "/\-(\d+)$/", $slug ) ) {
                $new_slug = preg_replace( "/\-(\d+)$/", "-{$inc}", $slug );
            } else {
                $new_slug = $slug . "-{$inc}";
            }
            
            $inc = $this->copy_inc( $new_slug, $inc );
        }
        
        return $inc;
    }
    
	/**
	 * Delete a lens
	 * 
	 * @param string $slug The slug of the lens to be deleted
	 * 
	 * @return boolean
	 */
	function delete( $slug ) {
		$lens = $this->get( $slug );
		$dir = dirname( $lens['files']['meta'] );
		
		return $this->_rdelete( $dir );
	}
    
	/**
	 * Filter empty values from an array
	 * 
	 * For use as a callback function by array_filter()
	 * 
	 * @param mixed The value to check against
	 */
	function filter_empty( $val ) {
		return !empty( $val );
	}
	
    /**
     * Load a lens
     * 
     * Loads a lens or all lenses (if no lens slug is specified). Parses all meta about the lens
     * and builds an array of information about the lens including paths for all its related
     * asset files.
     * 
     * @param string $slug The slug of a specific lens
     * 
     * @return array
     */
    function get( $slug = "" ) {
        $cache_key = $this->namespace . "--" . md5( __METHOD__ . $slug );
        
        $lenses = wp_cache_get( $cache_key, slidedeck2_cache_group( 'lenses-get' ) );
        if( $lenses == false ) {
            $lenses = array();
            $all_lens_files = array();
            $folders = !empty( $slug ) ? $slug : "*";
            
            // Get stock lens files that come with SlideDeck distribution
            $lens_files = (array) glob( SLIDEDECK2_DIRNAME . '/lenses/' . $folders . '/lens.json' );

            // Check for custom lenses if the custom lenses folder exists
            if( is_dir( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
                // Make sure we can read the folder
                if( is_readable( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
                    // Load and combine the custom lenses in with the stock lenses
                    $custom_lens_files = (array) glob( SLIDEDECK2_CUSTOM_LENS_DIR . '/' . $folders . '/lens.json' );
                	$lens_files = array_merge( $lens_files, $custom_lens_files );
                }
            }
            // Loop through each lens file to build an array of lenses
            foreach( (array) $lens_files as $lens_file ) {
                $key = basename( dirname( $lens_file ) );
                $all_lens_files[$key] = $lens_file;
            }
            
            // Append each lens to the $lenses array including the lens' meta
            foreach ( (array) array_values( $all_lens_files ) as $lens_file ) {
                if ( is_readable( $lens_file ) ) {
                    $lens_meta = $this->get_meta( $lens_file );
                    $lenses[$lens_meta['slug']] = $lens_meta;
                }
            }
            
            wp_cache_set( $cache_key, $lenses, slidedeck2_cache_group( 'lenses-get' ) );
        }
        
        $lenses = apply_filters( "{$this->namespace}_get_lenses", $lenses, $slug );
        
        if( !empty( $slug ) ) {
            $lenses = reset( $lenses );
        } else {
            // Sort lenses alphabetically
            usort( $lenses, array( &$this, '_sort' ) );
        }
        
        return $lenses;
    }

	/**
	 * Get Lens CSS content
	 * 
	 * Loads a lens' CSS file and returns the content of the lens file with the 
	 * meta comment extracted.
	 * 
	 * @param string $filename The file name of the lens to get the content from
	 * @param boolean $strip_meta Should the meta be stripped from the top of the page?
	 * 
	 * @return string
	 */
	function get_content( $filename ) {
		$lens_content = "";
		
		// Only load the content if this is actually a file and it isn't empty
		if ( is_file( $filename ) && filesize( $filename ) > 0 ) {
			$f = fopen( $filename , 'r' );
			$lens_content = fread( $f, filesize( $filename ) );
		}
		
		return $lens_content;
	}
	
    /**
     * Compile HTML for lens CSS tags
     * 
     * @param object $lens
     * 
     * @return string HTML markup of lens CSS tags
     */
    function get_css( $lens ) {
        $version = isset( $lens['meta']['version'] ) && !empty( $lens['meta']['version'] ) ? $lens['meta']['version'] : SLIDEDECK2_VERSION;
        
        $lens_css_tags = '';
        if( isset( $lens['ie_url'] ) && !empty( $lens['ie_url'] ) ) {
            $lens_css_tags .= '<!--[if lte IE 8]><link rel="stylesheet" type="text/css" href="' . $lens['ie_url'] . '?v=' . $version . '" media="screen" /><![endif]-->';
        }
        if( isset( $lens['ie7_url'] ) && !empty( $lens['ie7_url'] ) ) {
            $lens_css_tags .= '<!--[if IE 7]><link rel="stylesheet" type="text/css" href="' . $lens['ie7_url'] . '?v=' . $version . '" media="screen" /><![endif]-->';
        }
        if( isset( $lens['ie8_url'] ) && !empty( $lens['ie8_url'] ) ) {
            $lens_css_tags .= '<!--[if IE 8]><link rel="stylesheet" type="text/css" href="' . $lens['ie8_url'] . '?v=' . $version . '" media="screen" /><![endif]-->';
        }
        
        return $lens_css_tags;
    }

    /**
     * Process lens meta data from a lens file. Used by slidedeck_get_lens and slidedeck_get_lenses
     * 
     * @param object $lens_file
     * 
     * @uses site_url()
     * 
     * @return arr Lens meta data
     */
    function get_meta( $filename ) {
    	global $SlideDeckPlugin;
		
        $cache_key = $this->namespace . "--" . md5( __METHOD__ . $filename );
        
        $lens = wp_cache_get( $cache_key, slidedeck2_cache_group( 'lenses-get-meta' ) );
        if( $lens == false ) {
            $lens_data = file_get_contents( $filename );
            $lens_folder = dirname( $filename );
            $lens_slug = basename( $lens_folder );
            
            // Pre-populate the lens meta with default values and types
            $lens_meta = $this->lens_meta;
            // Lens JSON descriptor
            $lens_file_meta = json_decode( $lens_data, true );
            // Merge with the default options
            $lens_meta = array_merge( $lens_meta, $lens_file_meta );
            
            // Get the lens' base folder URL
            $lens_url = untrailingslashit( WP_PLUGIN_URL ) . str_replace(str_replace("\\","/",WP_PLUGIN_DIR), "", str_replace("\\","/",$lens_folder));
            
            // Adjust URL for SSL if we are running the current page through SSL
            if( is_ssl() ) $lens_url = str_replace( "http://", "https://", $lens_url );
            
            $lens = array(
                'url' => $lens_url . "/lens.css",
                'thumbnail' => $lens_url . "/thumbnail.jpg",
                'thumbnail-large' => $lens_url . "/thumbnail-large.jpg",
                'slug' => $lens_slug,
                'templates' => array(
                	'default' => $lens_folder . '/template.thtml'
				),
                'meta' => $lens_meta,
                'files' => array(
                    'meta' => $lens_folder . '/lens.json',
                    'css' => $lens_folder . '/lens.css'
                )
            );
            
            // IE stylesheet for all versions of IE
            if( file_exists( $lens_folder . "/lens.ie.css" ) ) {
                $lens['ie_url'] = $lens_url . "/lens.ie.css";
                $lens['files']['css_ie'] = $lens_folder . '/lens.ie.css';
            }
            // Stylesheet for IE7 and below
            if( file_exists( $lens_folder . "/lens.ie7.css" ) ) {
                $lens['ie7_url'] = $lens_url . "/lens.ie7.css";
                $lens['files']['css_ie7'] = $lens_folder . '/lens.ie7.css';
            }
            // Stylesheet for IE8 and below
            if( file_exists( $lens_folder . "/lens.ie8.css" ) ) {
                $lens['ie8_url'] = $lens_url . "/lens.ie8.css";
                $lens['files']['css_ie8'] = $lens_folder . '/lens.ie8.css';
            }
            // Lens JavaScript
            if ( file_exists( $lens_folder . "/lens.js" ) ) {
                $lens['script_url'] = $lens_url . "/lens.js";
                $lens['files']['js'] = $lens_folder . '/lens.js';
            }
            // Lens Admin JavaScript
            if ( file_exists( $lens_folder . "/lens.admin.js" ) ) {
                $lens['admin_script_url'] = $lens_url . "/lens.admin.js";
                $lens['files']['admin_js'] = $lens_folder . '/lens.admin.js';
            }
			
			foreach( array_keys( $SlideDeckPlugin->SlideDeck->slide_types ) as $slide_type ) {
				$template_file = $lens_folder . '/template.' . $slide_type . '.thtml';
				if( file_exists( $template_file ) ) {
					$lens['templates'][$slide_type] = $template_file;
				}
			}
            
            // TODO: Loop Through sources
			foreach( $lens['meta']['sources'] as $source ) {
				$template_file = $lens_folder . '/template.source.' . $source . '.thtml';
				if( file_exists( $template_file ) ) {
					$lens['templates'][$source] = $template_file;
				}
			}
            wp_cache_set( $cache_key, $lens, slidedeck2_cache_group( 'lenses-get-meta' ) );
        }

        return $lens;
    }

    /**
     * Check if this lens is protected
     * 
     * Checks to see if the lens file requested belongs to one of the stock lenses that comes with
     * SlideDeck. Any lens that exists in the /wp-content/plugins/slidedeck/lenses folder is considered
     * protected and cannot be edited via the editing interface. To edit one of these lenses, copy
     * it first to the /wp-content/plugins/slidedeck-lenses folder via FTP or the management interface.
     * 
     * @param string $lens_filename The full filename of the lens to check
     * 
     * @return boolean
     */
    function is_protected( $lens_filename ) {
        $protected = true;
        
        // Check for existence of the file first
        $file_exists = is_file( $lens_filename );
        
        if( $file_exists ) {
            if( str_replace( "\\", "/", dirname( dirname( $lens_filename ) ) ) == str_replace( "\\", "/", SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
                $protected = false;
            }
        }
        
        return $protected;
    }
    
    /**
     * Detect if the Lens folder is writeable
     * 
     * Looks to make sure that the custom Lens folder exists and is writeable. Returns an object
     * with an appropriate error message and status.
     * 
     * @return object
     */
    function is_writable() {
        $response = array(
            'valid' => false,
            'error' => ""
        );
        
        if( is_dir( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
            if( is_writable( SLIDEDECK2_CUSTOM_LENS_DIR ) ) {
                $response['valid'] = true;
            } else {
                $response['valid'] = false;
                $response['error'] = "<strong>ERROR:</strong> The " . SLIDEDECK2_CUSTOM_LENS_DIR . " directory is not writable, please make sure the server can write to it.";
            }
        } else {
            $response['valid'] = false;
            $response['error'] = "<strong>ERROR:</strong> The " . SLIDEDECK2_CUSTOM_LENS_DIR . " directory does not exist, please create it and make sure the server can write to it.";
        }
        
        return (object) $response;
    }
    
    /**
     * Parses raw HTML and returns an array of images
     * 
     * @param string $html_string Raw HTML to be processed
     * 
     * @return array
     */
    function parse_html_for_images( $html_string = "" ) {
        $html_string = preg_replace( "/([\n\r]+)/", "", $html_string );
        
        $image_strs = array();
        preg_match_all( '/<img(\s*([a-zA-Z]+)\=[\"\']([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)[\"\'])+\s*\/?>/', $html_string, $image_strs );

        $images_all = array();
        if( isset( $image_strs[0] ) && !empty( $image_strs[0] ) ) {
            foreach( (array) $image_strs[0] as $image_str ) {
                $image_attr = array();
                preg_match_all( '/([a-zA-Z]+)\=[\"\']([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)[\"\']/', $image_str, $image_attr );
                
                if( in_array( 'src', $image_attr[1] ) ) {
                    $images_all[] = array_combine( $image_attr[1], $image_attr[2] );
                }
            }
        }
        
        $images = array();
        if( !empty( $images_all ) ) {
            foreach( $images_all as $image ) {
                // Filter out advertisements and tracking beacons
                if( $this->test_image_for_ads_and_tracking( $image['src'] ) ) {
                    $images[] = $image['src'];
                }
            }
        }
        
        return $images;
    }
    
    /**
     * Parses image URL and returns false if it's a banned image 
     * 
     * @param string $image an image URL
     * 
     * @return mixed false if is an advertisment/banned and the image strign if not
     */
    function test_image_for_ads_and_tracking( $input_image = "" ) {
        // Filter out advertisements and tracking beacons
        $blacklist_regex = apply_filters( "{$this->namespace}_image_blacklist", SLIDEDECK2_IMAGE_BLACKLIST );
        if( preg_match( $blacklist_regex, $input_image ) )
            return false;
        
        return $input_image;
    }
    
    /**
     * Process a lens template file
     * 
     * Loads a template file and processes its content using the node data passed in. Returns
     * a string of the processed template HTML.
     * 
     * @param array $nodes Various information nodes available to use in the template file
     * @param string $template The full file path to the template file to use as output
     * 
     * @return string
     */
    function process_template( $nodes = array(), $slidedeck ) {
    	global $SlideDeckPlugin;
		
        $lens = $this->get( $slidedeck['lens'] );
        
        $size = $slidedeck['options']['size'];
		if( $size == "custom" ) {
			$size = $SlideDeckPlugin->SlideDeck->get_closest_size( $slidedeck );
		}
        
        if( isset( $nodes['created_at'] ) && !empty( $nodes['created_at'] ) ) {
            $nodes['created_at'] = is_numeric( $nodes['created_at'] ) ? $nodes['created_at'] : strtotime( $nodes['created_at'] );
			
			/**
			 * Preserve the created_at value somehow instead of being destructive. 
			 * The next switch has the ability to wipe out this data with no
			 * way to recover it later in the request.
			 */
			$nodes['unfiltered_created_at'] = $nodes['created_at'];
            
            $date_format = isset( $slidedeck['options']['date-format'] ) ? $slidedeck['options']['date-format'] : "none";
            switch( $date_format ) {
                case "none":
                    $nodes['created_at'] = "";
                break;
                
                case "timeago":
                    $nodes['created_at'] = human_time_diff( $nodes['created_at'], current_time( 'timestamp', 1 ) ) . " ago";
                break;
                
                case "human-readable":
                    $nodes['created_at'] = date( "F j, Y", $nodes['created_at'] );
                break;
                
                case "human-readable-abbreviated":
                    $nodes['created_at'] = date( "M j, Y", $nodes['created_at'] );
                break;
                
                case "raw":
                    $nodes['created_at'] = date( "m/d/Y", $nodes['created_at'] );
                break;
                
                case "raw-eu":
                    $nodes['created_at'] = date( "Y/m/d", $nodes['created_at'] );
                break;
            }
        }
        
        $nodes = apply_filters( "{$this->namespace}_slide_nodes", $nodes, $slidedeck );
        
        /**
         * Check for avatar exclusion
         * 
         * If this is not a preview and the author avatar option is set to 'no'
         * then set all the author avatar values to ''
         */
        if( $SlideDeckPlugin->preview === false ) {
            if( ( (bool) $slidedeck['options']['show-author-avatar'] ) === false ) {
                foreach( $nodes as &$node ) {
                    $nodes['author_avatar'] = '';
                }
            }
        }
		
        // Make all keyed node values accessible as variables for the template
        extract( $nodes );
		
        // Check for slide type template override
		$template = $lens['templates']['default'];
		if( isset( $lens['templates'][$type] ) ) {
			$template = $lens['templates'][$type];
		}
        
        // Check for source type template override
        if( isset( $lens['templates'][$source] ) ) {
            $template = $lens['templates'][$source];
        }
		
        if( isset( $video_meta ) ){
            $video_container = "<div id=\"video__{$video_meta['id']}__{$slidedeck['id']}-{$deck_iteration}-{$slide_counter}\" class=\"{$video_meta['service']} video-container\" data-video-id=\"{$video_meta['id']}\"></div>";
        }
        
        ob_start();
            
            // Load the template to be processed as PHP
            if( file_exists( $template ) ){
                include( $template );
            }
            
            // Grab the output buffer content for rendered template output
            $html = ob_get_contents();
            
        ob_end_clean();
        
        if( isset( $permalink ) && !empty( $permalink ) ) {
            $target = isset( $target ) ? $target : "_top";
            $html .= '<a href="' . $permalink . '" class="full-slide-link-hit-area" target="' . $target . '"></a>';
        }
        
        return $html;
    }
    
	/**
	 * Save Lens CSS content
	 * 
	 * Processes CSS content for saving and then writes the lens to the file passed in.
	 * All parameters are required to save the lens file. Returns the newly saved lens
	 * in its lens array object as returned by SlideDeckLens::get() or boolean(false) if
	 * there was an error in writing the updated lens.css file.
	 * 
     * @param string $filename Lens filename to save
	 * @param string $content The string of CSS to save to the file passed in the second parameter
	 * @param string $slug The slug of the lens to update the lens.css file for
	 * @param array $meta Meta to update on the CSS lens file's meta comment
	 * 
	 * @return array
	 */
	function save( $filename, $content, $slug, $meta = array() ) {
		// Look up the old lens to get the lens' primary file name
		$old_lens = $this->get( $slug );
        // Get the extension of the file being saved
        $file_ext = substr( $filename, strrpos( $filename, ".", 1 ) );
        
        // If this is the primary lens.css file, merge the metas together and build the meta header
        if( basename( $filename ) == basename( $old_lens['files']['meta'] ) ) {
        	foreach( $this->lens_meta as $key => $val ) {
        		if( is_bool( $val ) )
        			if( !isset( $meta[$key] ) )
						$meta[$key] = false;
        	}
			
            foreach( $old_lens['meta'] as $key => $val ) {
                if( is_array( $val ) ) {
					if( isset( $meta[$key] ) ) {
						$intersect = array_intersect( $val, (array) $meta[$key] );
						$merged = array_merge( $intersect, (array) $meta[$key] );
						if( !is_array( reset( $merged ) ) )
							$merged = array_unique( $merged );
						$meta[$key] = $merged;
					} else {
						$meta[$key] = $val;
					}
                } else {
                    $meta[$key] = isset( $meta[$key] ) ? $meta[$key] : $val;
                }
            }
			
			// Future proof lenses for new options added to the JSON default model, automatically includes new properties in the JSON
			foreach( $this->lens_meta as $key => $val ) {
				if( !isset( $meta[$key] ) ) {
					$meta[$key] = $val;
				}
			}
			
            $content = json_encode( $meta );
            $content = $this->_indent_json( $content );
        }
		
		// Clean up the string content to remove slashes and tags for CSS files
		if( $file_ext == "css" ) {
		    $content = strip_tags( $content );
		}
		$content = stripslashes( $content );
		
		// Set default return value to false
		$lens = false;
		
		if( is_writeable( $filename ) ) {
			//is_writable() not always reliable, check return value. see comments @ http://uk.php.net/is_writable
			$f = fopen( $filename, 'w+' );
			if( $f !== false ) {
				fwrite( $f, ( $content ), strlen( $content ) );
				fclose( $f );
				
				// Set the return lens value to the updated lens object
				$lens = $this->get( $slug );
			}
		}
		
		return $lens;
	}
}
