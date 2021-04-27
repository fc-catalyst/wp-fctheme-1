<?php

get_header();

?>
	<header>
		<h1><?php single_post_title() ?></h1>
	</header>
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

?>

    <div class="wp-block-column">
	<article class="post-<?php the_ID(); ?> <?php echo get_post_type(); ?> type-<?php echo get_post_type(); ?> status-publish entry" itemscope="" itemtype="https://schema.org/CreativeWork">
		<header class="entry-header">
			<h2 class="entry-title" itemprop="headline">
				<a class="entry-title-link" rel="bookmark" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<?php get_template_part( 'template-parts/author', 'short' ) ?>
		</header>
		<?php the_excerpt() ?>
	</article>
	</div>

<?php

    endwhile;
    get_template_part( 'template-parts/pagination' );
endif;


get_footer();
