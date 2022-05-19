<?php

$block_name = 'post-details'; // basename( __DIR__ ) //++add optins to print different h and or.. maybe limit to sections type

add_action( 'init', function() use ( $block_name ) {

    $print_block = function( $props, $content = null ) use ( $block_name ) {

        $post = get_queried_object();
    
        $cats = get_the_category( $post->ID );
        $print_cats = [];
        if ( is_array( $cats ) ) {
            foreach ( $cats as $v ) {
                $print_cats[] = '<a href="'.esc_url( get_category_link( $v ) ).'">'.esc_html( $v->name ).'</a>';
            }
        }
    
        ob_start();

        ?>
<div class="entry-details">
    <span class="entry-date" itemprop="datePublished" content="<?php echo get_the_date( 'Y-m-d', $post ) ?>">
        <?php echo get_the_date( '', $post ) ?>
    </span>
    <?php echo $print_cats[0] ? ' | <span class="entry-categories">'.implode(', ',$print_cats).'</span>' : '' ?>
    <div class="entry-author">
        <?php printf( __( 'by %s', 'fct1' ), get_the_author_meta( 'display_name', $post->post_author ) ) ?>
    </div>
</div>
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
