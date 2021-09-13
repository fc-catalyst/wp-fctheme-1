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

    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%">
        The thumbnail goes here
    </div>

</header>


<div class="entry-content-main">
    <?php the_content() ?>
</div>

<!-- // -->
        </div>
    </div>
</article>

<div class="entry-content">
    <?php get_template_part( 'template-parts/navigation', 'posts' ) ?>
    <?php comments_template() ?>
</div>

<?php

    endwhile;
endif;

get_footer();
