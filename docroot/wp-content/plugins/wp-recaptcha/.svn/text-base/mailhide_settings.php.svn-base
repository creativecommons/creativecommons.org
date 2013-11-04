<?php

    if (defined('ALLOW_INCLUDE') === false)
        die('no direct access');

?>

<a name="mailhide"></a>
<h2><?php _e('MailHide Options', 'recaptcha'); ?></h2>
<p><?php _e('One common misconception about MailHide is that it edits your email addresses on the database. This is false, your actual content is never actually modified. Instead, it is "filtered" such that it appears modified to the reader.', 'recaptcha'); ?></p>

<form method="post" action="options.php">
   <?php settings_fields('mailhide_options_group'); ?>

   <h3><?php _e('Authentication', 'recaptcha'); ?></h3>
   <p><?php _e('These keys are required before you are able to do anything else.', 'recaptcha'); ?> <?php _e('You can get the keys', 'recaptcha'); ?> <a href="http://mailhide.recaptcha.net/apikey" title="<?php _e('Get your reCAPTCHA API Keys', 'recaptcha'); ?>"><?php _e('here', 'recaptcha'); ?></a>.</p>
   <p><?php _e('Be sure not to mix them up! The public and private keys are not interchangeable!'); ?></p>
   
   <table class="form-table">
      <tr valign="top">
         <th scope="row"><?php _e('Public Key', 'recaptcha'); ?></th>
         <td>
            <input type="text" name="mailhide_options[public_key]" size="40" value="<?php echo $this->options['public_key']; ?>" />
         </td>
      </tr>
      <tr valign="top">
         <th scope="row"><?php _e('Private Key', 'recaptcha'); ?></th>
         <td>
            <input type="text" name="mailhide_options[private_key]" size="40" value="<?php echo $this->options['private_key']; ?>" />
         </td>
      </tr>
   </table>
   
   <h3><?php _e('General Options', 'recaptcha'); ?></h3>
   <table class="form-table">
      <tr valign="top">
         <th scope="row"><?php _e('Use MailHide in', 'recaptcha'); ?></th>
         <td>
            <input type="checkbox" id="mailhide_options[use_in_posts]" name="mailhide_options[use_in_posts]" value="1" <?php checked('1', $this->options['use_in_posts']); ?> />
            <label for="mailhide_options[use_in_posts]"><?php _e('Posts and Pages', 'recaptcha'); ?></label><br />
            
            <input type="checkbox" id="mailhide_options[use_in_comments]" name="mailhide_options[use_in_comments]" value="1" <?php checked('1', $this->options['use_in_comments']); ?> />
            <label for="mailhide_options[use_in_comments]"><?php _e('Comments', 'recaptcha'); ?></label><br />
            
            <input type="checkbox" id="mailhide_options[use_in_rss]" name="mailhide_options[use_in_rss]" value="1" <?php checked('1', $this->options['use_in_rss']); ?> />
            <label for="mailhide_options[use_in_rss]"><?php _e('RSS Feed of Posts and Pages', 'recaptcha'); ?></label><br />
            
            <input type="checkbox" id="mailhide_options[use_in_rss_comments]" name="mailhide_options[use_in_rss_comments]" value="1" <?php checked('1', $this->options['use_in_rss_comments']); ?> />
            <label for="mailhide_options[use_in_rss_comments]"><?php _e('RSS Feed of Comments', 'recaptcha'); ?></label><br />
         </td>
      </tr>
      
      <tr valign="top">
         <th scope="row"><?php _e('Target', 'recaptcha'); ?></th>
         <td>
            <input type="checkbox" id="mailhide_options[bypass_for_registered_users]" name="mailhide_options[bypass_for_registered_users]" value="1" <?php checked('1', $this->options['bypass_for_registered_users']); ?> />
            <label for="mailhide_options[bypass_for_registered_users]"><?php _e('Show actual email addresses to Registered Users who can', 'recaptcha'); ?></label>
            <?php $this->capabilities_dropdown(); ?>
         </td>
      </tr>
   </table>
   
   <h3><?php _e('Presentation', 'recaptcha'); ?></h3>
   <table class="form-table">
      <tr valign="top">
         <th scope="row"><?php _e('Replace Link With', 'recaptcha'); ?></th>
         <td>
            <input type="text" name="mailhide_options[replace_link_with]" size="70" value="<?php echo $this->options['replace_link_with']; ?>" />
         </td>
      </tr>
      
      <tr valign="top">
         <th scope="row"><?php _e('Replace Title With', 'recaptcha'); ?></th>
         <td>
            <input type="text" name="mailhide_options[replace_title_with]" size="70" value="<?php echo $this->options['replace_title_with']; ?>" />
         </td>
      </tr>
   </table>
   
   <h3><?php _e('Styling', 'recaptcha'); ?></h3>
   <p>You can style hidden emails using a variety of classes. Style the classes in your theme's stylesheet and be sure to clear any caches you might have to see the results.</p>
   
   <ul>
       <li><strong>.mh-email</strong> is assigned to the complete email</li>
       <li><strong>.mh-first</strong> is assigned to the first part of the email</li>
       <li><strong>.mh-middle</strong> is assigned to the middle of the email (the link)</li>
       <li><strong>.mh-last</strong> is assigned to the last part of the email</li>
   </ul>
   
   <p>The following is an example of the structure:</p>
   
   <code>
       &lt;span class=&quot;mh-email&quot;&gt; <br \>
       &nbsp;&nbsp;&nbsp; &lt;span class=&quot;mh-first&quot;&gt;jorg&lt;/span&gt; <br \>
       &nbsp;&nbsp;&nbsp; &lt;a href=&quot;url&quot; class=&quot;mh-middle&quot;&gt;...&lt;/a&gt; <br \>
       &nbsp;&nbsp;&nbsp; &lt;span class=&quot;mh-last&quot;&gt;@gmail.com&lt;/span&gt; <br \>
       &lt;/span&gt;
   </code>

   <p class="submit"><input type="submit" class="button-primary" title="<?php _e('Save MailHide Options') ?>" value="<?php _e('Save MailHide Changes') ?> &raquo;" /></p>
</form>