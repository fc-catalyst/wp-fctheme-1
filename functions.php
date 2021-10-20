<?php

define( 'FCT1_', [
    'PREFIX'  => 'fct1',
    'D' => time() // develope mode: time() or ''
]);


// add styles
add_action( 'wp_enqueue_scripts', function() { // ++try get_footer, if GInsights reacts better

    $enqueue_dir = get_template_directory() . '/assets/styles/';
    $enqueue_url = get_template_directory_uri() . '/assets/styles/';
    $enqueue_files = array_merge( ['fonts'], fct1_get_style_files() );

    foreach ( $enqueue_files as $v ) {
        if ( !is_file( $enqueue_dir . $v . '.css' ) ) { continue; }

        wp_enqueue_style(
            FCT1_['PREFIX'] . '-' . $v,
            $enqueue_url . $v . '.css',
            [FCT1_['PREFIX'] . '-' . 'style'], // after the main one
            wp_get_theme()->get( 'Version' ) . FCT1_['D'],
            'all'
        );

    }
    
    // main css & js
    wp_enqueue_style( FCT1_['PREFIX'] . '-' . 'style',
    	get_template_directory_uri() . '/style.css',
    	[],
        wp_get_theme()->get( 'Version' ) . FCT1_['D'],
        'all'
    );
    wp_enqueue_script( FCT1_['PREFIX'] . '-' . 'common',
		get_template_directory_uri() . '/assets/common.js',
		[ 'jquery' ],
		wp_get_theme()->get( 'Version' ) . FCT1_['D'],
		1
	);

});

add_action( 'wp_head', function() { // include the first-screen styles, instead of enqueuing
?><style><?php
    $include_dir = get_template_directory() . '/assets/styles/first-screen/';
    $include_files = array_merge( ['style'], fct1_get_style_files() );

    foreach ( $include_files as $v ) {
        if ( !is_file( $include_dir . $v . '.css' ) ) { continue; }

        if ( FCT1_['D'] ) {
            echo "\n\n".'/*---------- '.$v.'.css ----------*'.'/'."\n";
            include_once( $include_dir . $v . '.css' );
            continue;
        }
        
        ob_start();
        @include_once( $include_dir . $v . '.css' );
        $content = ob_get_contents();
        ob_end_clean();

        $content = preg_replace( '/\s+/', ' ', $content );
        $content = preg_replace( '/ ?([\{\};:]) ?/', '$1', $content );
        $content = preg_replace( '/\/\*(.*?)*\*\//', '', $content );
        //++(space and space)
        //++space>space, space~space, space+space
        //++replace empties like .aaa{}
        $content = trim( $content );

        echo $content;
    }
?></style><?php

@include_once( __DIR__ . '/fonts_link' );

}, 7 );

function fct1_get_style_files() {
    static $files = null;
    if ( $files !== null ) { return $files; }

    // get post type
    $qo = get_queried_object();
    if ( !is_object( $qo ) ) { return []; }
    if ( get_class( $qo ) === 'WP_Post_Type' ) {
        $post_type = $qo->name;
    }
    if ( get_class( $qo ) === 'WP_Post' ) {
        $post_type = $qo->post_type;
    }

    $files = [];
    if ( is_singular( $post_type ) ) {
        $files[] = $post_type;
    }
    if ( is_front_page() ) {
        $files[] = 'front-page';
    }
    if ( is_home() || is_archive() && ( !$post_type || $post_type === 'page' ) ) {
        $files[] = 'archive-post';
    }
    if( is_post_type_archive( $post_type ) ) {
        $files[] = 'archive-' . $post_type;
    }
    
    return $files;
}



/* theme settings */

// fixed header on
add_filter( 'body_class', function ($classes) {
    $classes[] = 'header-fixed';
    return $classes;
});

// menu & thumbnails
add_action( 'init', function() {

	register_nav_menus( [
		'main' => 'Main Menu',
		'logged' => 'Main Menu Logged',
	]);
	
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

});

// special menu links
add_filter( 'wp_nav_menu_objects', function ($items) {
	foreach ( $items as &$item ) {
        if ( $item->url === '#logout' ) {
            $item->url = wp_logout_url();
        }
        if ( $item->url === '#profile' ) {
            $item->url = get_edit_profile_url();
        }
	}
	return $items;
}, 10 );

// remove 'Archive:'
add_filter( 'get_the_archive_title', function($title) {
	if ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}else{
        global $post;
        $title = $post->post_title;
	}
	return $title;
});

// fix excerpt
add_filter( 'excerpt_more', function($more) {
    return '';
});
add_filter( 'excerpt_length', function($number) {
    return 18;
} );

// print featured image
add_action( 'wp_head', function() {
    $page_id = get_queried_object_id();
    if ( has_post_thumbnail( $page_id ) ) {
        $img = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'full' )[0];
        echo '<style>:root{--featured-image:url("'.$img.'");</style>'."\n";
    }

}, 6 );


/* economy */

// disable creating default sizes on upload
add_action( 'intermediate_image_sizes_advanced', function($sizes) {

    unset(
        $sizes['medium'],
        $sizes['large'],
        $sizes['medium_large'],
        $sizes['1536x1536'],
        $sizes['2048x2048']
    );
	return $sizes;

});
// disable displaying default sizes in admin
add_action( 'admin_print_styles', function() {

    $screen = get_current_screen();

    if( $screen->id !== 'options-media' ) {
        return;
    }

  ?>
    <style>
        #wpbody-content form > table:first-of-type tr:nth-of-type( 2 ),
        #wpbody-content form > table:first-of-type tr:nth-of-type( 3 ) {
            display: none;
        }
    </style>
  <?php

} );

// disable emoji
add_action( 'init', function() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', function( $plugins ) {
        if ( is_array( $plugins ) ) {
            return array_diff( $plugins, ['wpemoji'] );
        } else {
            return [];
        }
    });
});

// remove jquery migrate
add_action( 'wp_default_scripts', function ( $scripts ) {
    if ( !is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, ['jquery-migrate'] );
        }
    }
} );



// custom post type for global and reusable sections, like footer
add_action( 'init', function() {
	$labels = [
		'name'                => 'Sections',
		'singular_name'       => 'Section',
		'menu_name'           => 'Sections',
		'all_items'           => 'All sections',
		'view_item'           => 'View Section',
		'add_new_item'        => 'Add New Section',
		'add_new'             => 'Add New',
		'edit_item'           => 'Edit Section',
		'update_item'         => 'Update Section',
		'search_items'        => 'Search Section',
		'not_found'           => 'Section not found',
		'not_found_in_trash'  => 'Section not found in Trash',
	];
	$args = [
		'label'               => 'fct-section',
		'description'         => 'Global and reusable sections using Gutenberg editor for styling (footer)',
		'labels'              => $labels,
		'supports'            => [
									'title',
									'editor',
								],
		'hierarchical'        => false,
		'public'              => false,
		'show_in_rest'        => true, // turn on Gutenberg
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-schedule',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	];
	register_post_type( $args['label'], $args );
});

// gutenberg full-width & wide blocks support (wide is full-width background and boxed content)
add_action( 'after_setup_theme', function() {
    add_theme_support( 'align-wide' );
});

// admin additional styling for the theme
add_action( 'admin_init', function() {
	wp_admin_css_color(
		'klinikerfahrungen',
		'Klinikerfahrungen',
		get_template_directory_uri() . '/assets/styles/style-admin.css',
		[ '#0b4562', '#89cad6', '#fff', '#fff', '#fff', '#fda7a7' ]
		//[ '#0b4562', '#23667b', '#4699a9', '#89cad6', '#ffffff', '#fda7a7' ]
	);
	/*
    if ( !current_user_can( '{ROLE}' ) ) { // hide the option to pick a different color scheme
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    }
    //*/
});
/*
add_action( 'user_register', function ($user_id) { // set new theme to all newly registered users
    wp_update_user([
        'ID' => $user_id,
        'admin_color' => 'klinikerfahrungen'
    ]);
});
//*/

add_shortcode( 'fc-year', function() { // always demanded by copyright XD
    return date( 'Y' );
});


/* useful functions */

// images on the fly
function fct1_image_print( $img_id_src = 0, $size = 'full', $crop = false, $alt = '' ) {
    $img = fct1_image_src( $img_id_src, $size, $crop );
    if ( !$img ) { return; }
    
    ?><img src="<?php echo $img[0] ?>" width="<?php echo $img[1] ?>" height="<?php echo $img[2] ?>" alt="<?php echo $alt ?>" loading="lazy" /><?php
}

function fct1_image_src( $img_id_src = 0, $size = 'full', $crop = false ) { // src starts from after ..uploads/

    if ( is_int( $img_id_src ) ) {
        $img_id_src = explode( '/wp-content/uploads/', wp_get_attachment_image_src( $img_id_src )[0] )[1];
    }

    if ( is_string( $img_id_src ) ) {

        $return = function($src) use (&$path, &$url, &$src_size) {
            
            if ( $new_size = $src_size ? $src_size : getimagesize( $path . $src ) ) {
                return [
                    $url . $src,
                    $new_size[0],
                    $new_size[1]
                ];
            }
            return [ $url . $src ];
        };

        list( 'path' => $path, 'url' => $url ) = wp_get_upload_dir();
        $src = '/' . trim( $img_id_src, '/' );


        if ( !is_file( $path . $src ) ) { return; } // no source file
        // can unlink the thumbnails if no source file, or keep it separately
        if ( !is_array( $size ) || !is_int( $size[0] ) || !is_int( $size[1] ) ) { return $return( $src ); }

        if ( $src_size = getimagesize( $path . $src ) ) { // make only smaller variants
            if ( $src_size[0] <= $size[0] && $src_size[1] <= $size[1] ) { return $return( $src ); }
        }
        unset( $src_size );
        
        $path_split = pathinfo( $src );
        $desired_src =  $path_split['dirname'] .
                        ( $path_split['dirname'] == '/' ? '' : '/' ) .
                        $path_split['filename'] .
                        '-' . $size[0] . 'x' . $size[1] . 'c' . // c is for custom - to clear the files when needed
                        '.' . $path_split['extension']
                    ;

        if ( is_file( $path . $desired_src ) ) {
            return $return( $desired_src );
        }

        // create new image
        $edit_img = wp_get_image_editor( $path . $src );
        if ( is_wp_error( $edit_img ) ) { return $return( $src ); }

        $edit_img->resize( $size[0], $size[1], $crop );
        $edit_img->save( $path . $desired_src );

        return $return( $desired_src );
        
    }

}

function fct1_meta($name = '', $before = '', $after = '') {
    static $a = []; // collect all the values for further reuse
    if ( !$name ) { return; }

    $id = get_the_ID();

    if ( !isset( $a[ $id ] ) ) {
        $a[ $id ] = get_post_meta( $id, '' );
    }

    $v = $a[ $id ][ $name ][0];
    if ( is_serialized( $v ) ) {
        $v = unserialize( $v );
    } else {
        $v = trim( $v ) ? $before . $v . $after : '';
    }
    //$a[ $id ][ $name ][0] = $v;

    return $v;
}

function fct1_meta_print($name = '', $return = false, $before = '', $after = '') {
    if ( $return ) {
        return fct1_meta( $name, $before, $after );
    }
    echo fct1_meta( $name, $before, $after );
}

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


// load scripts async, like fcLoadScriptVariable(path.js, varname, success(), load after vars []);
add_action( 'wp_head', 'fcLoadScriptVariable', 0 );
add_action( 'admin_head', 'fcLoadScriptVariable', 0 );
function fcLoadScriptVariable() { ?>
<script type="text/javascript">
(function() {
    let load = [],
        paths = [], // for faster search
        interval = function(){},
        timer = setTimeout( function(){} ),
        tumbler = false;

    function init( path = '', variable = '', func = function(){}, dependencies = [], css = false ) {
        if ( !path && !variable ) { return }
        load.push( { p : path, v : variable, f : func, d : dependencies, c : css } );
        start();
    }

    function start() {
        if ( !tumbler ) {
            interval = setInterval( process, 300 );
            tumbler = true;
        }
        clearTimeout( timer );
        timer = setTimeout( stop, 20000 );
    }
    function stop() {
        clearInterval( interval );
        tumbler = false;
        clearTimeout( timer );
    }

    function loaded(e) {
        paths[ e.target.getAttribute( 'data-path' ) ] = 2;
    }
    
    function process() {

        if ( !load.length ) { stop(); return }
        if ( !( document.readyState === 'complete' || document.readyState === 'interactive' ) ) { return }
        
        const setAtts = function(tag, atts = {}, onload = false) {
            let el = document.createElement( tag );
            for ( let k in atts ) {
                el.setAttribute( k, atts[k] );
            }
            if ( atts.src && onload && typeof onload === 'function' ) {
                el.addEventListener( 'load', onload );
            }
            document.head.appendChild( el );
        };

        mainloop: for ( let k in load ) {

            // start loading only after dependencies global variables appear
            for ( let i = 0, j = load[k].d.length; i < j; i++ ) {
                if ( typeof window[ load[k].d[i] ] === 'undefined' ) { continue mainloop }
            }

            // load js & css paths
            if ( load[k].p && !paths[ load[k].p ] ) {

                paths[ load[k].p ] = 1; // 1 = tag added, 2 = path loaded

                setAtts(
                    'script',
                    {
                        'type' : 'text/javascript',
                        'src' : load[k].p,
                        'data-path' : load[k].p,
                        'async' : ''
                    },
                    loaded
                );

                let css = load[k].c === true ? load[k].p.replace( '.js', '.css' ) : load[k].c;
                if ( css ) {
                    setAtts( 'link', { 'type' : 'text/css', 'href' : css, 'rel' : 'stylesheet' } );
                }
            }

            // path is loaded
            if ( load[k].p && paths[ load[k].p ] !== 2 ) { continue }

            // variable is loaded
            if ( load[k].v && typeof window[ load[k].v ] === 'undefined' ) { continue }

            load[k].f();
            load.splice(k, 1);
        }
    }

    window.fcLoadScriptVariable = init;

})();
window.fcGmapKey='<?php @include_once( __DIR__ . '/gmaps_api_key' ) ?>';</script>
<?php }
