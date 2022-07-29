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