<?php

add_action( 'init', function() {

	wp_register_script(
		'fct1-basic-block',
		get_template_directory_uri() . '/gutenberg/'. basename( __DIR__ ) . '/block.js',
		[ 'wp-blocks', 'wp-element', 'wp-block-editor' ],
		FCT1S_VER . filemtime( __DIR__ . '/block.js' ),
		true
	);
    
    register_block_type( 'fct1-gutenberg/basic', [ 'editor_script' => 'fct1-basic-block' ] );

});
