<?php
/**
 * Closing call-to-action band with photo and floating chips.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'cta' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a = $args;
?>
<section class="cta-band dark">
	<div class="glow" aria-hidden="true"><i></i><i></i></div>
	<div class="container cta-grid">
		<div data-reveal>
			<h2 class="h-display"><?php echo rmdhost_kses_inline( $rmdhost_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p class="lead"><?php echo esc_html( $rmdhost_a['text'] ); ?></p>
			<?php if ( $rmdhost_a['button'] ) : ?>
				<a class="btn btn-white btn-lg" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['button_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['button'] ); ?></a>
			<?php endif; ?>
		</div>
		<div class="cta-visual" data-reveal>
			<div class="photo-frame"><?php echo rmdhost_image( $rmdhost_a['image'], __( 'Small business owner smiling at a laptop after launching a website', 'rmdhost' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php if ( $rmdhost_a['domain'] ) : ?>
				<div class="cta-domain float-a"><?php echo rmdhost_icon( 'globe' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo rmdhost_kses_inline( $rmdhost_a['domain'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
			<?php if ( $rmdhost_a['toast'] ) : ?>
				<div class="toast cta-toast float-b"><?php echo rmdhost_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><div><b><?php echo esc_html( $rmdhost_a['toast'] ); ?></b><small><?php echo esc_html( $rmdhost_a['toast_note'] ); ?></small></div></div>
			<?php endif; ?>
			<?php if ( $rmdhost_a['prompt'] ) : ?>
				<div class="cta-prompt float-c"><span><?php echo esc_html( $rmdhost_a['prompt'] ); ?></span><b><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b></div>
			<?php endif; ?>
		</div>
	</div>
</section>
