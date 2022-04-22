<?php

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
?>

	<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-publish entry" itemscope="" itemtype="https://schema.org/CreativeWork">
		<div class="post-content" itemprop="text">
            <?php if ( !get_post_meta( get_the_ID(), 'hide-h1', true ) ) { ?>
            <header class="entry-header entry-content">
                <h1 class="entry-title" itemprop="headline"><?php the_title() ?></h1>
            </header>
            <?php } ?>
            <div class="entry-content">
                <?php the_content() ?>
            </div>
		</div>
	</article>

<?php comments_template() ?>

<?php

    endwhile;
endif;

get_footer();
