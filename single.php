<?php

get_header();

$the_query = new WP_Query( [
    'post_type'        => 'fct-section',
    'name'        => 'post-hero'
]);

if ( $the_query->have_posts() ) {
    ?><style>body::before{content:none}</style><?php
    
    ob_start();
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
?>		
        <div class="entry-content">
            <?php the_content() ?>
        </div>
<?php
    }
    $header = ob_get_contents();
    ob_end_clean();
    wp_reset_postdata();
}


if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        
        $cats = get_the_category( get_the_ID() );
        $print_cats = [];
        if ( is_array( $cats ) ) {
            foreach ( $cats as $v ) {
                $print_cats[] = '<a href="'.esc_url( get_category_link( $v ) ).'">'.esc_html( $v->name ).'</a>';
            }
        }

?>

<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry" itemscope="" itemtype="https://schema.org/Article">
    <div class="post-content" itemprop="text">

        <?php echo $header ? '<header>' . $header . '</header>' : '' ?>

        <div class="entry-content">
            <div style="height:90px" aria-hidden="true" class="wp-block-spacer"></div>
            <?php the_content() ?>
            <?php print_author() ?>
            <div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
            <?php get_template_part( 'template-parts/post', 'prevnext' ) ?>
        </div>

    </div>
</article>

<div class="entry-content">
    <div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
    <h2 align="center"><?php _e( 'Topics you might be interested in', 'fct1' ) ?></h2>
    <div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
    <?php get_template_part( 'template-parts/post', 'moreposts' ) ?>
</div>

<?php comments_template() ?>

<?php

    endwhile;
endif;

?>
<div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div>
<?php

get_footer();

function print_author() {
    global $authordata;

    $name = get_the_author_meta( 'display_name' );
    $about = get_the_author_meta( 'user_description' );    

    if ( !$about || !in_array( 'author', $authordata->roles ) ) {
        return;
    }
    
    ?>
    <div class="author-box">
        <!--<div class="author-photo"></div>-->
        <h3><?php echo $name ?></h3>
        <?php echo $about ?>
    </div>
    <?php
}
