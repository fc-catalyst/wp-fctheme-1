<?php

get_header();

?>
    <div class="wrap-width">
    <h1><?php is_category() ? single_cat_title( '<small>Blog:</small> ' ) : '' ?></h1>
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        get_template_part( 'template-parts/post-tile' );

    endwhile;
    get_template_part( 'template-parts/pagination' );
endif;

?>
    </div>
<?php

get_footer();
