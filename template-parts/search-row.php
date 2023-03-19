<?php

$cat = get_the_category( get_the_ID() )[0];
$cat = $cat
    ? ' | Category: <a href="'.esc_url( get_category_link( $cat ) ).'">'.esc_html( $cat->name ).'</a>'
    : '';

$post_type = get_post_type_object( get_post_type() );

$featured_image = in_array( $post_type->name, ['docrot', 'clinic'] )
    ? fct1_image( 'entity/' . get_the_ID() . '/' . (
        fct1_meta( 'entity-photo', '', '', true )[0]
        ?: fct1_meta( 'entity-background', '', '', true )[0]
        ?: fct1_meta( 'entity-avatar', '', '', true )[0]
        ?: null
    ))
    : fct1_image( get_post_thumbnail_id() ?: null, [500,500], ['center','top'], get_the_title() );

$type = $post_type ? $post_type->labels->singular_name : '';

?>
<article class="post-<?php the_ID() ?> <?php echo get_post_type() ?> type-<?php echo get_post_type() ?> status-<?php echo get_post_status() ?> entry">
    <div class="entry-photo">
        <a class="entry-photo-link" href="<?php the_permalink() ?>">
            <?php echo $featured_image ?>
        </a>
    </div>
    <div>
        <header class="entry-header">
            <h2 class="entry-title">
                <a class="entry-title-link" href="<?php the_permalink() ?>"><?php the_title() ?></a>
            </h2>
        </header>
        <div class="entry-details">
            <div class="entry-excerpt">
                <?php the_excerpt() ?>
            </div>
        </div>
        <footer class="entry-meta">
            <?php echo $type ?>
            <?php echo $cat ?>
        </footer>
    </div>
</article>
<?php
