<?php
/**
 * Elementor compatibility (theme locations, page width, editor tweaks).
 * The RMDHost widgets themselves ship in the RMDHost Core plugin.
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor integration.
 */
class Elementor {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'elementor/theme/register_locations', array( __CLASS__, 'locations' ) );
		add_action( 'after_switch_theme', array( __CLASS__, 'defaults' ) );
	}

	/**
	 * Allow Elementor Pro Theme Builder to replace header, footer, single and archive.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Locations manager.
	 */
	public static function locations( $manager ) {
		$manager->register_all_core_location();
	}

	/**
	 * Sensible Elementor defaults on theme activation: enable pages/posts,
	 * use theme fonts/colours instead of Elementor's globals.
	 */
	public static function defaults() {
		if ( false === get_option( 'elementor_cpt_support', false ) ) {
			update_option( 'elementor_cpt_support', array( 'page', 'post' ) );
		}
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
		if ( false === get_option( 'elementor_container_width', false ) ) {
			update_option( 'elementor_container_width', 1184 );
		}
	}

	/**
	 * Render an Elementor Pro theme location if one is assigned.
	 *
	 * @param string $location header|footer|single|archive.
	 * @return bool True if Elementor rendered the location.
	 */
	public static function location( $location ) {
		return function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location );
	}
}
