<?php

$block_name = 'tile-one'; // basename( __DIR__ )

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) use ( $block_name ) {
        ob_start();

        ?>
        <div class="fct1-tile-one<?php echo $props['numbered'] ? ' numbered' : '' ?>">
            <?php echo $props['url'] ? '<a href="'.$props['url'].'"></a>' : '' ?>
            <?php echo wp_get_attachment_image( $props['mediaID'], 'full' ) ?>
            <?php echo $props['excerpt'] ? '<p'.($props['color1']?' style="color:'.$props['color1'].'"':'').'>'.$props['excerpt'].'</p>' : '' ?>
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

add_action( 'wp_enqueue_scripts', function() use ( $block_name ) { // ++add first screen option

    if ( !has_block( 'fct1-gutenberg/' . $block_name ) ) { return $content; }

    wp_enqueue_style( 'fct1-' . $block_name,
        get_template_directory_uri() . '/gutenberg/' . $block_name . '/style.css',
        false,
        FCT1S_VER . filemtime( __DIR__ . '/style.css' ),
        'all'
    );

    return $content;
});