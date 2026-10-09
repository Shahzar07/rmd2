<?php
/**
 * Promo grid: one large image card + two small cards.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'promo' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a = $args;
?>
<section class="section promo-sec">
	<div class="container promo-grid">
		<a class="promo-big" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['big_url'] ) ); ?>" data-reveal data-spot>
			<?php echo rmdhost_image( $rmdhost_a['big_image'], '', array( 'class' => 'promo-bg' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="stripes" aria-hidden="true"><i></i><i></i><i></i></span>
			<span class="pill pill-glass"><?php echo esc_html( $rmdhost_a['big_tag'] ); ?></span>
			<span class="promo-big-body">
				<strong><?php echo esc_html( $rmdhost_a['big_title'] ); ?></strong>
				<span><?php echo rmdhost_price_tokens( $rmdhost_a['big_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="btn btn-white"><?php echo esc_html( $rmdhost_a['big_button'] ); ?></span>
			</span>
		</a>
		<?php foreach ( (array) $rmdhost_a['cards'] as $rmdhost_c ) : ?>
			<a class="promo-small" href="<?php echo esc_url( rmdhost_url( $rmdhost_c['url'] ) ); ?>" data-reveal data-spot>
				<span class="pill"><?php echo esc_html( $rmdhost_c['tag'] ); ?></span><?php echo rmdhost_icon( 'arrowUpRight', 'corner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<strong><?php echo esc_html( $rmdhost_c['title'] ); ?></strong>
				<span><?php echo rmdhost_price_tokens( $rmdhost_c['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php if ( ! empty( $rmdhost_c['icon'] ) ) : ?>
					<span class="promo-ico float-a"><?php echo rmdhost_icon( $rmdhost_c['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
			</a>
		<?php endforeach; ?>
	</div>
</section>
