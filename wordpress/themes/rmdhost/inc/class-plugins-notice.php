<?php
/**
 * Recommend the companion plugin and supported plugins (dismissible notice).
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Admin notice.
 */
class Plugins_Notice {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'notice' ) );
		add_action( 'wp_ajax_rmdhost_dismiss_notice', array( __CLASS__, 'dismiss' ) );
	}

	/**
	 * Show the notice to admins until dismissed.
	 */
	public static function notice() {
		if ( ! current_user_can( 'install_plugins' ) || get_user_meta( get_current_user_id(), 'rmdhost_notice_dismissed', true ) ) {
			return;
		}
		$missing = array();
		if ( ! defined( 'RMDHOST_CORE_VERSION' ) ) {
			$missing[] = '<strong>RMDHost Core</strong> ' . esc_html__( '(server plans, Elementor widgets, blocks, demo import – included with the theme)', 'rmdhost' );
		}
		if ( ! did_action( 'elementor/loaded' ) ) {
			$missing[] = '<a href="' . esc_url( admin_url( 'plugin-install.php?s=elementor&tab=search&type=term' ) ) . '">Elementor</a>';
		}
		if ( ! class_exists( 'WooCommerce' ) ) {
			$missing[] = '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">WooCommerce</a> ' . esc_html__( '(optional)', 'rmdhost' );
		}
		if ( ! $missing ) {
			return;
		}
		printf(
			'<div class="notice notice-info is-dismissible" data-rmdhost-notice="%1$s"><p><strong>%2$s</strong> %3$s</p><ul style="list-style:disc;padding-left:20px">%4$s</ul></div>',
			esc_attr( wp_create_nonce( 'rmdhost_notice' ) ),
			esc_html__( 'RMDHost theme:', 'rmdhost' ),
			esc_html__( 'install these plugins to unlock every feature.', 'rmdhost' ),
			wp_kses_post( '<li>' . implode( '</li><li>', $missing ) . '</li>' )
		);
		wp_print_inline_script_tag( "document.addEventListener('click',function(e){var n=e.target.closest('[data-rmdhost-notice] .notice-dismiss');if(!n)return;var d=new FormData();d.append('action','rmdhost_dismiss_notice');d.append('nonce',n.parentNode.getAttribute('data-rmdhost-notice'));fetch(ajaxurl,{method:'POST',body:d,credentials:'same-origin'});});" );
	}

	/**
	 * Remember dismissal.
	 */
	public static function dismiss() {
		check_ajax_referer( 'rmdhost_notice', 'nonce' );
		update_user_meta( get_current_user_id(), 'rmdhost_notice_dismissed', 1 );
		wp_send_json_success();
	}
}
