<?php

get_header();

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
        <div class="entry-content">

<!-- gutenberg formatting start -->

<header class="entry-header wp-block-columns alignwide">

    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
        <h1><?php the_title() ?></h1>
        <div class="entry-details">
            <span class="entry-date" itemprop="datePublished" content="<?php the_date('Y-m-d') ?>">
                <?php echo get_the_date() ?>
            </span>
            <?php echo $print_cats[0] ? ' • <span class="entry-categories">'.implode(', ',$print_cats).'</span>' : '' ?>
        </div>
        <div class="entry-author">
            by <?php the_author_meta( 'display_name' ) ?>
        </div>
    </div>

    <div class="wp-block-column" style="flex-basis:50%;position:relative">
        <div class="entry-photo">
            <?php if ( $th_url = get_the_post_thumbnail_url() ) { ?>
            <img loading="lazy" width="100%" height="100%"
                src="<?php echo $th_url ?>"
                alt="<?php the_title() ?>"
            />
            <?php } ?>
        </div>
    </div>

</header>

<div style="height:35px" aria-hidden="true" class="wp-block-spacer"></div>

<?php the_content() ?>

<!-- // -->
        </div>
    </div>
</article>

<div class="entry-content">
    <?php get_template_part( 'template-parts/post', 'prevnext' ) ?>
    <div style="height:60px" aria-hidden="true" class="wp-block-spacer"></div>
    <h2 align="center">Themen die Sie Interessieren Könnten</h2>
    <div style="height:30px" aria-hidden="true" class="wp-block-spacer"></div>
    <?php get_template_part( 'template-parts/post', 'moreposts' ) ?>
    <?php comments_template() ?>
</div>

<?php

    endwhile;
endif;

get_footer();
