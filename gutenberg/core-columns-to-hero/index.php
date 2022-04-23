<?php

add_action( 'enqueue_block_editor_assets', function() {
	wp_enqueue_script(
        'fcpgb-columns-hero',
        get_template_directory_uri() . '/gutenberg/'. basename( __DIR__ ) . '/block.js',
        ['wp-edit-post'],
        FCT1S_VER
    );
});
// ++ can load syles dynamically after checking with has_block() and parse_blocks()