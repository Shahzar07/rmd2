<?php
/**
 * Horizontal carousel linking to every server product.
 *
 * @package RMDHost
 * @var array $args title, dark (bool), exclude (slug).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_dark = ! empty( $args['dark'] );
?>
<?php if ( ! empty( $args['title'] ) ) : ?>
	<div class="car-head" data-reveal>
		<h2 class="h3"><?php echo esc_html( $args['title'] ); ?></h2>
		<div class="car-arrows"><button class="icon-btn" type="button" data-car-prev aria-label="<?php esc_attr_e( 'Previous', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'chevronLeft' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button><button class="icon-btn" type="button" data-car-next aria-label="<?php esc_attr_e( 'Next', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'chevronRight' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button></div>
	</div>
<?php endif; ?>
<div class="carousel" data-carousel>
	<?php foreach ( RMDHost\Data::groups() as $rmdhost_slug => $rmdhost_g ) : ?>
		<?php
		if ( ! empty( $args['exclude'] ) && $args['exclude'] === $rmdhost_slug ) {
			continue;
		}
		$rmdhost_from = RMDHost\Data::from_price( $rmdhost_slug );
		?>
		<a class="car-card<?php echo $rmdhost_dark ? '' : ' car-light'; ?>" href="<?php echo esc_url( home_url( '/' . $rmdhost_slug . '/' ) ); ?>" data-spot>
			<?php echo rmdhost_icon( $rmdhost_g['icon'] ?? 'server' ) . rmdhost_icon( 'arrowUpRight', 'corner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<strong><?php echo esc_html( $rmdhost_g['name'] ); ?><?php echo ( $rmdhost_dark && ! empty( $rmdhost_g['tag'] ) ) ? ' <em class="pill pill-xs pill-invert">' . esc_html( $rmdhost_g['tag'] ) . '</em>' : ''; ?></strong>
			<?php if ( $rmdhost_dark ) : ?>
				<span><?php echo esc_html( strtok( (string) ( $rmdhost_g['lede'] ?? '' ), '.' ) . '.' ); ?></span>
			<?php else : ?>
				<span><?php echo null !== $rmdhost_from ? esc_html__( 'From', 'rmdhost' ) . ' ' . rmdhost_money( $rmdhost_from ) : esc_html__( 'Pricing coming soon', 'rmdhost' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php endif; ?>
		</a>
	<?php endforeach; ?>
</div>
