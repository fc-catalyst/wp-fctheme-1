<?php
		
$hab_prev_page = get_previous_posts_link( 'Previous Page' );
$hab_next_page = get_next_posts_link( 'Next Page' );
if ( !$hab_prev_page && !$hab_next_page ) {
    return;
}

?>

<nav class="wp-block-columns">
    <div class="wp-block-column">
		<p>
            <?php echo $hab_prev_page ?>
        </p>
    </div>
    <div class="wp-block-column">
		<p style="text-align:right;">
            <?php echo $hab_next_page ?>
        </p>
	</div>
</nav>
