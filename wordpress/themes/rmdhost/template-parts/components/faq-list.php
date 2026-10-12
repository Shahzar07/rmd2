<?php
/**
 * FAQ accordion with numbered questions.
 *
 * @package RMDHost
 * @var array $args faqs: list of array( q, a ); open: index of the item open on load (-1 for none).
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $args['faqs'] ) ) {
	return;
}
$rmdhost_open = isset( $args['open'] ) ? (int) $args['open'] : -1;
?>
<div class="faq">
	<?php
	foreach ( array_values( $args['faqs'] ) as $rmdhost_i => $rmdhost_faq ) :
		$rmdhost_id      = 'fa-' . wp_unique_id();
		$rmdhost_is_open = $rmdhost_i === $rmdhost_open;
		?>
		<div class="faq-item<?php echo $rmdhost_is_open ? ' is-open' : ''; ?>" data-reveal>
			<button class="faq-q" type="button" aria-expanded="<?php echo $rmdhost_is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $rmdhost_id ); ?>"><span class="faq-n"><?php echo esc_html( str_pad( (string) ( $rmdhost_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><span class="faq-t"><?php echo esc_html( $rmdhost_faq['q'] ); ?></span><span class="faq-ico"><?php echo rmdhost_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></button>
			<div class="faq-a<?php echo $rmdhost_is_open ? ' open' : ''; ?>" id="<?php echo esc_attr( $rmdhost_id ); ?>"><div><?php echo wp_kses_post( wpautop( $rmdhost_faq['a'] ) ); ?></div></div>
		</div>
	<?php endforeach; ?>
</div>
