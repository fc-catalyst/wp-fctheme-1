<?php

// clear cloudflare cached page on unpublish, as some clients are angry about not unpublishing immediately
//* doesn't really always work.. maybe cloudflare has a queue.. most probably
// ++add flushing on an entity publish
add_action( 'transition_post_status', function($new_status, $old_status, $post) {

    if ( !class_exists( '\CF\WordPress\Hooks' ) ) { return; }
    if ( $old_status !== 'publish' || $new_status === 'publish' ) { return; } // unpublish

    $cf = new \CF\WordPress\Hooks;
    //$cf->purgeCacheByRelevantURLs( $post->ID );
    $cf->purgeCacheEverything(); // to flush the archives too

}, 10, 3 );
//*/

// fix the gap, caused by wp-container-{ID}
remove_filter( 'render_block', 'wp_render_layout_support_flag', 10, 2 );
remove_filter( 'render_block', 'gutenberg_render_layout_support_flag', 10, 2 );


// rankmath crutches
add_filter( 'rank_math/sitemap/enable_caching', '__return_false' );

// sitemap for doctors
add_filter( 'rank_math/sitemap/post_type_archive_link', function( $archive_url, $post_type ){
	return $post_type === 'doctor' ? false : $archive_url;
}, 10, 2 );

// rankmath breadcrumbs for doctors
add_filter( 'rank_math/frontend/breadcrumb/items', function( $crumbs, $class ) {
	if ( strpos( $crumbs[1][1], '/aerzte/' ) !== false ) {
		$crumbs[1][0] = 'Kliniken';
		$crumbs[1][1] = str_replace( '/aerzte/', '/kliniken/', $crumbs[1][1] );
	}
	return $crumbs;
}, 10, 2);