<?php
/**
 * FAQ accordion.
 *
 * @package RMDHost
 * @var array $args faqs: list of array( q, a ).
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $args['faqs'] ) ) {
	return;
}
?>
<div class="faq">
	<?php
	foreach ( $args['faqs'] as $rmdhost_faq ) :
		$rmdhost_id = 'fa-' . wp_unique_id();
		?>
		<div class="faq-item" data-reveal>
			<button class="faq-q" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $rmdhost_id ); ?>"><?php echo esc_html( $rmdhost_faq['q'] ); ?><span class="faq-ico"><?php echo rmdhost_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></button>
			<div class="faq-a" id="<?php echo esc_attr( $rmdhost_id ); ?>"><div><?php echo wp_kses_post( wpautop( $rmdhost_faq['a'] ) ); ?></div></div>
		</div>
	<?php endforeach; ?>
</div>
