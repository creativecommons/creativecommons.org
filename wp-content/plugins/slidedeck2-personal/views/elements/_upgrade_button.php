<div class="upgrade-button-cta renewal-state-<?php echo $renewal_message['type']; ?>" data-nonce="<?php echo wp_create_nonce( 'slidedeck-check-license-status' ); ?>"  data-context="<?php echo $context; ?>">
    <span class="message"><?php echo $values['message_text']; ?></span>
    <a href="<?php echo $values['cta_url']; ?>&referrer=<?php echo urlencode( "Upgrade Button ({$values['context']})" ); ?>" class="upgrade-button"><span class="button-noise"><span><?php echo $values['cta_text']; ?></span></span></a>
</div>