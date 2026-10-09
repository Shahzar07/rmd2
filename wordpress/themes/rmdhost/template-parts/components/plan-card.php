<?php
/**
 * Plan box: CPU | RAM | Storage | Bandwidth | Location | DDoS | Price | More details | Order now.
 *
 * @package RMDHost
 * @var array $args plan (normalised), featured, product.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_plan    = $args['plan'];
$rmdhost_feat    = ! empty( $args['featured'] );
$rmdhost_soon    = null === $rmdhost_plan['price'];
$rmdhost_id      = 'pd-' . sanitize_html_class( $rmdhost_plan['id'] ? $rmdhost_plan['id'] : sanitize_title( $rmdhost_plan['name'] ) ) . '-' . wp_unique_id();
$rmdhost_rows    = array(
	array( 'cpu', __( 'CPU', 'rmdhost' ), $rmdhost_plan['cpu'] ),
	array( 'memory', __( 'RAM', 'rmdhost' ), $rmdhost_plan['ram'] ),
	array( 'drive', __( 'Storage', 'rmdhost' ), $rmdhost_plan['storage'] ),
	array( 'globe', __( 'Bandwidth', 'rmdhost' ), $rmdhost_plan['bandwidth'] ),
	array( 'location', __( 'Location', 'rmdhost' ), $rmdhost_plan['location'] ),
	array( 'shield', __( 'DDoS', 'rmdhost' ), $rmdhost_plan['ddos'] ),
);
$rmdhost_contact = add_query_arg( 'topic', rawurlencode( $rmdhost_plan['name'] ), home_url( '/support/' ) );
?>
<article class="plan <?php echo $rmdhost_feat ? 'featured' : ''; ?>" data-reveal data-spot>
	<?php if ( $rmdhost_plan['badge'] ) : ?>
		<p class="plan-flag"><?php echo esc_html( $rmdhost_plan['badge'] ); ?></p>
	<?php endif; ?>
	<header class="plan-head">
		<h3><?php echo esc_html( $rmdhost_plan['name'] ); ?></h3>
		<?php if ( ! empty( $args['product'] ) ) : ?>
			<p class="plan-sub"><?php echo esc_html( $args['product'] ); ?></p>
		<?php endif; ?>
	</header>
	<div class="plan-price"><?php echo rmdhost_money( $rmdhost_plan['price'], array( 'usd' => $rmdhost_plan['usd'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<p class="plan-note"><?php echo esc_html( $rmdhost_soon ? __( 'Final pricing announced soon', 'rmdhost' ) : ( $rmdhost_plan['note'] ? $rmdhost_plan['note'] : __( 'Billed monthly · Excl. VAT', 'rmdhost' ) ) ); ?></p>
	<?php if ( $rmdhost_soon ) : ?>
		<a class="btn <?php echo $rmdhost_feat ? 'btn-white' : 'btn-outline'; ?> btn-block" href="<?php echo esc_url( $rmdhost_contact ); ?>"><?php esc_html_e( 'Contact sales', 'rmdhost' ); ?></a>
	<?php else : ?>
		<a class="btn <?php echo $rmdhost_feat ? 'btn-white' : 'btn-primary'; ?> btn-block" href="<?php echo esc_url( rmdhost_url( $rmdhost_plan['order'] ) ); ?>"><?php esc_html_e( 'Order now', 'rmdhost' ); ?></a>
	<?php endif; ?>
	<ul class="specs">
		<?php foreach ( $rmdhost_rows as $rmdhost_row ) : ?>
			<li><?php echo rmdhost_icon( $rmdhost_row[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="k"><?php echo esc_html( $rmdhost_row[1] ); ?></span><span class="v"><?php echo esc_html( $rmdhost_row[2] ); ?></span></li>
		<?php endforeach; ?>
	</ul>
	<?php if ( $rmdhost_plan['extras'] ) : ?>
		<button class="more" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $rmdhost_id ); ?>" data-more><?php esc_html_e( 'More details', 'rmdhost' ); ?> <?php echo rmdhost_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		<div class="more-panel" id="<?php echo esc_attr( $rmdhost_id ); ?>"><div><?php echo rmdhost_checklist( $rmdhost_plan['extras'], 'checks-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div>
	<?php endif; ?>
</article>
