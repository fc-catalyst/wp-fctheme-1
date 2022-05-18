<?php

get_header();

?>
    <style>body::before{content:none}</style>

    <?php
        $the_query = new WP_Query( [
            'post_type'        => 'fct-section',
            'name'        => 'blog-hero'
        ]);

        if ( $the_query->have_posts() ) {
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
    ?>		
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
    <?php
            }
            wp_reset_postdata();
        }
    ?>

    <div style="height:90px" aria-hidden="true" class="wp-block-spacer"></div>

    <div class="wrap-width">
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
    <div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div>
<?php

get_footer();