<?php

get_header();

?>
    <div class="wrap-width">
    <h1><?php single_post_title() ?></h1>
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        get_template_part( 'template-parts/post', 'tile' );

    endwhile;
    get_template_part( 'template-parts/pagination' );
endif;

?>
    </div>
<?php

get_footer();
