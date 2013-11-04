<?php 
if( SlideDeckPlugin::$slidedeck_addons_installing ) {
	if( !class_exists( 'SlideDeckPluginInstall' ) ) {
	    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' ;
	    class SlideDeckPluginInstall extends Plugin_Upgrader {
	        
	        function install_strings() {
	            $this->strings['up_to_date'] = __('The plugin is at the latest version.');
	            $this->strings['no_package'] = __('Installation package not available.');
	            $this->strings['downloading_package'] = __('Downloading new plugin from <span class="code">%s</span>&#8230;');
	            $this->strings['unpack_package'] = __('Unpacking the plugin&#8230;');
	            $this->strings['deactivate_plugin'] = __('Deactivating the existing plugin#8230;');
	            $this->strings['remove_old'] = __('Removing existing plugin&#8230;');
	            $this->strings['remove_old_failed'] = __('Could not remove the existing plugin.');
	            $this->strings['process_failed'] = __('Plugin install failed.');
	            $this->strings['process_success'] = __('Plugin install successfully.');
	        }
	        
	        function install( $plugins ) {
	            $this->init();
	            $this->bulk = true;
	            $this->install_strings();
	    
	            $current = get_site_transient( 'update_plugins' );
	    
	            $this->skin->header();
	    
	            // Connect to the Filesystem first.
	            $res = $this->fs_connect( array(WP_CONTENT_DIR, WP_PLUGIN_DIR) );
	            if ( ! $res ) {
	                $this->skin->footer();
	                return false;
	            }
	    
	            $this->skin->bulk_header();
	    
	            // Only start maintenance mode if running in Multisite OR the plugin is in use
	            $maintenance = is_multisite(); // @TODO: This should only kick in for individual sites if at all possible.
	            if ( $maintenance )
	                $this->maintenance_mode(true);
	    
	            $results = array();
	    
	            $this->update_count = count($plugins);
	            $this->update_current = 0;
	            foreach ( $plugins as $plugin ) {
	                $this->update_current++;
	    
	                $result = $this->run(array(
	                            'package' => $plugin,
	                            'destination' => WP_PLUGIN_DIR,
	                            'clear_destination' => true,
	                            'clear_working' => true,
	                            'is_multi' => true,
	                            'hook_extra' => array(),
	                        ));
	    
	                $results[$plugin] = $this->result;
	    
	                // Prevent credentials auth screen from displaying multiple times
	                if ( false === $result )
	                    break;
	            } //end foreach $plugins
	    
	            $this->maintenance_mode(false);
	    
	            $this->skin->bulk_footer();
	    
	            $this->skin->footer();
	    
	            // Cleanup our hooks, in case something else does a upgrade on this connection.
	            remove_filter('upgrader_clear_destination', array(&$this, 'delete_old_plugin'));
	    
	            // Force refresh of plugin update information
	            delete_site_transient('update_plugins');
	    
	            return $results;
	        }
	    }
	
	}// End of if class_exists()
	
	
	
	if( !class_exists( 'SlideDeckPluginInstallSkin' ) ) {
	    class SlideDeckPluginInstallSkin extends Plugin_Installer_Skin {
	    
	        function header() {
	            if ( $this->done_header )
	                return;
	            $this->done_header = true;
	            
	        }
	        
	        function footer() {
	            echo '<p><a href="' . admin_url( '/admin.php?page=' . basename( SLIDEDECK2_BASENAME ) ) . "/upgrades" .  '">Back to ' . SlideDeckPlugin::$friendly_name . ' Upgrades</a></p>';
	        }
	        
	        function bulk_header() {
	            // Nothing yet
	        }
	    
	        function bulk_footer() {
	            // Nothing yet
	        }
	    
	        function before() {
				echo '<div class="installation-block">';
	        }
			
	        function after() {
	            $this->plugin = $this->upgrader->plugin_info();
				$full_path_to_plugin = WP_PLUGIN_DIR . '/' . $this->plugin;
	            
	            show_message( __( 'Activating the plugin&#8230;', SlideDeckPlugin::$namespace ) );
				
                // Clear the existing plugins available for install cache so that the addon activates properly
                wp_cache_delete( 'plugins', 'plugins' );
                
				$result = activate_plugin( $full_path_to_plugin );
				if( !is_wp_error( $result ) ) {
					show_message( __('Plugin Activated Successfully!'), SlideDeckPlugin::$namespace );
				}else{
					show_message( __('Could not activate the plugin.'), SlideDeckPlugin::$namespace );
				}
				echo '</div>';
	        }
	    }
	}// End of if class_exists()

}


?>