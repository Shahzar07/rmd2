<?php
/**
 * "More configurations" dedicated server table.
 *
 * @package RMDHost
 * @var array $args table: title, columns, rows.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_t = $args['table'];
$rmdhost_l = array( __( 'Server', 'rmdhost' ), __( 'CPU', 'rmdhost' ), __( 'Storage', 'rmdhost' ), __( 'RAM', 'rmdhost' ), __( 'Network', 'rmdhost' ), __( 'Price', 'rmdhost' ) );
?>
<h3 class="sub-h center" data-reveal><?php echo esc_html( $rmdhost_t['title'] ?? '' ); ?></h3>
<div class="stable stable-6" role="table" aria-label="<?php echo esc_attr( $rmdhost_t['title'] ?? '' ); ?>">
	<div class="srow shead" role="row">
		<?php foreach ( (array) ( $rmdhost_t['columns'] ?? $rmdhost_l ) as $rmdhost_c ) : ?>
			<span role="columnheader"><?php echo esc_html( $rmdhost_c ); ?></span>
		<?php endforeach; ?>
		<span role="columnheader"><span class="sr-only"><?php esc_html_e( 'Order', 'rmdhost' ); ?></span></span>
	</div>
	<?php foreach ( (array) $rmdhost_t['rows'] as $rmdhost_r ) : ?>
		<?php
		$rmdhost_r = RMDHost\Data::normalize_plan(
			$rmdhost_r + array(
				'id'      => strtolower( $rmdhost_r['name'] ?? '' ),
				'network' => '',
			)
		);
		?>
		<div class="srow" role="row">
			<span role="cell" data-l="<?php echo esc_attr( $rmdhost_l[0] ); ?>"><b><?php echo esc_html( $rmdhost_r['name'] ); ?></b></span>
			<span role="cell" data-l="<?php echo esc_attr( $rmdhost_l[1] ); ?>"><?php echo esc_html( $rmdhost_r['cpu'] ); ?></span>
			<span role="cell" data-l="<?php echo esc_attr( $rmdhost_l[2] ); ?>"><?php echo esc_html( $rmdhost_r['storage'] ); ?></span>
			<span role="cell" data-l="<?php echo esc_attr( $rmdhost_l[3] ); ?>"><?php echo esc_html( $rmdhost_r['ram'] ); ?></span>
			<span role="cell" data-l="<?php echo esc_attr( $rmdhost_l[4] ); ?>"><?php echo esc_html( $rmdhost_r['network'] ); ?></span>
			<span role="cell" data-l="<?php echo esc_attr( $rmdhost_l[5] ); ?>" class="sprice"><?php echo rmdhost_money( $rmdhost_r['price'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span role="cell"><a class="btn btn-outline btn-sm" href="<?php echo esc_url( $rmdhost_r['order'] ); ?>"><?php esc_html_e( 'Order now', 'rmdhost' ); ?></a></span>
		</div>
	<?php endforeach; ?>
</div>
