<?php
		
$hab_prev_page = get_previous_posts_link( __( 'Previous Page', 'fct1' ) );
$hab_next_page = get_next_posts_link( __( 'Next Page', 'fct1' ) );
if ( !$hab_prev_page && !$hab_next_page ) {
    return;
}

?>

<nav class="wp-block-columns nav-prev-next">
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
