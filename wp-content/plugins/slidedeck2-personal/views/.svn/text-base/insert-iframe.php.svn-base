<?php
/**
 * Preview SlideDeck iframe template
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
<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo _e( "Insert your SlideDeck", $namespace ); ?></title>
        
        <link rel="stylesheet" type="text/css" href="<?php echo SLIDEDECK2_URLPATH; ?>/css/fancy-form.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo SLIDEDECK2_URLPATH; ?>/css/slidedeck-admin.css" media="all" />
        
        <?php
            foreach( $scripts as $script ) {
                $src = $wp_scripts->registered[$script]->src;
                if ( !preg_match( '|^https?://|', $src ) && !( $content_url && 0 === strpos( $src, $content_url ) ) ) {
                    $src = $base_url . $src;
                }
                
                echo '<script type="text/javascript" src="' . $src . ( strpos( $src, "?" ) !== false ? "&" : "?" ) . "v=" . $wp_scripts->registered[$script]->ver . '"></script>';
            }
        ?>
        
        <style type="text/css">
            body, html {
                position: relative;
                width: 100%;
                height: 100%;
                overflow: hidden;
                margin: 0;
                padding: 0;
                background-color: #f2f2f2;
            }
            #slidedeck-insert-iframe-form {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                overflow: hidden;
            }
            #slidedeck-insert-iframe-wrapper {
                position: absolute;
                top: 45px;
                right: 0;
                bottom: 60px;
                left: 0;
                overflow: auto;
                overflow-x: hidden;
                border-bottom: 1px solid #d1d1d1;
            }
        </style>
    </head>
    <body class="insert-iframe-modal">
        <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="GET" id="slidedeck-insert-iframe-form">
            <div id="slidedeck-insert-iframe-section-header" class="slidedeck-header">
                <h1><?php _e( "Choose a SlideDeck to insert:", $namespace ); ?></h1>
                
                <?php slidedeck2_html_input( 'orderby', $orderby, array( 'type' => 'select', 'label' => "Arrange by:", 'attr' => array( 'class' => 'fancy' ), 'values' => $order_options ) ); ?>
                
                <input type="hidden" name="action" value="<?php echo $namespace; ?>_insert_iframe_update" />
                <?php wp_nonce_field( "slidedeck-update-insert-iframe", "_wpnonce_insert_update", false ); ?>
                <?php wp_nonce_field( "slidedeck-insert" ); ?>
            </div>
            
            <div id="slidedeck-insert-iframe-wrapper">
                
                <fieldset id="slidedeck-insert-iframe-section-table">
                    
                    <div class="inner">
                        
                        <?php echo $insert_iframe_table; ?>
                        
                    </div>
                    
                </fieldset>
            
            </div>
            
            <p class="submit-row">
                <a href="#cancel" id="slidedeck-insert-iframe-cancel-link"><?php _e( "Cancel", $namespace ); ?></a>
                <input type="submit" class="slidedeck-button-primary" value="<?php _e( "Insert", $namespace ); ?>" />
            </p>
        </form>
    </body>
</html>