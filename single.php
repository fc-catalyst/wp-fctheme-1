<?php

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

?>

	<article class="post-<?php the_ID(); ?> <?php echo get_post_type(); ?> type-<?php echo get_post_type(); ?> status-publish entry" itemscope="" itemtype="https://schema.org/Article">
		<div class="post-content" itemprop="text">
            <header class="entry-header wrap-width">
                <h1 class="entry-title" itemprop="headline">
                    <?php the_title(); ?>
                </h1>
                <?php get_template_part( 'template-parts/author', 'short' ) ?>
            </header>
            <div class="entry-content">
                <?php the_content() ?>
            </div>
		</div>
	</article>

<div class="entry-content">
    <?php comments_template() ?>
</div>

<?php

    endwhile;
    get_template_part( 'template-parts/navigation', 'posts' );
endif;

get_footer();
