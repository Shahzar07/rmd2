<?php
/**
 * Public, read-only export of published server packages for static builds.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/** Catalogue endpoint. */
class Catalogue {
	/** Register routes. */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'routes' ) );
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'settings' ) );
		add_action( 'save_post_rmd_plan', array( __CLASS__, 'changed' ), 30 );
		add_action( 'rmdhost_catalogue_rebuild', array( __CLASS__, 'rebuild' ) );
	}

	/** Register the deploy-hook setting for administrators. */
	public static function settings() {
		register_setting( 'rmdhost_static', 'rmdhost_deploy_hook', array( 'type' => 'string', 'sanitize_callback' => 'esc_url_raw', 'show_in_rest' => false ) );
	}

	/** Add the static build settings page. */
	public static function menu() {
		add_submenu_page( 'rmdhost-core', __( 'Static build', 'rmdhost-core' ), __( 'Static build', 'rmdhost-core' ), 'manage_options', 'rmdhost-static', array( __CLASS__, 'page' ) );
	}

	/** Render administrator settings. */
	public static function page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap"><h1><?php esc_html_e( 'RMDHost static build', 'rmdhost-core' ); ?></h1>
		<p><?php esc_html_e( 'Set WP_URL to this WordPress site in your hosting build environment. Published server plans are loaded at build time. Enter a Vercel or Netlify deploy hook to rebuild after saving a plan.', 'rmdhost-core' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'rmdhost_static' ); ?>
			<p><label for="rmdhost_deploy_hook"><?php esc_html_e( 'Deploy hook URL', 'rmdhost-core' ); ?></label></p>
			<input type="password" id="rmdhost_deploy_hook" name="rmdhost_deploy_hook" class="large-text" autocomplete="off" value="<?php echo esc_attr( get_option( 'rmdhost_deploy_hook', '' ) ); ?>">
			<?php submit_button(); ?>
		</form></div>
		<?php
	}

	/**
	 * Debounce rebuilds so plan metadata has been saved before fetching it.
	 *
	 * @param int $post_id Plan ID.
	 */
	public static function changed( $post_id ) {
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( get_option( 'rmdhost_deploy_hook' ) && ! wp_next_scheduled( 'rmdhost_catalogue_rebuild' ) ) {
			wp_schedule_single_event( time() + 10, 'rmdhost_catalogue_rebuild' );
		}
	}

	/** Call the administrator-configured deploy hook. */
	public static function rebuild() {
		$url = get_option( 'rmdhost_deploy_hook', '' );
		if ( $url ) {
			wp_safe_remote_post( $url, array( 'timeout' => 10, 'blocking' => false ) );
		}
	}

	/** Public product data only; no customer records or settings. */
	public static function routes() {
		register_rest_route(
			'rmdhost/v1',
			'/catalogue',
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'read' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Export the same plans rendered by the theme.
	 *
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function read() {
		if ( ! class_exists( '\RMDHost\Data' ) ) {
			return new \WP_Error( 'rmdhost_theme_required', 'Activate the RMDHost theme.', array( 'status' => 503 ) );
		}
		$products = array();
		$fields   = array_flip( array( 'id', 'name', 'price', 'usd', 'cpu', 'ram', 'storage', 'bandwidth', 'location', 'ddos', 'badge', 'note', 'extras', 'order' ) );
		foreach ( \RMDHost\Data::groups() as $slug => $group ) {
			$plans = array();
			foreach ( \RMDHost\Data::plans( $slug ) as $plan ) {
				$plans[] = array_intersect_key( $plan, $fields );
			}
			$products[] = array( 'slug' => $slug, 'plans' => $plans );
		}
		return rest_ensure_response( array( 'products' => $products ) );
	}
}
