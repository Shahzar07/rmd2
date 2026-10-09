<?php
/**
 * Cookie consent banner + preferences dialog (logic in main.js).
 * Consent fires a `rmd:consent` DOM event for analytics loaders.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_privacy = get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' );
?>
<div class="cookie" data-cookie hidden role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Cookie consent', 'rmdhost' ); ?>">
	<div class="cookie-inner">
		<div class="cookie-text">
			<p class="cookie-title"><?php echo rmdhost_icon( 'cookie' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( get_theme_mod( 'rmdhost_cookie_title', __( 'We care about your privacy', 'rmdhost' ) ) ); ?></p>
			<p><?php echo rmdhost_kses_inline( get_theme_mod( 'rmdhost_cookie_text', __( 'We use cookies that are needed for the site to work, plus optional cookies for analytics and marketing. You can accept all, reject optional cookies or choose your preferences.', 'rmdhost' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			printf(
				/* translators: 1: cookie policy link, 2: privacy policy link */
				esc_html__( 'Read our %1$s and %2$s.', 'rmdhost' ),
				'<a href="' . esc_url( home_url( '/cookie-policy/' ) ) . '">' . esc_html__( 'Cookie policy', 'rmdhost' ) . '</a>',
				'<a href="' . esc_url( $rmdhost_privacy ) . '">' . esc_html__( 'Privacy policy', 'rmdhost' ) . '</a>'
			);
			?>
			</p>
		</div>
		<div class="cookie-actions">
			<button type="button" class="btn btn-primary btn-sm" data-cookie-accept><?php esc_html_e( 'Accept all', 'rmdhost' ); ?></button>
			<button type="button" class="btn btn-outline btn-sm" data-cookie-reject><?php esc_html_e( 'Reject all', 'rmdhost' ); ?></button>
			<button type="button" class="btn btn-ghost btn-sm" data-cookie-manage><?php esc_html_e( 'Manage preferences', 'rmdhost' ); ?></button>
		</div>
	</div>
</div>
<dialog class="cookie-modal" data-cookie-modal aria-labelledby="ck-title">
	<form method="dialog" class="cookie-modal-inner">
		<div class="ck-head"><h2 id="ck-title"><?php esc_html_e( 'Cookie preferences', 'rmdhost' ); ?></h2><button class="icon-btn" value="cancel" aria-label="<?php esc_attr_e( 'Close', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button></div>
		<p class="muted"><?php esc_html_e( 'Choose which optional cookies we may use. Necessary cookies are always on because the site cannot work without them.', 'rmdhost' ); ?></p>
		<div class="ck-row"><div><strong><?php esc_html_e( 'Necessary', 'rmdhost' ); ?></strong><p><?php esc_html_e( 'Security, load balancing, currency and theme preferences.', 'rmdhost' ); ?></p></div><span class="switch is-locked"><input type="checkbox" checked disabled aria-label="<?php esc_attr_e( 'Necessary cookies (always on)', 'rmdhost' ); ?>"><i></i></span></div>
		<div class="ck-row"><div><strong><?php esc_html_e( 'Analytics', 'rmdhost' ); ?></strong><p><?php esc_html_e( 'Anonymous statistics that help us improve the website.', 'rmdhost' ); ?></p></div><label class="switch"><input type="checkbox" name="analytics" data-ck="analytics" aria-label="<?php esc_attr_e( 'Analytics cookies', 'rmdhost' ); ?>"><i></i></label></div>
		<div class="ck-row"><div><strong><?php esc_html_e( 'Marketing', 'rmdhost' ); ?></strong><p><?php esc_html_e( 'Used to show relevant offers on other websites.', 'rmdhost' ); ?></p></div><label class="switch"><input type="checkbox" name="marketing" data-ck="marketing" aria-label="<?php esc_attr_e( 'Marketing cookies', 'rmdhost' ); ?>"><i></i></label></div>
		<div class="ck-actions">
			<button type="button" class="btn btn-outline btn-sm" data-cookie-reject><?php esc_html_e( 'Reject all', 'rmdhost' ); ?></button>
			<button type="button" class="btn btn-outline btn-sm" data-cookie-save><?php esc_html_e( 'Save preferences', 'rmdhost' ); ?></button>
			<button type="button" class="btn btn-primary btn-sm" data-cookie-accept><?php esc_html_e( 'Accept all', 'rmdhost' ); ?></button>
		</div>
	</form>
</dialog>
