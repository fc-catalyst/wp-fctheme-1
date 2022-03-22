<?php

// run through text and fix the links attributes
function fct1_a_clear($text, $com = false, $targ = [], $rel = [], $atts = []) {

    $targ = $targ === false ? false : $targ + [
        'in' => '',
        'ex' => '_blank'
    ];

    $rel = $rel === false ? false : $rel + [
        'in' => '',
        'ex' => 'nofollow noopener noreferrer',
        'com' => 'noopener'
    ];

    $atts = $atts ? $atts : ['href', 'rel', 'target', 'title'];

    $is_ext = function($url) {
        $a = parse_url( $url );
        return !empty( $a['host'] ) && strcasecmp( $a['host'], $_SERVER['HTTP_HOST'] );
    };

    return preg_replace_callback(
        '/<a\s+[^>]+>/i',

        function( $m ) use ( $com, $targ, $rel, $atts, $is_ext ) {

            return preg_replace_callback(
                '/\s*([\w\d\-\_]+)=([\'"])(.*?)\\2/i',

                function( $m ) use ( $com, $targ, $rel, $atts, $is_ext ) {

                    $att = $m[1];
                    $val = $m[3];
                    $add_attr = '';

                    if ( !in_array( $att, $atts ) ) { return; }
                    
                    if ( $att === 'rel' ) { return $rel === false ? $m[0] : ''; }
                    if ( $att === 'target' ) { return $targ === false ? $m[0] : ''; }

                    if ( $att === 'href' ) {
                    
                        $ext = $is_ext( $val );

                        if ( in_array( 'rel', $atts ) ) {
                            $rel_new = $rel['in'];
                            if ( $ext ) {
                                $rel_new = $com ? $rel['com'] : $rel['ex'];
                            }
                            $add_attr .= $rel_new ? ' rel="'.$rel_new.'"' : '';
                        }

                        if ( in_array( 'target', $atts ) ) {
                            $targ_new = $ext ? $targ['ex'] : $targ['in'];
                            $add_attr .= $targ_new ? ' target="'.$targ_new.'"' : '';
                        }

                    }

                    return ' ' . $att . '="' . $val . '"' . $add_attr;

                },
                $m[0]
            );
        },
        $text
    );
    
   
    return $result;
}