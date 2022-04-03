<?php

// this file contains the initial settings sample for the theme

define( 'FCT1S', [
    'prefix'         => 'fct1' . '-',
    'dev'            => $fct1_dev ? time() : '',
    'gmap_api_key'   => '',
    'fonts_external' => '',
    
]);

define( 'FCT1S_VER', wp_get_theme()->get( 'Version' ) . FCT1S['dev'] );