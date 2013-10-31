<?php
/**
 * SlideDeck Upgrade Options
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
?>
<div class="slidedeck-wrapper upgrades">
    <?php slidedeck2_flash(); ?>
    <div class="wrap">
        <div id="slidedeck-upgrade-wrapper">
            <div class="slidedeck-license-key-wrapper">
            	<form id="verify_addons_license_key" action="">
            		<input type="hidden" name="action" value="slidedeck_verify_addons_license_key" />
            		<?php foreach( (array) $_REQUEST as $key => $val ): ?>
            			<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>" />
        			<?php endforeach; ?>
        			<?php 
	        			if( empty( $license_key ) && isset( $_REQUEST['license_key']) && !empty( $_REQUEST['license_key'] ) ){
	        				$license_key = $_REQUEST['license_key'];
	        			}
        			?>
	                <?php slidedeck2_html_input( 'data[license_key]', $license_key, array( 'type' => 'password', 'attr' => array( 'class' => 'fancy license-key-text-field' ), 'label' => "Update Your SlideDeck License Key" ) ); ?>
	                <?php wp_nonce_field( "{$this->namespace}_verify_addons_license_key", 'verify_addons_nonce' ); ?>
	                <a href="#verify" class="verify-license-key button">Verify</a>
            	</form>
            </div>
            <div class="addon-verification-response"></div>
        </div>
    </div>
</div>
