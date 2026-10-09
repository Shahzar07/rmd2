<?php
/**
 * Pricing tabs: one tab per product group, plan boxes in each.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'pricing' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a      = $args;
$rmdhost_uid    = wp_unique_id( 'pricing-' );
$rmdhost_groups = array();
foreach ( (array) $rmdhost_a['groups'] as $rmdhost_slug ) {
	$rmdhost_g = RMDHost\Data::group( $rmdhost_slug );
	if ( $rmdhost_g ) {
		$rmdhost_plans = RMDHost\Data::plans( $rmdhost_slug );
		if ( (int) $rmdhost_a['limit'] > 0 ) {
			$rmdhost_plans = array_slice( $rmdhost_plans, 0, (int) $rmdhost_a['limit'] );
		}
		if ( $rmdhost_plans ) {
			$rmdhost_groups[] = array( $rmdhost_g, $rmdhost_plans );
		}
	}
}
if ( ! $rmdhost_groups ) {
	return;
}
?>
<section class="section <?php echo ! empty( $rmdhost_a['soft'] ) ? 'section-soft' : ''; ?>"<?php echo $rmdhost_a['id'] ? ' id="' . esc_attr( $rmdhost_a['id'] ) . '"' : ''; ?>>
	<div class="container">
		<?php
		echo rmdhost_section_head(
			array(
				'title' => $rmdhost_a['title'],
				'text'  => $rmdhost_a['text'],
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		?>
		<?php if ( ! empty( $rmdhost_a['assure'] ) ) : ?>
			<ul class="assure" data-reveal>
				<?php foreach ( array_values( (array) $rmdhost_a['assure'] ) as $rmdhost_i => $rmdhost_x ) : ?>
					<li><?php echo rmdhost_icon( array( 'shield', 'refresh', 'headset' )[ $rmdhost_i % 3 ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $rmdhost_x ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div class="tabs" data-tabs>
			<div class="tablist tablist-center<?php echo count( $rmdhost_groups ) > 5 ? ' tablist-wrap' : ''; ?>" role="tablist" aria-label="<?php esc_attr_e( 'Plan type', 'rmdhost' ); ?>" data-reveal>
				<span class="tab-ind" aria-hidden="true"></span>
				<?php foreach ( $rmdhost_groups as $rmdhost_i => $rmdhost_pair ) : ?>
					<button role="tab" id="<?php echo esc_attr( "$rmdhost_uid-tab-$rmdhost_i" ); ?>" aria-controls="<?php echo esc_attr( "$rmdhost_uid-panel-$rmdhost_i" ); ?>" aria-selected="<?php echo 0 === $rmdhost_i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $rmdhost_i ? '0' : '-1'; ?>"><?php echo esc_html( $rmdhost_a['labels'][ $rmdhost_i ] ?? $rmdhost_pair[0]['name'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $rmdhost_groups as $rmdhost_i => $rmdhost_pair ) : ?>
				<div class="tabpanel" role="tabpanel" id="<?php echo esc_attr( "$rmdhost_uid-panel-$rmdhost_i" ); ?>" aria-labelledby="<?php echo esc_attr( "$rmdhost_uid-tab-$rmdhost_i" ); ?>" <?php echo $rmdhost_i ? 'hidden' : ''; ?>>
					<div class="plans plans-<?php echo (int) count( $rmdhost_pair[1] ); ?>">
						<?php
						foreach ( $rmdhost_pair[1] as $rmdhost_plan ) {
							echo rmdhost_plan_card( $rmdhost_plan, array( 'product' => $rmdhost_pair[0]['name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
					<p class="center mt"><a class="row-link inline" href="<?php echo esc_url( home_url( '/' . $rmdhost_pair[0]['slug'] . '/' ) ); ?>"><?php /* translators: %s: product name */ echo esc_html( sprintf( __( 'View all %s plans', 'rmdhost' ), $rmdhost_pair[0]['name'] ) ); ?><?php echo rmdhost_icon( 'arrowUpRight' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( $rmdhost_a['fine'] ) : ?>
			<p class="fine"><?php echo esc_html( $rmdhost_a['fine'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
