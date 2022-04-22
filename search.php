<?php

get_header();

?>
    <div class="wrap-width">
    <h1><?php _e( 'Search results for', 'fct1' ) ?>
        <small> <?php the_search_query() ?></small></h1>
    <div style="height:40px;width:100%" aria-hidden="true" class="wp-block-spacer"></div>
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        get_template_part( 'template-parts/search', 'tile' );

    endwhile;
    get_template_part( 'template-parts/pagination' );
endif;

?>
    </div>
    <div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div>
<?php

get_footer();
