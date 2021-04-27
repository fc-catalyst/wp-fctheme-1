<p class="post-meta">
    by
    <?php if ( function_exists( 'get_avatar_url' ) ) : ?>
        <img
            src="<?php echo get_avatar_url( get_the_author_meta('ID') ) ?>"
            alt="<?php the_author_meta( 'first_name' ) ?> <?php the_author_meta( 'last_name' ) ?>"
        />
    <?php endif; ?>
        <span class="author-short">
            <?php the_author_meta( 'first_name' ) ?>
            <?php the_author_meta( 'last_name' ) ?>
        </span>
    | <?php echo get_the_date() ?>
</p>
