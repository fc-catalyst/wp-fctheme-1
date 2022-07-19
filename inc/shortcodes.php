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

add_shortcode( 'rank_math_breadcrumb--global', function() { // return the rank math breadcrumbs for the main loop
    if ( !function_exists('rank_math_the_breadcrumbs') ) { return; }
    ob_start();

    wp_reset_postdata();
    rank_math_the_breadcrumbs();

    //++-- can restore the current query here, but was never demanded

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
});