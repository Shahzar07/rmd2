<?php
/**
 * Comments.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="h3 comments-title">
			<?php
			$rmdhost_count = get_comments_number();
			/* translators: %s: number of comments. */
			printf( esc_html( _n( '%s comment', '%s comments', $rmdhost_count, 'rmdhost' ) ), esc_html( number_format_i18n( $rmdhost_count ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
		<?php if ( ! comments_open() ) : ?>
			<p class="muted"><?php esc_html_e( 'Comments are closed.', 'rmdhost' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'class_form'         => 'form comment-form',
			'class_submit'       => 'btn btn-primary',
			'title_reply_before' => '<h2 id="reply-title" class="h3 comment-reply-title">',
			'title_reply_after'  => '</h2>',
		)
	);
	?>
</div>
