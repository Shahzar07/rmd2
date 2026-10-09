<?php
/**
 * Header bar, mega menu, domain search and mobile drawer.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_nav      = RMDHost\Menu::primary();
$rmdhost_login    = RMDHost\Data::setting( 'login_url' );
$rmdhost_status   = rmdhost_url( get_theme_mod( 'rmdhost_status_url', '/network-status/' ) );
$rmdhost_currency = get_theme_mod( 'rmdhost_show_currency', true );
$rmdhost_toggle   = get_theme_mod( 'rmdhost_show_theme_toggle', true );
$rmdhost_search   = RMDHost\Data::setting( 'domain_search' );
$rmdhost_search_q = wp_parse_url( $rmdhost_search, PHP_URL_QUERY );
parse_str( (string) $rmdhost_search_q, $rmdhost_search_args );
?>
<header class="site-header" data-header>
	<div class="container header-row">
		<?php echo rmdhost_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rmdhost_logo(). ?>
		<nav class="nav" aria-label="<?php esc_attr_e( 'Main', 'rmdhost' ); ?>">
			<ul>
				<?php foreach ( $rmdhost_nav as $rmdhost_i => $rmdhost_item ) : ?>
					<?php if ( ! empty( $rmdhost_item['mega'] ) ) : ?>
						<li class="nav-item has-mega">
							<button class="nav-link" type="button" aria-expanded="false" aria-controls="mega-<?php echo (int) $rmdhost_i; ?>"><?php echo esc_html( $rmdhost_item['label'] ); ?><?php echo rmdhost_icon( 'chevron', 'nav-caret' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
							<div class="mega" id="mega-<?php echo (int) $rmdhost_i; ?>" role="region" aria-label="<?php echo esc_attr( $rmdhost_item['label'] ); ?>">
								<div class="mega-inner">
									<?php foreach ( $rmdhost_item['mega'] as $rmdhost_col ) : ?>
										<div class="mega-col">
											<p class="mega-title"><?php echo esc_html( $rmdhost_col['title'] ); ?></p>
											<ul>
												<?php foreach ( $rmdhost_col['items'] as $rmdhost_link ) : ?>
													<li><a href="<?php echo esc_url( rmdhost_url( $rmdhost_link['href'] ) ); ?>" class="mega-link">
														<span class="mega-ico"><?php echo rmdhost_icon( $rmdhost_link['icon'] ?? 'server' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
														<span><span class="mega-label"><?php echo esc_html( $rmdhost_link['label'] ); ?><?php echo ! empty( $rmdhost_link['tag'] ) ? ' <em class="pill pill-xs">' . esc_html( $rmdhost_link['tag'] ) . '</em>' : ''; ?></span><span class="mega-desc"><?php echo esc_html( $rmdhost_link['desc'] ?? '' ); ?></span></span>
													</a></li>
												<?php endforeach; ?>
											</ul>
										</div>
									<?php endforeach; ?>
									<?php
									if ( ! empty( $rmdhost_item['promo'] ) ) :
										$rmdhost_p = $rmdhost_item['promo'];
										?>
										<a class="mega-promo" href="<?php echo esc_url( rmdhost_url( $rmdhost_p['href'] ) ); ?>">
											<?php echo RMDHost\Menu::promo_visual( $rmdhost_p['visual'] ?? '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static markup. ?>
											<span class="pill pill-invert"><?php echo esc_html( $rmdhost_p['eyebrow'] ); ?></span>
											<strong><?php echo esc_html( $rmdhost_p['title'] ); ?></strong>
											<span><?php echo esc_html( $rmdhost_p['text'] ); ?></span>
											<span class="link-arrow"><?php echo esc_html( $rmdhost_p['cta'] ); ?> <?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</li>
					<?php else : ?>
						<li class="nav-item"><a class="nav-link" href="<?php echo esc_url( rmdhost_url( $rmdhost_item['href'] ) ); ?>"><?php echo esc_html( $rmdhost_item['label'] ); ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</nav>
		<div class="header-actions">
			<?php if ( get_theme_mod( 'rmdhost_show_status', true ) ) : ?>
				<a class="chip-btn hide-sm" href="<?php echo esc_url( $rmdhost_status ); ?>"><?php echo rmdhost_icon( 'pulse' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Status', 'rmdhost' ); ?></span></a>
			<?php endif; ?>
			<?php
			if ( $rmdhost_currency ) {
				echo rmdhost_currency_select( 'currency-desktop' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			if ( $rmdhost_toggle ) {
				echo rmdhost_theme_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			do_action( 'rmdhost/header_actions' );
			?>
			<a class="icon-btn" href="<?php echo esc_url( $rmdhost_login ); ?>" aria-label="<?php esc_attr_e( 'Client area login', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<button class="icon-btn menu-btn" type="button" data-menu-open aria-label="<?php esc_attr_e( 'Open menu', 'rmdhost' ); ?>" aria-controls="drawer" aria-expanded="false"><?php echo rmdhost_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</div>
	</div>
	<?php if ( get_theme_mod( 'rmdhost_domainbar', true ) && $rmdhost_search ) : ?>
		<div class="container domainbar-row">
			<form class="domainbar" action="<?php echo esc_url( strtok( $rmdhost_search, '?' ) ); ?>" method="get" role="search">
				<?php foreach ( (array) $rmdhost_search_args as $rmdhost_k => $rmdhost_v ) : ?>
					<?php if ( 'query' !== $rmdhost_k ) : ?>
						<input type="hidden" name="<?php echo esc_attr( $rmdhost_k ); ?>" value="<?php echo esc_attr( $rmdhost_v ); ?>">
					<?php endif; ?>
				<?php endforeach; ?>
				<?php echo rmdhost_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<label class="sr-only" for="dq"><?php esc_html_e( 'Search for a domain', 'rmdhost' ); ?></label>
				<input id="dq" name="query" type="text" placeholder="<?php echo esc_attr( get_theme_mod( 'rmdhost_domain_placeholder', __( 'Type the domain you want', 'rmdhost' ) ) ); ?>" autocomplete="off">
				<button class="btn btn-sm btn-white" type="submit"><?php esc_html_e( 'Search', 'rmdhost' ); ?></button>
			</form>
			<p class="domainbar-note"><?php echo rmdhost_kses_inline( get_theme_mod( 'rmdhost_domain_note', __( '<strong>No setup fees</strong><br>on VPS &amp; in-stock servers', 'rmdhost' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
	<?php endif; ?>
</header>
<div class="drawer" id="drawer" data-drawer hidden>
	<div class="drawer-head"><?php echo rmdhost_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><button class="icon-btn" type="button" data-menu-close aria-label="<?php esc_attr_e( 'Close menu', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button></div>
	<nav class="drawer-nav" aria-label="<?php esc_attr_e( 'Mobile', 'rmdhost' ); ?>">
		<?php foreach ( $rmdhost_nav as $rmdhost_item ) : ?>
			<?php if ( ! empty( $rmdhost_item['mega'] ) ) : ?>
				<details class="m-group"><summary><?php echo esc_html( $rmdhost_item['label'] ); ?><?php echo rmdhost_icon( 'chevron' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></summary>
					<?php foreach ( $rmdhost_item['mega'] as $rmdhost_col ) : ?>
						<p class="m-title"><?php echo esc_html( $rmdhost_col['title'] ); ?></p>
						<?php foreach ( $rmdhost_col['items'] as $rmdhost_link ) : ?>
							<a href="<?php echo esc_url( rmdhost_url( $rmdhost_link['href'] ) ); ?>"><?php echo rmdhost_icon( $rmdhost_link['icon'] ?? 'server' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $rmdhost_link['label'] ); ?></a>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</details>
			<?php else : ?>
				<a class="m-link" href="<?php echo esc_url( rmdhost_url( $rmdhost_item['href'] ) ); ?>"><?php echo esc_html( $rmdhost_item['label'] ); ?></a>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if ( get_theme_mod( 'rmdhost_show_status', true ) ) : ?>
			<a class="m-link" href="<?php echo esc_url( $rmdhost_status ); ?>"><?php esc_html_e( 'Network status', 'rmdhost' ); ?></a>
		<?php endif; ?>
	</nav>
	<div class="drawer-foot">
		<?php
		if ( $rmdhost_currency ) {
			echo rmdhost_currency_select( 'currency-mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		if ( $rmdhost_toggle ) {
			echo rmdhost_theme_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
		<a class="btn btn-primary btn-block" href="<?php echo esc_url( $rmdhost_login ); ?>"><?php esc_html_e( 'Client area', 'rmdhost' ); ?></a>
	</div>
</div>
