<?php
/**
 * Filterable instant dedicated server table.
 *
 * @package RMDHost
 * @var array $args group.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_g = $args['group'];
?>
<div class="filters" data-filter-group role="group" aria-label="<?php esc_attr_e( 'Filter servers', 'rmdhost' ); ?>" data-reveal>
	<?php foreach ( array_values( (array) ( $rmdhost_g['filters'] ?? array() ) ) as $rmdhost_i => $rmdhost_f ) : ?>
		<button type="button" class="chip <?php echo $rmdhost_i ? '' : 'on'; ?>" data-filter="<?php echo esc_attr( $rmdhost_i ? $rmdhost_f : '*' ); ?>" aria-pressed="<?php echo $rmdhost_i ? 'false' : 'true'; ?>"><?php echo esc_html( $rmdhost_f ); ?></button>
	<?php endforeach; ?>
	<label class="chip chip-check"><input type="checkbox" data-instock> <?php esc_html_e( 'In stock only', 'rmdhost' ); ?></label>
</div>
<div class="stable" role="table" aria-label="<?php echo esc_attr( $rmdhost_g['name'] ); ?>">
	<div class="srow shead" role="row"><span role="columnheader"><?php esc_html_e( 'Processor', 'rmdhost' ); ?></span><span role="columnheader"><?php esc_html_e( 'RAM', 'rmdhost' ); ?></span><span role="columnheader"><?php esc_html_e( 'Storage', 'rmdhost' ); ?></span><span role="columnheader"><?php esc_html_e( 'Included', 'rmdhost' ); ?></span><span role="columnheader"><?php esc_html_e( 'Price', 'rmdhost' ); ?></span><span role="columnheader"><span class="sr-only"><?php esc_html_e( 'Order', 'rmdhost' ); ?></span></span></div>
	<?php foreach ( (array) $rmdhost_g['servers'] as $rmdhost_s ) : ?>
		<?php
		$rmdhost_s = RMDHost\Data::normalize_plan(
			$rmdhost_s + array(
				'family'  => '',
				'network' => '',
				'stock'   => true,
			)
		);
		?>
		<div class="srow <?php echo $rmdhost_s['stock'] ? '' : 'out'; ?>" role="row" data-family="<?php echo esc_attr( $rmdhost_s['family'] ); ?>" data-stock="<?php echo $rmdhost_s['stock'] ? 'true' : 'false'; ?>">
			<span role="cell" data-l="<?php esc_attr_e( 'Processor', 'rmdhost' ); ?>"><b><?php echo esc_html( $rmdhost_s['name'] ); ?></b><small><?php echo esc_html( $rmdhost_s['cpu'] ); ?></small></span>
			<span role="cell" data-l="<?php esc_attr_e( 'RAM', 'rmdhost' ); ?>"><?php echo esc_html( $rmdhost_s['ram'] ); ?></span>
			<span role="cell" data-l="<?php esc_attr_e( 'Storage', 'rmdhost' ); ?>"><?php echo esc_html( $rmdhost_s['storage'] ); ?></span>
			<span role="cell" data-l="<?php esc_attr_e( 'Included', 'rmdhost' ); ?>"><?php echo esc_html( $rmdhost_s['network'] ); ?><small><?php esc_html_e( '/64 IPv6 · DDoS protection', 'rmdhost' ); ?></small></span>
			<span role="cell" data-l="<?php esc_attr_e( 'Price', 'rmdhost' ); ?>" class="sprice"><?php echo rmdhost_money( $rmdhost_s['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span role="cell">
				<?php if ( $rmdhost_s['stock'] ) : ?>
					<a class="btn btn-primary btn-sm" href="<?php echo esc_url( $rmdhost_s['order'] ); ?>"><?php esc_html_e( 'Order now', 'rmdhost' ); ?></a><small class="free"><?php esc_html_e( 'Free setup', 'rmdhost' ); ?></small>
				<?php else : ?>
					<span class="btn btn-sm btn-disabled" aria-disabled="true"><?php esc_html_e( 'Sold out', 'rmdhost' ); ?></span>
				<?php endif; ?>
			</span>
		</div>
	<?php endforeach; ?>
</div>
