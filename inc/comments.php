<?php

// remove the url field
add_filter( 'comment_form_default_fields', function($fields) {
    unset( $fields['url'] );
    return $fields;
});

// replace labels with placeholders
add_filter( 'comment_form_defaults', function($d) {

    $label_to_placeholder = function($t, $p = '') {
        if ( !$p ) {
            preg_match( '/<label[^>]*>(.*)<\/label>/', $t, $label );
            $p = strip_tags( $label[1] );
        }
        $t = preg_replace( '/<label(.?)*<\/label>/', '', $t );
        $t = preg_replace( '/<(input|textarea)/', '\\0 placeholder="'.$p.'"', $t );
        return $t;
        
    };

    $d['fields']['author'] = $label_to_placeholder( $d['fields']['author'] );
    $d['fields']['email'] = $label_to_placeholder( $d['fields']['email'] );
    $d['comment_field'] = $label_to_placeholder( $d['comment_field'] );

    return $d;
});

// fields order change
add_filter( 'comment_form_fields', function($fields) {
    $order = array_flip( ['author', 'email', 'comment'] );
    uksort( $fields, function($a, $b) use ($order) {
        if ( isset( $order[ $a ] ) && isset( $order[ $b ] ) ) {
            return $order[ $a ] > $order[ $b ] ? 1 : -1;
        }
        return isset( $order[ $b ] ); // missing values go to the end
    });
    return $fields;
});