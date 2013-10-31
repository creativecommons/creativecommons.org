<?php
class SlideDeckLens_BlockTitle extends SlideDeckLens_Scaffold {
    var $options_model = array(
        'Appearance' => array(
            'accentColor' => array(
                'value' => "#ffffff"
            ),
            'titleFont' => array(
                'value' => "oswald"
            ),
            'hideSpines' => array(
                'type' => 'hidden',
                'value' => true
            )
		)
    );
}
