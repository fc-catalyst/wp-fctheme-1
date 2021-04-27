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
