<?php
// a list of commonly used helpful shortcodes

add_shortcode( 'fc-year', function() { // always demanded by copyright XD
    return date( 'Y' );
});

add_shortcode( 'fc-logout-link', function($atts = []) { // can't just make a static link due to a nonce hash
    $allowed = [
        'text' => __( 'Logout' ),
    ];
    $atts = shortcode_atts( $allowed, $atts );
    
    return '<a href="'.wp_logout_url().'">'.$atts['text'].'</a>';
});