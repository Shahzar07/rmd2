<?php
/**
 * Bridge to the theme's section renderer, plus the [rmdhost_section] shortcode
 * so sections work in any page builder.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Renderer.
 */
class Render {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_shortcode( 'rmdhost_section', array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * Whether the RMDHost theme (or a child theme) is active.
	 *
	 * @return bool
	 */
	public static function theme_ready() {
		return function_exists( 'rmdhost_section' ) && function_exists( 'rmdhost_sections' );
	}

	/**
	 * Section choices (slug => label).
	 *
	 * @return array
	 */
	public static function sections() {
		return self::theme_ready() ? rmdhost_sections() : array();
	}

	/**
	 * Render a section to a string.
	 *
	 * @param string $name Section slug.
	 * @param array  $args Arguments (merged over the section defaults).
	 * @return string
	 */
	public static function section( $name, $args = array() ) {
		if ( ! self::theme_ready() ) {
			return current_user_can( 'edit_posts' ) ? '<p class="rmdhost-notice">' . esc_html__( 'RMDHost sections need the RMDHost theme to be active.', 'rmdhost-core' ) . '</p>' : '';
		}
		$name = sanitize_key( $name );
		if ( ! isset( rmdhost_sections()[ $name ] ) ) {
			return '';
		}
		ob_start();
		rmdhost_section( $name, self::clean( (array) $args ) );
		return (string) ob_get_clean();
	}

	/**
	 * Drop empty values so the section defaults apply.
	 *
	 * @param array $args Args.
	 * @return array
	 */
	public static function clean( $args ) {
		return array_filter(
			$args,
			static function ( $v ) {
				return ! ( null === $v || '' === $v || array() === $v );
			}
		);
	}

	/**
	 * [rmdhost_section name="pricing" group="vps" title="…"]
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function shortcode( $atts ) {
		$atts = (array) $atts;
		$name = isset( $atts['name'] ) ? $atts['name'] : '';
		unset( $atts['name'] );
		foreach ( $atts as $key => $value ) {
			if ( in_array( $value, array( 'true', 'false' ), true ) ) {
				$atts[ $key ] = 'true' === $value;
			} elseif ( 'groups' === $key ) {
				$atts[ $key ] = array_map( 'sanitize_key', explode( ',', $value ) );
			} else {
				$atts[ $key ] = wp_kses_post( $value );
			}
		}
		return self::section( $name, $atts );
	}
}
