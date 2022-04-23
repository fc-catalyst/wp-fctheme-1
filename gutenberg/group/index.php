<?php

$block_name = 'group'; // $block_name

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) {
        ob_start();

        ?>
            <div class="fct1-<?php echo $block_name ?>" data-rows="<?php echo $props['columns'] ? $props['columns'] : 2 ?>">
                <?php echo( $content ) ?>
            </div>
        <?php

        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    };

    register_block_type( 'fct1-gutenberg/' . $block_name, [
        'editor_script' => 'fct1-' . $block_name . '-block',
        'editor_style' => 'fct1-' . $block_name . '-editor',
        'render_callback' => $print_block
    ] );

    wp_register_script(
        'fct1-' . $block_name . '-block',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/block.js',
        ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components'],
        FCT1S_VER . filemtime( __DIR__ . '/block.js' )
    );
    
    wp_register_style(
        'fct1-' . $block_name . '-editor',
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/editor.css',
        ['wp-edit-blocks'],
        FCT1S_VER . filemtime( __DIR__ . '/editor.css' )
    );
});

add_action( 'wp_enqueue_scripts', function() use ( $block_name ) {

    if ( !has_block( 'fct1-gutenberg/' . $block_name ) ) { return $content; }

    wp_enqueue_style( 'fct1-' . $block_name,
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/style.css',
        false,
        FCT1S_VER . filemtime( __DIR__ . '/style.css' ),
        'all'
    );

    return $content;
});