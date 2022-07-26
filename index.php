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
            <?php
            if ( $custom_header = get_post_meta( get_the_ID(), 'custom-header', true ) ) {
                $the_query = new WP_Query( [
                    'post_type'      => 'fct-section',
                    'p'              => $custom_header,
                    'posts_per_page' => 1,
                    'post_status'    => 'publish',
                ]);
                if ( $the_query->have_posts() ) {
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                ?>		
                <header class="entry-header entry-content">
                    <?php the_content() ?>
                </header>
                <?php
                    }
                    wp_reset_postdata();
                }
            }
            ?>
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
