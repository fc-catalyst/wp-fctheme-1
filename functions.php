<?php

function fct_dev() {
    //return '';
    return time();
}

/* STYLES for the first-screen */
add_action( 'wp_head', function() {
?><style>
<?php

    $dir = get_template_directory() . '/assets/first-screen/';

    // main
    @include_once( $dir . '--main.css' );

    // front page
    if ( is_front_page() ) {
        @include_once( $dir . '--front-page.css' );
    }

    // post type and name or archieve type name
    $files = fct_load_styles();
    if ( $files ) {
        foreach( $files as $v ) {
            @include_once( $dir .  $v . '.css' );
        }
    }

?></style>
<?php
}, 7 );

/* add common styles */
add_action( 'wp_enqueue_scripts', function() { // try get_footer, if GInsights reacts better

    $dir = get_template_directory() . '/assets/styles/';
    $url = get_template_directory_uri() . '/assets/styles/';
    $main = 'fct1-style';
    $fonts = 'fct1-fonts';

    // main
    wp_enqueue_style( $main,
    	get_template_directory_uri() . '/style.css',
    	[],
        wp_get_theme()->get( 'Version' ) . fct_dev(),
        'all'
    );

    // scripts
    wp_enqueue_script( 'fct1-common',
		get_template_directory_uri() . '/assets/common.js',
		[ 'jquery' ],
		wp_get_theme()->get( 'Version' ) . fct_dev(),
		1
	);

    // fonts
    wp_enqueue_style( $fonts,
        $url . '--fonts.css',
        [],
        wp_get_theme()->get( 'Version' ) . fct_dev(),
        'all'
    );

	// front page
	if ( is_front_page() ) {
        wp_enqueue_style(
            $main . '-front-page',
            $url . '--front-page.css',
            [ $main, $fonts ],
            wp_get_theme()->get( 'Version' ) . fct_dev(),
            'all'
        );	
	}

	// post type and name or archieve type name
    $files = fct_load_styles();
    if ( empty( $files ) ) { return; }
    
    foreach( $files as $v ) {
        if ( !is_file( $dir . $v . '.css' ) ) { continue; }

        wp_enqueue_style(
            $main . $v,
            $url . $v . '.css',
            [ $main, $fonts ],
            wp_get_theme()->get( 'Version' ) . fct_dev(),
            'all'
        );
    }

});


function fct_load_styles() {
    // -{post-type}-list for archive, -{post-type} for posts, {slug} for particular posts
    $qo = get_queried_object();
    if ( !is_object( $qo ) ) { return; }

    $result = [];

    if ( get_class( $qo ) === 'WP_Post_Type' ) {
        $result[] = '-' . $qo->name . '-list'; // or can use slug to match the url: $go->rewrite->slug
    }

    if ( get_class( $qo ) === 'WP_Post' ) {
        $result[] = $qo->post_name; // slug
        $result[] = '-' . $qo->post_type; // post type name
    }

    return $result;
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
	]);
	
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

});

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
		get_template_directory_uri() . '/assets/styles/--style-admin.css',
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

// load scripts async, like fcLoadScriptVariable(path.js, varname, success(), depend vars []);
add_action( 'wp_head', function() { // ++do similar for styles to use in separate scripts
?>
<script type="text/javascript">!function(){let r={},c={},a={},o=function(){},d=!1;function u(){clearInterval(o),d=!1}window.fcLoadScriptVariable=function(t,e="",n=function(){},i=[]){!t||a[t]||c[t]||r[t]||(r[t]={var:e,fun:n,dpc:i},d||(o=setInterval(function(){!function(){if(!Object.keys(r).length)return u(),0;var t=document.readyState;if(t="complete"===t||"interactive"===t)t:for(var n in r){for(let t=0,e=r[n].dpc.length;t<e;t++)if(void 0===window[r[n].dpc[t]])continue t;if(!c[n]&&!a[n]){let t=document.createElement("script");t.setAttribute("type","text/javascript"),t.setAttribute("src",n),t.setAttribute("async",""),document.head.appendChild(t),c[n]=!0}r[n].var&&void 0===window[r[n].var]||(a[n]=!0,r[n].fun(),delete r[n],delete c[n])}}()},300),d=!0,setTimeout(u,2e4)))},window.fcLoadScriptVariable.state=function(){return d}}();</script>
        <?php
}, 0 );


