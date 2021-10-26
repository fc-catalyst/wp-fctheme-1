<?php
/**
 * The template for displaying comments
*/

if ( post_password_required() ) {
	return;
}

/*
if ( !comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
    ?>
    <p class="no-comments"><?php _e( 'Comments are closed.' ) ?></p>
    <?php
}
//*/

if ( have_comments() ) {

?>
<div class="entry-content">
    <div id="comments" class="comments-area">

        <div class="wp-block-columns">
            <div class="wp-block-column comments-list">

                <?php wp_list_comments() ?>
                
                <?php
                the_comments_pagination([
                    'prev_text' => '&lt;&nbsp;prev',
                    'next_text' => 'next&nbsp;&gt;'
                ]);
                ?>

            </div>
            
            <div class="wp-block-column" style="flex-basis:33.33%">
                <?php FCP_Comment_Rate::print_rating_summary() ?>
            </div>

        </div>
        
        <?php comment_form() ?>

    </div>
</div>
<?php

}
