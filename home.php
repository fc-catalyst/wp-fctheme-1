<?php

get_header();

?>
    <div class="wrap-width">
    <h1><?php single_post_title() ?></h1>
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        $cat = get_the_category( get_the_ID() )[0];
        $cat = $cat
            ? '<a href="'.esc_url( get_category_link( $cat ) ).'" class="entry-category">'.esc_html( $cat->name ).'</a>'
            : ''
        ;
?>
<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry" itemscope="" itemtype="https://schema.org/CreativeWork">
    <header class="entry-header">
        <?php echo $cat ?>
        <div class="entry-photo">
            <?php if ( $th_url = get_the_post_thumbnail_url() ) { ?>
            <img loading="lazy" width="100%" height="100%"
                src="<?php echo $th_url ?>"
                alt="<?php the_title() ?>"
            />
            <?php } ?>
        </div>
        <h2 class="entry-title" itemprop="headline">
            <a class="entry-title-link" rel="bookmark" href="<?php the_permalink() ?>"><?php the_title() ?></a>
        </h2>
    </header>
    <div class="entry-details">
        <div class="entry-date" itemprop="datePublished" content="<?php the_date('Y-m-d') ?>">
            <?php echo get_the_date() ?>
        </div>
        <div class="entry-excerpt">
            <?php the_excerpt() ?>
        </div>
        <a href="<?php the_permalink() ?>" class="entry-read">Weiter lesen</a>
    </div>
</article>

<?php

    endwhile;
    get_template_part( 'template-parts/pagination' );
    ?></div><?php
endif;


get_footer();
