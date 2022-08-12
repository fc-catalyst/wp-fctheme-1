<?php
/**
 * The template for displaying comments
*/

if ( post_password_required() ) { return; }
if ( !comments_open() && !get_comments_number() || !post_type_supports( get_post_type(), 'comments' ) ) { return; }

?>
<div id="comments" class="comments-area entry-content">

	<?php if ( have_comments() ) { ?>

    <ul class="comments-list">
        <?php wp_list_comments() ?>
    </ul>

    <?php the_comments_pagination(); ?>

    <?php } ?>

    <?php
    // print the form
    if ( comments_open() ) { comment_form(); }
    if ( !comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
        ?>
        <p class="no-comments"><?php _e( 'Comments are closed.' ) ?></p>
        <?php
    }
	?>

</div>

<?php