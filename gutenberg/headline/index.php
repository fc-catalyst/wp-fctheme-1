<?php

$block_name = 'headline'; // basename( __DIR__ ) //++add optins to print different h and or 

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) use ( $block_name ) {
        ob_start();

        ?>
<h1><?php is_category() ? single_cat_title( '<small>' . __( 'Blog', 'fct1' ) .':</small> ' ) : single_post_title() ?></h1>
        <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    };

    register_block_type( 'fct1-gutenberg/' . $block_name, [
        'editor_script' => 'fct1-' . $block_name . '-block',
        'render_callback' => $print_block
    ] );

    wp_register_script(
        'fct1-' . $block_name . '-block',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/block.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        FCT1S_VER . filemtime( __DIR__ . '/block.js' )
    );

});
