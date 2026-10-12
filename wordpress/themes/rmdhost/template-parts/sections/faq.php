<?php
/**
 * FAQ accordion section: sticky intro + help card beside numbered questions,
 * or a single centred column when layout is "simple".
 *
 * @package RMDHost
 * @var array $args title, text, group, soft, id, layout (split|simple), faqs (optional explicit list).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_faqs = ! empty( $args['faqs'] ) ? $args['faqs'] : RMDHost\Data::faqs( $args['group'] );
if ( ! $rmdhost_faqs ) {
	return;
}
$rmdhost_split = empty( $args['layout'] ) || 'simple' !== $args['layout'];
?>
<section class="section faq-sec <?php echo ! empty( $args['soft'] ) ? 'section-soft' : ''; ?>"<?php echo ! empty( $args['id'] ) ? ' id="' . esc_attr( $args['id'] ) . '"' : ''; ?>>
	<?php if ( $rmdhost_split ) : ?>
		<div class="container faq-wrap">
			<div class="faq-side" data-reveal>
				<p class="kicker">
				<?php
				/* translators: %d: number of questions */
				echo esc_html( sprintf( _n( '// FAQ · %d answer', '// FAQ · %d answers', count( $rmdhost_faqs ), 'rmdhost' ), count( $rmdhost_faqs ) ) );
				?>
				</p>
				<h2 class="h2"><?php echo rmdhost_kses_inline( $args['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php if ( ! empty( $args['text'] ) ) : ?>
					<p class="muted"><?php echo rmdhost_kses_inline( $args['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php endif; ?>
				<div class="faq-help">
					<span class="faq-help-ico"><?php echo rmdhost_icon( 'headset' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<strong><?php esc_html_e( 'Still have a question?', 'rmdhost' ); ?></strong>
					<span><?php esc_html_e( 'Our in-house engineers answer tickets 24/7 – no chatbots, no scripts.', 'rmdhost' ); ?></span>
					<span class="faq-help-cta"><a class="btn btn-primary" href="<?php echo esc_url( rmdhost_url( '/support/' ) ); ?>"><?php esc_html_e( 'Open a ticket', 'rmdhost' ); ?></a><a class="row-link inline" href="<?php echo esc_url( rmdhost_url( '/faq/' ) ); ?>"><?php esc_html_e( 'All FAQs', 'rmdhost' ); ?><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></span>
				</div>
			</div>
			<?php echo rmdhost_faq_list( $rmdhost_faqs, 0 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php else : ?>
		<div class="container narrow">
			<?php echo rmdhost_section_head( array( 'title' => $args['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo rmdhost_faq_list( $rmdhost_faqs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
</section>
