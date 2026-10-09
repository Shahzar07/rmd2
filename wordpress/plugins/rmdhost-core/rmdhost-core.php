<?php
/**
 * Plugin Name:       RMDHost Core
 * Plugin URI:        https://rmdhost.com/
 * Description:       Companion plugin for the RMDHost theme: editable server plans, data-centre locations, reviews and FAQs, Elementor widgets, a "RMDHost section" block, one-click demo import and the contact form.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            RMDHost
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       rmdhost-core
 * Domain Path:       /languages
 *
 * @package RMDHost_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'RMDHOST_CORE_VERSION', '1.0.0' );
define( 'RMDHOST_CORE_FILE', __FILE__ );
define( 'RMDHOST_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'RMDHOST_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once RMDHOST_CORE_DIR . 'includes/class-post-types.php';
require_once RMDHOST_CORE_DIR . 'includes/class-render.php';
require_once RMDHOST_CORE_DIR . 'includes/class-schema.php';
require_once RMDHOST_CORE_DIR . 'includes/class-contact.php';
require_once RMDHOST_CORE_DIR . 'includes/class-blocks.php';
require_once RMDHOST_CORE_DIR . 'includes/class-elementor.php';
require_once RMDHOST_CORE_DIR . 'includes/class-importer.php';

add_action(
	'plugins_loaded',
	static function () {
		load_plugin_textdomain( 'rmdhost-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		RMDHost_Core\Post_Types::init();
		RMDHost_Core\Render::init();
		RMDHost_Core\Contact::init();
		RMDHost_Core\Blocks::init();
		RMDHost_Core\Elementor::init();
		RMDHost_Core\Importer::init();
	}
);

register_activation_hook(
	__FILE__,
	static function () {
		RMDHost_Core\Post_Types::register();
		flush_rewrite_rules();
	}
);
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
