<?php

$fct1_dev = true;

$fct1_settings_sample = is_file( __DIR__ . '/settings.php' ) ? '' : '-sample';

require __DIR__ . '/settings'.$fct1_settings_sample.'.php';
require __DIR__ . '/inc/styles-load.php';
require __DIR__ . '/inc/image-onthefly.php';
require __DIR__ . '/inc/text-filtering.php';
require __DIR__ . '/inc/shortcodes.php';
require __DIR__ . '/inc/comments.php';
require __DIR__ . '/inc/crutches.php';
require __DIR__ . '/gutenberg/index.php';
require __DIR__ . '/gutenberg/settings.php';


unset( $fct1_settings_sample, $fct1_dev );


/* translations */
add_action( 'after_setup_theme', function() {
    load_theme_textdomain( 'fct1', get_template_directory() . '/languages' );
});


/* theme settings */
add_action( 'after_setup_theme', function() {

    add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

    add_theme_support( 'custom-logo', [ // upload logo field for customizer
        'width'       => 700,
        'height'      => 160,
        'flex-width'  => true, // can't use simple svg support with strict rules here
        'flex-height' => true, // or here
        'header-text' => '',
        'unlink-homepage-logo' => true,
    ]);

});

// menu & thumbnails
add_action( 'init', function() {
	register_nav_menus( [
		'main' => 'Main Menu',
		'logged' => 'Main Menu Logged in',
	]);
});

// remove 'Archive: ' text from headline
add_filter( 'get_the_archive_title', function ($title) {
	if ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	}

	// ++argument the following
    global $post;
    return $post->post_title;
});

// excerpt
add_filter( 'excerpt_more', function($more) {
    return '';
});
add_filter( 'excerpt_length', function($number) {
    return 18;
});

// svg support
add_filter( 'upload_mimes', function($types) { // install a plugin, if meta data is needed for svg images (sizes)
    if ( !current_user_can( 'administrator' ) ) { return $types; }

    $types['svg'] = 'image/svg+xml';
    return $types;
}, 10, 2 );

/* solutions & crutches */

// special menu links, which transform from anchor to a back-end hashed links
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

/*
// print featured image url
add_action( 'wp_head', function() { // ++--
    $page_id = get_queried_object_id();
    if ( has_post_thumbnail( $page_id ) ) {
        $img = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'full' )[0];
        echo '<style>:root{--featured-image:url("'.$img.'")}</style>'."\n";
    }
}, 6 );
//*/

// hide the top gap behind the menu
add_action( 'wp_head', function() {
    $page_id = get_queried_object_id();
    if ( get_post_meta( $page_id, 'hide-top-gap', true ) ) {
        echo '<style>body::before{content:none}</style>'."\n";
    }
}, 8 );

/* economy */

// disable creating default sizes on upload, solved by inc/image-onthefly.php
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
    if( $screen->id !== 'options-media' ) { return; }

    ?>
    <style>
        #wpbody-content form > table:first-of-type tr:nth-of-type( 2 ),
        #wpbody-content form > table:first-of-type tr:nth-of-type( 3 ) {
            display: none;
        }
    </style>
    <?php
});

// disable emoji, just taken from somewhere
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

// remove jquery migrate, as nothing uses it
add_action( 'wp_default_scripts', function ( $scripts ) {
    if ( !is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, ['jquery-migrate'] );
        }
    }
} );


/* theme details */

// custom post type for global and reusable sections, like footer - uses Gutenberg for global sections' layouts
add_action( 'init', function() {
	$labels = [
		'name'                => __( 'Sections', 'fct1' ),
		'singular_name'       => __( 'Section', 'fct1' ),
		'menu_name'           => __( 'Sections', 'fct1' ),
		'all_items'           => __( 'All sections', 'fct1' ),
		'view_item'           => __( 'View Section', 'fct1' ),
        'add_new'             => __( 'Add New', 'fct1' ),
		'add_new_item'        => __( 'Add New Section', 'fct1' ),
		'edit_item'           => __( 'Edit Section', 'fct1' ),
		'update_item'         => __( 'Update Section', 'fct1' ),
		'search_items'        => __( 'Search Section', 'fct1' ),
		'not_found'           => __( 'Section not found', 'fct1' ),
		'not_found_in_trash'  => __( 'Section not found in Trash', 'fct1' ),
	];
	$args = [
		'label'               => 'fct-section',
		'description'         => __( 'Global sections, which use Gutenberg editor (footer, custom header)', 'fct1' ),
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

// add custom theme styling for admin-side
add_action( 'admin_init', function() {
	wp_admin_css_color(
		'klinikerfahrungen',
		'Klinikerfahrungen',
		get_template_directory_uri() . '/assets/styles/style-admin.css',
		[ '#0b4562', '#89cad6', '#fff', '#fff', '#fff', '#fda7a7' ]
	);
	/*
    if ( !current_user_can( '{ROLE}' ) ) { // hide the option to pick a different color scheme
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    }
    //*/
});

/* useful functions */

// operate meta fields in a my / better way
function fct1_meta($name = '', $before = '', $after = '') { // ++allow $name be an array for entity-add/templates/func..gmap
    static $a = []; // collect all the values for further re-use
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

    return $v;
}

function fct1_log($content, $dir = __DIR__) {
    if ( is_array( $content ) || is_object( $content ) ) {
        $content = print_r( $content, true );
    }
    return file_put_contents(
        $dir . '/log.txt',
        "\n" . '------------- ' . date( 'H:i:s (d.m.Y)' ) . ' -------------' . "\n" . $content . "\n\n",
        FILE_APPEND | LOCK_EX
    );
}


// print the loadings cript the highest
// ++ maybe load the key async and encoded.. but who cares as it appears streight in the script src anyways
add_action( 'wp_head', 'fcLoadScriptVariable', 0 );
add_action( 'admin_enqueue_scripts', 'fcLoadScriptVariable', 0 ); // not admin_head to run the highest
function fcLoadScriptVariable() {
?><script type="text/javascript">
<?php require __DIR__ . '/assets/fcLoadScriptVariable.'.( FCT1S['dev'] ? 'js' : 'min.js' ) ?>
window.fcGmapKey='<?php echo FCT1S['gmap_api_key'] ?>';
window.fcVer = '<?php echo FCT1S_VER ?>';
</script><?php
}


/*
 * Convert Rank Math FAQ Block Into Accordion - Option 2
 * https://rankmath.com/kb/turn-faq-block-into-accordion/
 */
add_action( 'wp_footer', function() { // ++make conditional, take idea from Voslamber
	?>
<script>
(function ($) {

	var rankMath = {
		accordion: function () {
			$('.rank-math-block').find('.rank-math-answer').hide();
			$('.rank-math-block').find('.rank-math-question').click(function () {  
				//Expand or collapse this panel
				$(this).nextAll('.rank-math-answer').eq(0).slideToggle('fast', function () {
					if ($(this).hasClass('collapse')) {
						$(this).removeClass('collapse');
					}
					else {
						$(this).addClass('collapse');
					}
				});
				//Hide the other panels
				$(".rank-math-answer").not($(this).nextAll('.rank-math-answer').eq(0)).slideUp('fast');
			});

			$('.rank-math-block .rank-math-question').click(function () {
				$('.rank-math-block .rank-math-question').not($(this)).removeClass('collapse');
				if ($(this).hasClass('collapse')) {
					$(this).removeClass('collapse');
				}
				else {
					$(this).addClass('collapse');
				}
			});
		}
	};

	rankMath.accordion();

})(jQuery);
</script>
	<?php
} );