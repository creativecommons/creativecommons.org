<?php
class SlideDeckLens_Video extends SlideDeckLens_Scaffold {
    var $options_model = array(
        'Appearance' => array(
            'accentColor' => array(
                'value' => "#f9e836"
            ),
            'hideSpines' => array(
                'type' => 'hidden',
                'value' => true
            )
        )
    );
}
