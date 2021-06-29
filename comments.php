<?php
/**
 * The template for displaying comments
*/

if ( post_password_required() ) {
	return;
}

?>

<div id="comments" class="comments-area">

	<?php

	if ( have_comments() ) :

    ?>
		<h2><?php
			$comments_number = get_comments_number();
			echo $comments_number == 1 ? 'One review' : $comments_number . ' reviews';
        ?></h2>

		<div class="comments-list">
			<?php
				wp_list_comments([
                    'avatar_size' => 80,
                    'max_depth' => 2,
                    'style'       => 'div',
                    'callback' => 'fct1_comment_print',
                    'short_ping'  => true,
                    'reply_text'  => 'Reply this review',
                    //'per_page' => 0,
                    //'page' => 1,
                    'reverse_top_level' => true,
                    'reverse_children' => true,
                    'login_text' => 'Login to leave a review / reply'
				]);
			?>
		</div>

		<?php
		the_comments_pagination([
            'prev_text' => '&lt;&nbsp;prev',
            'next_text' => 'next&nbsp;&gt;'
		]);

	endif;

	if ( !comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?>

		<p class="no-comments"><?php _e( 'Comments are closed.' ) ?></p>

		<?php
	}

	comment_form([
        'title_reply'  => 'Leave a Comment',
        'comment_notes_before' => '',
        'fields' => [
            'author' => '<p class="comment-form-author">
                <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . $html_req . ' placeholder="' . __( 'Name' ) . ( $req ? ' *' : '' ) . '" />
            </p>',
            'email'  => '<p class="comment-form-email">
                <input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' placeholder="' . __( 'Email' ) . ( $req ? ' *' : '' ) . '" />
            </p>',
            'cookies' => '',
        ],
        'comment_field' => '<p class="comment-form-comment">
            <textarea id="comment" name="comment" cols="45" rows="8"  aria-required="true" required="required" placeholder="' . __( 'Comment' ) . ' *"></textarea>
        </p>',
	]);

	?>

</div>

<?php

function fct1_comment_print( $comment, $args, $depth ) {
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}

	$classes = ' ' . comment_class( empty( $args['has_children'] ) ? '' : 'parent', null, null, false );
	?>

<<?php echo $tag, $classes; ?> id="comment-<?php comment_ID() ?>">

	<div class="comment-author">
		<?php

		if ( $args['avatar_size'] != 0 ) {
			echo get_avatar( $comment, $args['avatar_size'] );
		}

        echo get_comment_author();

		?>
	</div>

	<?php if ( $comment->comment_approved == '0' ) { ?>
		<em class="comment-awaiting-moderation">
			<?php _e( 'Your comment is awaiting moderation.' ) ?>
		</em><br/>
	<?php } ?>

	<div class="comment-content">
        <?php comment_text() ?>
    </div>

	<div class="comment-more">
		<?php
		comment_reply_link(
			array_merge(
				$args,
				array(
					'add_below' => $add_below,
					'depth'     => $depth,
					'max_depth' => $args['max_depth']
				)
			)
		);

		edit_comment_link( __( 'Edit' ), '  ', '' );
		
		echo get_comment_date();
        //echo get_comment_time();
		?>
	</div>
    <?php
}
