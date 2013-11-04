<?php do_action( "{$this->namespace}_lens_selection_before_lenses", $lenses, $slidedeck ); ?>

<?php foreach( $lenses as &$lens ): ?>
    <label class="lens<?php if( $lens['slug'] == $slidedeck['lens'] ) echo ' selected'; ?>">
        <span class="thumbnail"><img src="<?php echo $lens['thumbnail']; ?>" alt="<?php echo $lens['meta']['name']; ?>" /></span>
        <span class="shadow">&nbsp;</span>
        <span class="title"><?php echo $lens['meta']['name']; ?></span>
        <input type="radio" name="lens" value="<?php echo $lens['slug']; ?>"<?php if( $lens['slug'] == $slidedeck['lens'] ) echo ' checked="checked"'; ?> />
    </label >
<?php endforeach; ?>

<?php do_action( "{$this->namespace}_lens_selection_after_lenses", $lenses, $slidedeck ); ?>

<input type="hidden" name="_wpnonce_update_available_lenses" value="<?php echo wp_create_nonce( 'slidedeck-update-available-lenses' ); ?>" />
