<?php

// run through text and fix the links number & attributes
function fct1_a_clear_all($text, $limit = -1, $force = [], $allowed_atts = [] ) { // ++rebuild with DOMDocument?

    $limit = is_numeric( $limit ) ? intval( $limit ) : -1; // refers to external links

    $allowed_atts = empty( $allowed_atts ) || !is_array( $allowed_atts )
        ? ['href', 'rel', 'target', 'title']
        : array_merge( ['href'], $allowed_atts ); // href is a must have for all links

    unset( $force['href'] );
    $defaults = [ // rules to treat some attributes
        'target' => [ // --keep && --remove can be applied here too, absent value is treated as --keep
            'internal' => '--keep', // --keep or !isset() is for keeping the existing attribute value
            'external' => '_blank'
        ],
        'rel' => [
            'internal' => '--remove', // --remove is for removing the attribute
            'external' => 'nofollow noopener noreferrer',
        ]
    ];
    $modified = $force + $defaults;
       
    $is_external = function($url) {
        $a = parse_url( $url );
        return !empty( $a['host'] ) && strcasecmp( $a['host'], $_SERVER['HTTP_HOST'] );
    };

    $format_attr = function($attr_name, $attr_value) {
        return ' '.$attr_name.'="'.htmlentities( $attr_value, ENT_COMPAT ).'"';
    };

    $count = $limit;
    // ++ would probably be faster and safer to perform with DOMDocument
    return preg_replace_callback( '/(<a\s+[^>]+>)(.*?)(<\/a>)/i', // go through all links' opening tags

        function( $link ) use ( $allowed_atts, $modified, $is_external, $format_attr, &$count ) {

            if ( !preg_match( '/href=([\'"])(.*?)\\1/i', $link[1], $href ) ) {
                return $link[2]; // a with no href attr
            }

            if ( $external = $is_external( $href[2] ) ) {
                if ( $count === 0 ) { return $link[2]; } // print only the text of the link
                $count--;
            }

            $extint = $external ? 'external' : 'internal';

            preg_match_all( '/\s*([\w\d\-\_]+)=([\'"])(.*?)\\2/i', $link[1], $atts, PREG_SET_ORDER );
            $att_val = [];
            foreach ( $atts as $v ) { $att_val[ $v[1] ] = $v[3]; }

            $result = ''; // collect the attributes, formatted with $format_attr()
            foreach ( $allowed_atts as $v ) {

                $obligate = null;
                if ( isset( $modified[ $v ] ) ) {
                    if ( is_array( $modified[ $v ] ) ) {
                        $obligate = isset( $modified[ $v ][ $extint ] )
                            ? $modified[ $v ][ $extint ]
                            : $obligate;
                    } else {
                        $obligate = $modified[ $v ];
                    }
                }

                // remove the attribute
                if ( $obligate === '--remove' ) { continue; }

                // keep as is initially
                if ( !isset( $obligate ) || $obligate === '--keep' ) {
                    if ( !isset( $att_val[ $v ] ) ) { continue; }
                    $result .= $format_attr( $v, $att_val[ $v ] );
                    continue;
                }

                // override the value with the obligatory
                $result .= $format_attr( $v, $obligate );

            }

            return '<a' . $result . '>' . $link[2] . $link[3];

        },
        $text
    );
    
   
    return $result;
}

function fct1_html_words_limit($html, $limit) {

    $count = -1;
    $offset = 0;

    preg_replace_callback( '/>([^<]+)</i',
        function( $text ) use ( $html, $limit, &$count, &$offset ) {

            if ( $count === $limit ) { return $text[0]; }

            $offset = strpos( $html, $text[1], $offset );

            $count_local = fct1_count_words( $text[1] );
            $offset_local = 0;

            if ( $count_local > 0 && $count + $count_local > $limit ) { // track the offset inside local range
                $words = explode( ' ', trim(  preg_replace( ['/\&nbsp;/', '/\s+/'], ' ', $text[1] ) ) );
                for ( $i = 0; $i < $limit - $count; $i++ ) {
                    $offset_local = strpos( $text[0], $words[ $i ], $offset_local );
                }

                $count = $limit;
                $offset += $offset_local + strlen( $words[ $i - 1 ] );
                return $text[0];
            }

            $count += $count_local;
            $offset = $offset + strlen( $text[1] );

            return $text[0];
         }, '>' . $html . '<' // to match the regexp if the text starts with plane
    );

    if ( $count !== $limit ) { return $html; }

    return fct1_html_fix( trim( substr( $html, 0, $offset ) ) . '&hellip;' );
}

function fct1_html_to_text($a) {
    $a = preg_replace( '/</', ' <', $a ); // possible gaps to spaces
    $a = preg_replace( '/<(head|script|style|template)[^>]*>(?:.*?)<\/\1>/si', ' ', $a ); // remove the hidden tags
    $a = strip_tags( $a );
    $a = preg_replace( ['/\&nbsp;/', '/\s+/'], ' ', $a );
    $a = trim( $a );
    return $a;
}

/*
function fct1_html_fix($html) { // it fixex, but adds a starting tag, if starts with a plain text, though
    $dom = new DOMDocument();
    $dom->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
    return $dom->saveHTML();
}
//*/
function fct1_html_fix($html) {
    $dom = new DOMDocument();
    $dom->loadHTML( mb_convert_encoding( $html, 'HTML-ENTITIES', 'UTF-8' ) );
    $dom->getElementsByTagName( 'body' )->item(0)->nodeValue;
    return trim( substr( $dom->saveHTML( $dom->getElementsByTagName( 'body' )->item(0) ), 6, -7 ) );
}

function fct1_count_words($text) {
    //return str_word_count( $text ); // it just fails counting properly and stabelly
    return substr_count( fct1_html_to_text( $text ), ' ' ) + 1; // counting spaces
}