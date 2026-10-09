<?php
/**
 * RMDHost theme bootstrap.
 *
 * All logic lives in /inc. Keep this file small.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

define( 'RMDHOST_VERSION', '1.0.0' );
define( 'RMDHOST_DIR', get_template_directory() );
define( 'RMDHOST_URI', get_template_directory_uri() );

require_once RMDHOST_DIR . '/inc/template-tags.php';
require_once RMDHOST_DIR . '/inc/defaults.php';
require_once RMDHOST_DIR . '/inc/class-data.php';
require_once RMDHOST_DIR . '/inc/class-setup.php';
require_once RMDHOST_DIR . '/inc/class-assets.php';
require_once RMDHOST_DIR . '/inc/class-customizer.php';
require_once RMDHOST_DIR . '/inc/class-menu.php';
require_once RMDHOST_DIR . '/inc/class-meta-boxes.php';
require_once RMDHOST_DIR . '/inc/class-seo.php';
require_once RMDHOST_DIR . '/inc/class-elementor.php';
require_once RMDHOST_DIR . '/inc/class-plugins-notice.php';

RMDHost\Setup::init();
RMDHost\Assets::init();
RMDHost\Customizer::init();
RMDHost\Menu::init();
RMDHost\Meta_Boxes::init();
RMDHost\SEO::init();
RMDHost\Elementor::init();
RMDHost\Plugins_Notice::init();

if ( class_exists( 'WooCommerce' ) ) {
	require_once RMDHOST_DIR . '/inc/class-woocommerce.php';
	RMDHost\WooCommerce::init();
}
