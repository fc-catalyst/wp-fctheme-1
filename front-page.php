<?php

get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

?>

	<article class="post-<?php the_ID(); ?> <?php echo get_post_type(); ?> type-<?php echo get_post_type(); ?> status-publish entry" itemscope="" itemtype="https://schema.org/CreativeWork">
		<div class="post-content" itemprop="text">
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
		</div>
	</article>
	
<?php

    endwhile;
endif;

?>
<style>
    body.home {
        --hero-bg:url('http://localhost/wordpress/wp-content/uploads/nathan-dumlao-Wr3comVZJxU-unsplash-1.png') no-repeat 50% 50%;
    }
</style>
<?php

get_footer();
