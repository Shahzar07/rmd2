<?php
/**
 * Elementor integration: an "RMDHost" widget category with one widget per
 * theme section. Every widget renders the same template part as the theme,
 * so output is identical to the hand-built pages.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor loader.
 */
class Elementor {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'category' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'widgets' ) );
	}

	/**
	 * Widget category.
	 *
	 * @param \Elementor\Elements_Manager $manager Manager.
	 */
	public static function category( $manager ) {
		$manager->add_category(
			'rmdhost',
			array(
				'title' => __( 'RMDHost', 'rmdhost-core' ),
				'icon'  => 'eicon-server',
			)
		);
	}

	/**
	 * Register widgets.
	 *
	 * @param \Elementor\Widgets_Manager $manager Manager.
	 */
	public static function widgets( $manager ) {
		if ( ! Render::theme_ready() ) {
			return;
		}
		require_once RMDHOST_CORE_DIR . 'includes/elementor/class-section-widget.php';
		require_once RMDHOST_CORE_DIR . 'includes/elementor/widgets.php';
		foreach ( array_keys( Render::sections() ) as $slug ) {
			$class = __NAMESPACE__ . '\\Widgets\\' . str_replace( ' ', '_', ucwords( str_replace( '-', ' ', $slug ) ) );
			if ( class_exists( $class ) ) {
				$manager->register( new $class() );
			}
		}
	}
}
