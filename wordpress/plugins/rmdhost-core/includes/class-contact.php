<?php
/**
 * Contact form handler (AJAX + no-JS fallback).
 *
 * Messages are emailed to the sales address from the theme settings and kept
 * under RMDHost → Messages. Nonce, honeypot and a per-IP rate limit protect it.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Contact form.
 */
class Contact {

	const LIMIT = 5; // Messages per IP per hour.

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'wp_ajax_rmdhost_contact', array( __CLASS__, 'ajax' ) );
		add_action( 'wp_ajax_nopriv_rmdhost_contact', array( __CLASS__, 'ajax' ) );
		add_action( 'admin_post_rmdhost_contact', array( __CLASS__, 'post' ) );
		add_action( 'admin_post_nopriv_rmdhost_contact', array( __CLASS__, 'post' ) );
	}

	/**
	 * AJAX endpoint.
	 */
	public static function ajax() {
		$result = self::handle();
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ), 400 );
		}
		wp_send_json_success( array( 'message' => __( 'Thanks – your message has been sent. We’ll reply shortly.', 'rmdhost-core' ) ) );
	}

	/**
	 * Regular form post (no JavaScript).
	 */
	public static function post() {
		$result = self::handle();
		$back   = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		wp_safe_redirect( add_query_arg( 'rmd_sent', is_wp_error( $result ) ? '0' : '1', $back ) );
		exit;
	}

	/**
	 * Validate, store and send.
	 *
	 * @return true|\WP_Error
	 */
	private static function handle() {
		if ( ! isset( $_POST['rmd_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['rmd_nonce'] ) ), 'rmdhost_contact' ) ) {
			return new \WP_Error( 'nonce', __( 'Your session expired. Please reload the page and try again.', 'rmdhost-core' ) );
		}
		if ( ! empty( $_POST['website'] ) ) { // Honeypot: pretend success.
			return true;
		}
		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$topic   = isset( $_POST['topic'] ) ? sanitize_text_field( wp_unslash( $_POST['topic'] ) ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
		if ( ! $name || ! is_email( $email ) || ! $message ) {
			return new \WP_Error( 'invalid', __( 'Please fill in your name, a valid email address and a message.', 'rmdhost-core' ) );
		}
		if ( mb_strlen( $message ) > 5000 ) {
			return new \WP_Error( 'long', __( 'Your message is too long.', 'rmdhost-core' ) );
		}

		$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$key   = 'rmd_contact_' . md5( $ip );
		$count = (int) get_transient( $key );
		if ( $count >= self::LIMIT ) {
			return new \WP_Error( 'limit', __( 'Too many messages – please try again later or email us directly.', 'rmdhost-core' ) );
		}
		set_transient( $key, $count + 1, HOUR_IN_SECONDS );

		$id = wp_insert_post(
			array(
				'post_type'    => 'rmd_message',
				'post_status'  => 'private',
				/* translators: 1: topic, 2: sender name. */
				'post_title'   => sprintf( __( '%1$s – %2$s', 'rmdhost-core' ), $topic, $name ),
				'post_content' => $message,
			),
			true
		);
		if ( ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_rmd_name', $name );
			update_post_meta( $id, '_rmd_email', $email );
			update_post_meta( $id, '_rmd_topic', $topic );
			update_post_meta( $id, '_rmd_ip', $ip );
		}

		$to = function_exists( 'rmdhost_section' ) && class_exists( '\RMDHost\Data' ) ? \RMDHost\Data::setting( 'sales_email' ) : '';
		$to = is_email( $to ) ? $to : get_option( 'admin_email' );
		/**
		 * Filters the contact form recipient.
		 *
		 * @param string $to    Email address.
		 * @param string $topic Selected topic.
		 */
		$to = apply_filters( 'rmdhost_core/contact_recipient', $to, $topic );
		/* translators: 1: site name, 2: topic. */
		$subject = sprintf( __( '[%1$s] %2$s', 'rmdhost-core' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $topic );
		$body    = sprintf( "%s: %s\n%s: %s\n%s: %s\n\n%s", __( 'Name', 'rmdhost-core' ), $name, __( 'Email', 'rmdhost-core' ), $email, __( 'Topic', 'rmdhost-core' ), $topic, $message );
		wp_mail( $to, $subject, $body, array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );

		return true;
	}
}
