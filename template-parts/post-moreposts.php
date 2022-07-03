<?php

// ++ move to the list posts plugin to not repeat styles so many times

$query = [
    'orderby'          => 'date',
    'order'            => 'DESC',
    'posts_per_page'   => '3',
    'paged'            => 1,
    'post_status'      => ['publish'],  
];

$cats = get_the_category( get_the_ID() );
if ( !empty( $cats ) ) {
    foreach ( $cats as $v ) {
        $query[ 'category__in' ][] = $v->cat_ID;
    }
}

$wp_query = new WP_Query( $query );

?><div class="entry-content wrap-width tmp-tiles"><?php

if ( $wp_query->have_posts() ) {
    while ( $wp_query->have_posts() ) {
        $wp_query->the_post();
        
        get_template_part( 'template-parts/post', 'tile' );
        
    }
}
wp_reset_query();

?></div><?php
