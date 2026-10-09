<?php
/**
 * Numbered steps (e.g. "How it works").
 *
 * @package RMDHost
 * @var array $args title, items[ title, text ], soft.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $args['items'] ) ) {
	return;
}
?>
<section class="section <?php echo ! empty( $args['soft'] ) ? 'section-soft' : ''; ?>">
	<div class="container">
		<?php echo rmdhost_section_head( array( 'title' => $args['title'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<ol class="steps">
			<?php foreach ( array_values( (array) $args['items'] ) as $rmdhost_i => $rmdhost_s ) : ?>
				<li data-reveal><span class="step-n"><?php echo esc_html( str_pad( (string) ( $rmdhost_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h3><?php echo esc_html( $rmdhost_s['title'] ); ?></h3><p class="muted"><?php echo esc_html( $rmdhost_s['text'] ); ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
