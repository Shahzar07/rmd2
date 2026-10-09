<?php
/**
 * Front-end assets.
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Scripts and styles.
 */
class Assets {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
		add_action( 'wp_head', array( __CLASS__, 'early_head' ), 0 );
		add_filter( 'wp_resource_hints', array( __CLASS__, 'resource_hints' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'editor_fonts' ) );
	}

	/**
	 * Google Fonts URL (DM Sans + JetBrains Mono).
	 *
	 * @return string
	 */
	public static function fonts_url() {
		$url = 'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500;600&display=swap';
		/**
		 * Filters the web font stylesheet URL. Return '' to self-host fonts.
		 *
		 * @param string $url Fonts URL.
		 */
		return (string) apply_filters( 'rmdhost/fonts_url', $url );
	}

	/**
	 * Enqueue theme assets.
	 */
	public static function enqueue() {
		$fonts = self::fonts_url();
		if ( $fonts ) {
			wp_enqueue_style( 'rmdhost-fonts', $fonts, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts.
		}
		wp_enqueue_style( 'rmdhost-main', RMDHOST_URI . '/assets/css/main.css', array(), RMDHOST_VERSION );
		wp_enqueue_style( 'rmdhost-wordpress', RMDHOST_URI . '/assets/css/wordpress.css', array( 'rmdhost-main' ), RMDHOST_VERSION );

		$accent = get_theme_mod( 'rmdhost_signal', '#3ddc84' );
		if ( '#3ddc84' !== $accent && sanitize_hex_color( $accent ) ) {
			wp_add_inline_style( 'rmdhost-main', ':root{--signal:' . sanitize_hex_color( $accent ) . ';}' );
		}

		wp_enqueue_script(
			'rmdhost-main',
			RMDHOST_URI . '/assets/js/main.js',
			array(),
			RMDHOST_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
		wp_enqueue_script(
			'rmdhost-wordpress',
			RMDHOST_URI . '/assets/js/wordpress.js',
			array( 'rmdhost-main' ),
			RMDHOST_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
		$config = array(
			'home'     => untrailingslashit( home_url() ),
			'i18n'     => array(
				'sent'  => __( 'Thanks – your message has been sent. We’ll reply shortly.', 'rmdhost' ),
				'error' => __( 'Something went wrong. Please email us instead.', 'rmdhost' ),
			),
			'currency' => array(
				'default' => Data::setting( 'currency' ),
				'list'    => array(
					array(
						'code'   => 'GBP',
						'symbol' => '£',
						'rate'   => 1,
					),
					array(
						'code'   => 'USD',
						'symbol' => '$',
						'rate'   => (float) Data::setting( 'usd_rate' ),
					),
				),
			),
		);
		wp_add_inline_script( 'rmdhost-main', 'window.RMD=' . wp_json_encode( $config ) . ';', 'before' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Apply the saved light/dark preference before paint (avoids a flash).
	 */
	public static function early_head() {
		wp_print_inline_script_tag( "(function(){try{var t=localStorage.getItem('rmd-theme');if(t)document.documentElement.setAttribute('data-theme',t);}catch(e){}})();" );
		echo '<meta name="theme-color" content="#000000">' . "\n";
	}

	/**
	 * Preconnect to font hosts.
	 *
	 * @param array  $urls          URLs.
	 * @param string $relation_type Relation.
	 * @return array
	 */
	public static function resource_hints( $urls, $relation_type ) {
		if ( 'preconnect' === $relation_type && self::fonts_url() ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}
		return $urls;
	}

	/**
	 * Fonts inside the block editor.
	 */
	public static function editor_fonts() {
		$fonts = self::fonts_url();
		if ( $fonts ) {
			wp_enqueue_style( 'rmdhost-editor-fonts', $fonts, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts.
		}
	}
}
