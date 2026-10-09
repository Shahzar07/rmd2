<?php
/**
 * Every product group in tabs + "instant servers" callout (Pricing page).
 *
 * @package RMDHost
 * @var array $args callout (bool), fine.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_uid  = wp_unique_id( 'pp-' );
$rmdhost_tabs = array();
foreach ( RMDHost\Data::groups() as $rmdhost_slug => $rmdhost_g ) {
	$rmdhost_plans = RMDHost\Data::plans( $rmdhost_slug );
	if ( $rmdhost_plans ) {
		$rmdhost_tabs[] = array( $rmdhost_g, $rmdhost_plans );
	}
}
$rmdhost_instant = RMDHost\Data::from_price( 'instant-dedicated-servers-usa' );
?>
<section class="section">
	<div class="container">
		<div class="tabs" data-tabs>
			<div class="tablist tablist-center tablist-wrap" role="tablist" aria-label="<?php esc_attr_e( 'Product', 'rmdhost' ); ?>" data-reveal>
				<span class="tab-ind" aria-hidden="true"></span>
				<?php foreach ( $rmdhost_tabs as $rmdhost_i => $rmdhost_t ) : ?>
					<button role="tab" id="<?php echo esc_attr( "$rmdhost_uid-t-{$rmdhost_t[0]['slug']}" ); ?>" aria-controls="<?php echo esc_attr( "$rmdhost_uid-{$rmdhost_t[0]['slug']}" ); ?>" aria-selected="<?php echo $rmdhost_i ? 'false' : 'true'; ?>" tabindex="<?php echo $rmdhost_i ? '-1' : '0'; ?>"><?php echo esc_html( $rmdhost_t[0]['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $rmdhost_tabs as $rmdhost_i => $rmdhost_t ) : ?>
				<div class="tabpanel" role="tabpanel" id="<?php echo esc_attr( "$rmdhost_uid-{$rmdhost_t[0]['slug']}" ); ?>" aria-labelledby="<?php echo esc_attr( "$rmdhost_uid-t-{$rmdhost_t[0]['slug']}" ); ?>"<?php echo $rmdhost_i ? ' hidden' : ''; ?>>
					<div class="plans plans-<?php echo (int) count( $rmdhost_t[1] ); ?>">
						<?php
						foreach ( $rmdhost_t[1] as $rmdhost_plan ) {
							echo rmdhost_plan_card( $rmdhost_plan, array( 'product' => $rmdhost_t[0]['name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
					<p class="center mt"><a class="row-link inline" href="<?php echo esc_url( home_url( '/' . $rmdhost_t[0]['slug'] . '/' ) ); ?>"><?php /* translators: %s: product name */ echo esc_html( sprintf( __( '%s details', 'rmdhost' ), $rmdhost_t[0]['name'] ) ); ?><?php echo rmdhost_icon( 'arrowUpRight' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( ( ! isset( $args['callout'] ) || $args['callout'] ) && null !== $rmdhost_instant ) : ?>
			<div class="callout" data-reveal>
				<div><h2 class="h3"><?php esc_html_e( 'Need instant bare metal in the USA?', 'rmdhost' ); ?></h2><p class="muted"><?php esc_html_e( 'Pre-configured dedicated servers online in minutes from', 'rmdhost' ); ?> <?php echo rmdhost_money( $rmdhost_instant ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>.</p></div>
				<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/instant-dedicated-servers-usa/' ) ); ?>"><?php esc_html_e( 'View instant servers', 'rmdhost' ); ?></a>
			</div>
		<?php endif; ?>
		<p class="fine"><?php echo esc_html( $args['fine'] ?? __( 'Prices exclude VAT and are billed monthly.', 'rmdhost' ) ); ?></p>
	</div>
</section>
