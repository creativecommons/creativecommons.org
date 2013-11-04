<?php
/**
 * SlideDeck Instagram Content Source
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

<div id="content-source-instagram">
    <input type="hidden" name="source[]" value="<?php echo $this->name; ?>" />
    <div class="inner">
        <ul class="content-source-fields">
            <li>
				<?php $tooltip =  __( 'Choose whether to show a user&rsquo;s recent photos, or photos that <em>you</em> have Liked!', $namespace ) ?>
			    <?php slidedeck2_html_input( 'options[instagram_recent_or_likes]', $slidedeck['options']['instagram_recent_or_likes'], array( 'type' => 'radio', 'attr' => array( 'class' => 'fancy' ), 'label' => __( 'Which Photos?', $namespace ), 'values' => array(
			        'recent' => __( 'Recent Photos', $namespace ),
			        'likes' => __( 'Your Likes', $namespace )
			    ), 'description' => "Choose whether to show a user's recent photos, or photos that <em>you</em> have Liked!" ) ); ?>
            </li>
            <li>
				<?php 
					$tooltip =  __( 'Instagram\'s API requires an access token to access your photos.', $namespace );
					$tooltip .= '<br />';
					$tooltip .= sprintf( __( 'Click %1$shere%2$s to get your token. (You only need to do this once)', $namespace ), "<a href='https://instagram.com/oauth/authorize/?client_id=529dede105394ad79dd253e0ec0ac090&redirect_uri=http%3A%2F%2Fwww.slidedeck.com%2Finstagram%3Fautofill_url%3D" . urlencode( WP_PLUGIN_URL ) . "%2F&response_type=code' target='_blank'>", '</a>' )
				?>
				<?php slidedeck2_html_input( 'options[instagram_access_token]', $token, array( 'type' => 'password', 'label' => __( "Access Token", $namespace ) . '<span class="tooltip" title="' . $tooltip . '"></span>', array( 'size' => 40, 'maxlength' => 255 ), 'required' => true ) ); ?>
				<em class="note-below">Get your access token <a href="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=<?php echo $namespace; ?>_get_instagram_access_token&_wpnonce_get_instagram_access_token=<?php echo wp_create_nonce( $namespace . '-get-instagram-access-token' ); ?>" id="get-instagram-access-token-link">here</a>.</em>
            </li>
            <li>
				<?php $tooltip =  __( 'This can be your Username or another user\'s Username', $namespace ) ?>
			    <?php slidedeck2_html_input( 'options[instagram_username]', $slidedeck['options']['instagram_username'], array( 'label' => __( "Username", $namespace ) . '<span class="tooltip" title="' . $tooltip . '"></span>', array( 'size' => 20, 'maxlength' => 255 ) ) ); ?>
            </li>
        </ul>
    </div>
</div>