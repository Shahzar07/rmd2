<?php
/**
 * Footer: menu columns, logo, social, payments, legal, copyright.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_social = array();
foreach ( (array) ( RMDHost\Data::catalog()['site']['social'] ?? array() ) as $rmdhost_net => $rmdhost_url ) {
	$rmdhost_url = get_theme_mod( 'rmdhost_social_' . $rmdhost_net, $rmdhost_url );
	if ( $rmdhost_url ) {
		$rmdhost_social[ $rmdhost_net ] = $rmdhost_url;
	}
}
$rmdhost_pays = array_filter( array_map( 'trim', explode( ',', get_theme_mod( 'rmdhost_payments', 'VISA, Mastercard, AMEX, PayPal, Bank transfer, Crypto' ) ) ) );
/* translators: %s: site name */
$rmdhost_copy = get_theme_mod( 'rmdhost_copyright', sprintf( __( '© {year} %s – High-performance VPS & dedicated servers.', 'rmdhost' ), get_bloginfo( 'name' ) ) );
?>
<footer class="site-footer">
	<div class="container">
		<?php if ( is_active_sidebar( 'footer-top' ) ) : ?>
			<div class="f-widgets"><?php dynamic_sidebar( 'footer-top' ); ?></div>
		<?php endif; ?>
		<div class="f-grid">
			<?php foreach ( RMDHost\Menu::footer_columns() as $rmdhost_col ) : ?>
				<div class="f-col">
					<p class="f-title"><?php echo esc_html( $rmdhost_col['title'] ); ?></p>
					<ul>
						<?php foreach ( $rmdhost_col['links'] as $rmdhost_link ) : ?>
							<li><a href="<?php echo esc_url( rmdhost_url( $rmdhost_link['href'] ) ); ?>"><?php echo esc_html( $rmdhost_link['label'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="f-mid">
			<?php echo rmdhost_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="f-social">
				<?php foreach ( $rmdhost_social as $rmdhost_net => $rmdhost_url ) : ?>
					<a href="<?php echo esc_url( $rmdhost_url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $rmdhost_net ) ); ?>" rel="noopener" target="_blank"><?php echo rmdhost_icon( $rmdhost_net ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="f-bottom">
			<div class="f-pays">
				<?php foreach ( $rmdhost_pays as $rmdhost_pay ) : ?>
					<span class="pay"><?php echo esc_html( $rmdhost_pay ); ?></span>
				<?php endforeach; ?>
			</div>
			<div class="f-legal">
				<?php
				if ( has_nav_menu( 'legal' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'legal',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				} else {
					$rmdhost_privacy = get_privacy_policy_url();
					foreach (
						array(
							array( __( 'Privacy policy', 'rmdhost' ), $rmdhost_privacy ? $rmdhost_privacy : home_url( '/privacy-policy/' ) ),
							array( __( 'Terms of service', 'rmdhost' ), home_url( '/terms-of-service/' ) ),
							array( __( 'Cookie policy', 'rmdhost' ), home_url( '/cookie-policy/' ) ),
						) as $rmdhost_l
					) {
						echo '<a href="' . esc_url( $rmdhost_l[1] ) . '">' . esc_html( $rmdhost_l[0] ) . '</a>';
					}
				}
				?>
				<button type="button" class="linklike" data-cookie-manage><?php esc_html_e( 'Cookie settings', 'rmdhost' ); ?></button>
			</div>
		</div>
		<div class="f-copy">
			<p><?php echo esc_html( str_replace( '{year}', gmdate( 'Y' ), $rmdhost_copy ) ); ?></p>
			<p><?php echo esc_html( get_theme_mod( 'rmdhost_footer_note', __( 'Prices are listed without VAT.', 'rmdhost' ) ) ); ?></p>
		</div>
	</div>
</footer>
