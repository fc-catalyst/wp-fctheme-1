<?php

get_header();

?>


<div class="container">
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

?>

	<article class="post-<?php the_ID(); ?> <?php echo get_post_type(); ?> type-<?php echo get_post_type(); ?> status-publish entry" itemscope="" itemtype="https://schema.org/CreativeWork">
		<div class="post-content">
			<div class="wrap-width" itemprop="text">
				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline">
						<?php the_title(); ?>
					</h1>

					<?php if ( get_post_type() == 'post' ) : ?>
                    <p class="post-meta">
                        by
                        <span itemscope="" itemid="<?php echo site_url() ?>/about/" itemtype="https://schema.org/Person">
                            <?php if ( function_exists( 'get_avatar_url' ) ) : ?>
                            <span itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                                <img src="<?php echo get_avatar_url( get_the_author_meta('ID') ) ?>" alt="<?php the_author_meta('first_name') ?> <?php the_author_meta('last_name') ?>" itemprop="url">
                            </span>
                            <?php endif; ?>
                            <a href="<?php echo site_url() ?>/about/" itemprop="url" rel="author">
                                <span itemprop="name">
                                    <?php the_author_meta('first_name') ?>
                                    <?php the_author_meta('last_name') ?>
                                </span>
                            </a>
                        </span>
                        |
                        <span itemprop="datePublished" content="<?php the_date('Y-m-d') ?>">
                            <?php echo get_the_date() ?>
                        </span>
                    </p>
                    <?php endif; ?>

				</header>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</article>
	

<?php
        if ( get_post_type() == 'post' ) :

?>
<nav class="wp-block-columns">
    <div class="wp-block-column">
		<p>
            <?php next_post_link( '%link', 'Previous Post' ) ?>
        </p>
    </div>
    <div class="wp-block-column">
		<p style="text-align:right;">
            <?php previous_post_link( '%link', 'Next Post' ) ?>
        </p>
	</div>
</nav>
<?php

        endif;

    endwhile;
endif;

?>
</div>
<?php

get_footer();
