<?php

defined( 'ABSPATH' ) || exit;

foreach ( scandir( __DIR__ ) as $v ) {
    if ( $v == '.' || $v == '..' || $v[0] === '-' ) { continue; }
    @include_once( __DIR__ . '/' . $v . '/index.php' );
}