<?php

add_action( 'after_setup_theme', function() {

    add_theme_support( 'align-wide' ); // gutenberg full-width and wide blocks

    add_theme_support( 'editor-color-palette', [ // custom gutenberg colors
        [
            'name'  => __( 'Dark', 'fct1' ) . ' 1',
            'slug'  => 'fct1-dark-1',
            'color' => '#23667b',
        ],
        [
            'name'  => __( 'Dark', 'fct1' ) . ' 2',
            'slug'  => 'fct1-dark-2',
            'color' => '#277888',
        ],
        [
            'name'  => __( 'Dark', 'fct1' ) . ' 3',
            'slug'  => 'fct1-dark-3',
            'color' => '#58acbc',
        ],
        [
            'name'  => __( 'Dark', 'fct1' ) . ' 4',
            'slug'  => 'fct1-dark-4',
            'color' => '#0087a0',
        ],
        [
            'name'  => __( 'Dark', 'fct1' ) . ' 5',
            'slug'  => 'fct1-dark-5',
            'color' => '#dfc082',
        ],
        [
            'name'  => __( 'Light', 'fct1' ) . ' 1',
            'slug'  => 'fct1-light-1',
            'color' => '#87c8d3',
        ],
        [
            'name'  => __( 'Warning', 'fct1' ) . ' 1',
            'slug'  => 'fct1-warning-1',
            'color' => '#fda7a7',
        ],
        [
            'name'  => __( 'White', 'fct1' ),
            'slug'  => 'white',
            'color' => '#fff',
        ],
        [
            'name'  => __( 'Black', 'fct1' ),
            'slug'  => 'black',
            'color' => '#000',
        ],
        [
            'name'  => __( 'Grey', 'fct1' ) . ' 1',
            'slug'  => 'fct1-grey-1',
            'color' => '#22262c',
        ],
        [
            'name'  => __( 'Grey', 'fct1' ) . ' 2',
            'slug'  => 'fct1-grey-2',
            'color' => '#2f3339',
        ],
        [
            'name'  => __( 'Grey', 'fct1' ) . ' 3',
            'slug'  => 'fct1-grey-3',
            'color' => '#2c3538',
        ],
        [
            'name'  => __( 'Grey', 'fct1' ) . ' 4',
            'slug'  => 'fct1-grey-4',
            'color' => '#d3d7da',
        ],
    ]);

    add_theme_support( 'editor-gradient-presets', [ // custom gradients
        [
            'name'     => __( 'Gradient', 'fct1' ) . ' 1',
            'gradient' => 'linear-gradient(60deg, #277888 10%, #58acbc 90%)',
            'slug'     => 'fct1-gradient-1'
        ],
        [
            'name'     => __( 'Gradient', 'fct1' ) . ' 2',
            'gradient' => 'linear-gradient(240deg, #fce0a9 20%, #d3af69 90%)',
            'slug'     => 'fct1-gradient-2'
        ]
    ]);
    add_action( 'admin_print_styles', function() { // custom gradients to work properly in the back-end
    ?>
<style>
    .has-fct1-gradient-1-gradient-background { background:linear-gradient(60deg, #277888 10%, #58acbc 90%) }
    .has-fct1-gradient-2-gradient-background { background:linear-gradient(240deg, #fce0a9 20%, #d3af69 90%) }
</style>
    <?php
    });
    
    
    add_theme_support( 'editor-font-sizes', [ // custom fonts sizes
        [
            'name'      => '12',
            'shortName' => '12',
            'size'      => 12,
            'slug'      => 'f12'
        ],
        [
            'name'      => '15',
            'shortName' => '15',
            'size'      => 15,
            'slug'      => 'f15'
        ],
        [
            'name'      => '16',
            'shortName' => '16',
            'size'      => 16,
            'slug'      => 'f16'
        ],
        [
            'name'      => '18',
            'shortName' => '18',
            'size'      => 18,
            'slug'      => 'f18'
        ],
        [
            'name'      => '20',
            'shortName' => '20',
            'size'      => 20,
            'slug'      => 'f20'
        ],
        [
            'name'      => '22',
            'shortName' => '22',
            'size'      => 22,
            'slug'      => 'f22'
        ],
        [
            'name'      => '26',
            'shortName' => '26',
            'size'      => 26,
            'slug'      => 'f26'
        ],
        [
            'name'      => '30',
            'shortName' => '30',
            'size'      => 30,
            'slug'      => 'f30'
        ],
        [
            'name'      => '32',
            'shortName' => '32',
            'size'      => 32,
            'slug'      => 'f32'
        ],
        [
            'name'      => '42',
            'shortName' => '42',
            'size'      => 42,
            'slug'      => 'f42'
        ],
        [
            'name'      => '46',
            'shortName' => '46',
            'size'      => 46,
            'slug'      => 'f46'
        ],

    ]);
    
});