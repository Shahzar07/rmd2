<?php
/**
 * Review card.
 *
 * @package RMDHost
 * @var array $args review: name, role, product, rating, text, sample.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_r = wp_parse_args(
	$args['review'],
	array(
		'name'    => '',
		'role'    => '',
		'product' => '',
		'rating'  => 5,
		'text'    => '',
		'sample'  => false,
	)
);
?>
<figure class="review" data-reveal>
	<?php echo rmdhost_stars( (int) $rmdhost_r['rating'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<blockquote>“<?php echo esc_html( $rmdhost_r['text'] ); ?>”</blockquote>
	<figcaption><span class="av"><?php echo esc_html( mb_substr( $rmdhost_r['name'], 0, 1 ) ); ?></span><span><b><?php echo esc_html( $rmdhost_r['name'] ); ?></b><small><?php echo esc_html( trim( $rmdhost_r['role'] . ' · ' . $rmdhost_r['product'], ' ·' ) ); ?></small></span>
	<?php
	if ( ! empty( $rmdhost_r['sample'] ) ) :
		?>
		<em class="pill pill-xs" title="<?php esc_attr_e( 'Replace with a real customer review before launch', 'rmdhost' ); ?>"><?php esc_html_e( 'Sample', 'rmdhost' ); ?></em><?php endif; ?></figcaption>
</figure>
