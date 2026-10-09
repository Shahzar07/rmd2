<?php
/**
 * Customizer: everything a site owner edits without code.
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer settings.
 */
class Customizer {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'customize_register', array( __CLASS__, 'register' ) );
	}

	/**
	 * Homepage sections that can be toggled, in display order.
	 *
	 * @return array slug => label
	 */
	public static function home_sections() {
		return array(
			'hero'         => __( 'Hero + offer banner', 'rmdhost' ),
			'promo'        => __( 'Promo grid', 'rmdhost' ),
			'finder'       => __( 'Server finder', 'rmdhost' ),
			'tools'        => __( 'Feature tabs', 'rmdhost' ),
			'essentials'   => __( 'Essentials cards', 'rmdhost' ),
			'alt'          => __( 'Alternating highlights', 'rmdhost' ),
			'support'      => __( 'Support cards', 'rmdhost' ),
			'pricing'      => __( 'Pricing tabs', 'rmdhost' ),
			'automation'   => __( 'Automation + product carousel', 'rmdhost' ),
			'locations'    => __( 'Data-centre map + stats', 'rmdhost' ),
			'testimonials' => __( 'Testimonials wall', 'rmdhost' ),
			'faq'          => __( 'FAQ', 'rmdhost' ),
			'cta'          => __( 'Closing call to action', 'rmdhost' ),
		);
	}

	/**
	 * Register panels, sections, settings and controls.
	 *
	 * @param \WP_Customize_Manager $wp Manager.
	 */
	public static function register( $wp ) {
		$hero = rmdhost_section_defaults( 'hero' );

		$wp->add_panel(
			'rmdhost',
			array(
				'title'    => __( 'RMDHost Theme', 'rmdhost' ),
				'priority' => 20,
			)
		);

		// General / client area.
		self::section( $wp, 'rmdhost_general', __( 'Client area & links', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_general', 'login_url', __( 'Client area login URL (account icon)', 'rmdhost' ), Data::setting( 'login_url' ), 'url' );
		self::field( $wp, 'rmdhost_general', 'order_fallback', __( 'Default order URL (plans without their own link)', 'rmdhost' ), Data::setting( 'order_fallback' ), 'url' );
		self::field( $wp, 'rmdhost_general', 'domain_search', __( 'Domain search URL (query is appended)', 'rmdhost' ), Data::setting( 'domain_search' ), 'url' );
		self::field( $wp, 'rmdhost_general', 'ticket_url', __( 'Open ticket URL', 'rmdhost' ), Data::setting( 'ticket_url' ), 'url' );
		self::field( $wp, 'rmdhost_general', 'email', __( 'Support email', 'rmdhost' ), Data::setting( 'email' ), 'email' );
		self::field( $wp, 'rmdhost_general', 'sales_email', __( 'Sales email', 'rmdhost' ), Data::setting( 'sales_email' ), 'email' );
		self::field( $wp, 'rmdhost_general', 'status_url', __( 'Header "Status" link', 'rmdhost' ), '/network-status/', 'text' );

		// Header.
		self::section( $wp, 'rmdhost_header', __( 'Header', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_header', 'domainbar', __( 'Show domain search bar', 'rmdhost' ), true, 'checkbox' );
		self::field( $wp, 'rmdhost_header', 'domain_placeholder', __( 'Domain search placeholder', 'rmdhost' ), __( 'Type the domain you want', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_header', 'domain_note', __( 'Note beside the domain bar (HTML allowed: strong, br)', 'rmdhost' ), __( '<strong>No setup fees</strong><br>on VPS &amp; in-stock servers', 'rmdhost' ), 'textarea' );
		self::field( $wp, 'rmdhost_header', 'show_status', __( 'Show "Status" button', 'rmdhost' ), true, 'checkbox' );
		self::field( $wp, 'rmdhost_header', 'show_currency', __( 'Show currency switcher', 'rmdhost' ), true, 'checkbox' );
		self::field( $wp, 'rmdhost_header', 'show_theme_toggle', __( 'Show light/dark toggle', 'rmdhost' ), true, 'checkbox' );

		// Currency.
		self::section( $wp, 'rmdhost_currency', __( 'Currency', 'rmdhost' ) );
		$wp->add_setting(
			'rmdhost_currency',
			array(
				'default'           => 'GBP',
				'sanitize_callback' => array( __CLASS__, 'sanitize_currency' ),
			)
		);
		$wp->add_control(
			'rmdhost_currency',
			array(
				'label'   => __( 'Default currency', 'rmdhost' ),
				'section' => 'rmdhost_currency',
				'type'    => 'select',
				'choices' => array(
					'GBP' => 'GBP (£)',
					'USD' => 'USD ($)',
				),
			)
		);
		self::field( $wp, 'rmdhost_currency', 'usd_rate', __( 'GBP → USD rate (plans can set their own USD price)', 'rmdhost' ), 1.27, 'number' );

		// Hero.
		self::section( $wp, 'rmdhost_hero', __( 'Homepage hero', 'rmdhost' ) );
		foreach (
			array(
				'eyebrow'      => array( __( 'Eyebrow', 'rmdhost' ), 'text' ),
				'headline'     => array( __( 'Headline', 'rmdhost' ), 'text' ),
				'text'         => array( __( 'Benefit text', 'rmdhost' ), 'textarea' ),
				'offer_label'  => array( __( 'Offer label', 'rmdhost' ), 'text' ),
				'offer_price'  => array( __( 'Offer price (GBP, empty = hide)', 'rmdhost' ), 'number' ),
				'offer_suffix' => array( __( 'Offer suffix', 'rmdhost' ), 'text' ),
				'offer_note'   => array( __( 'Offer note', 'rmdhost' ), 'text' ),
				'cta1_label'   => array( __( 'Primary button label', 'rmdhost' ), 'text' ),
				'cta1_url'     => array( __( 'Primary button link', 'rmdhost' ), 'text' ),
				'cta2_label'   => array( __( 'Secondary button label', 'rmdhost' ), 'text' ),
				'cta2_url'     => array( __( 'Secondary button link', 'rmdhost' ), 'text' ),
			) as $key => $def
		) {
			self::field( $wp, 'rmdhost_hero', 'hero_' . $key, $def[0], $hero[ $key ] ?? '', $def[1] );
		}

		// Offer & review banner.
		self::section( $wp, 'rmdhost_banner', __( 'Offer & review banner', 'rmdhost' ), __( 'Review figures must match the review source exactly. Leave the score empty to show a neutral icon and link only.', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_banner', 'banner_enabled', __( 'Show banner', 'rmdhost' ), $hero['banner'], 'checkbox' );
		self::field( $wp, 'rmdhost_banner', 'banner_tag', __( 'Offer tag', 'rmdhost' ), $hero['banner_tag'] );
		self::field( $wp, 'rmdhost_banner', 'banner_text', __( 'Offer text', 'rmdhost' ), $hero['banner_text'], 'textarea' );
		self::field( $wp, 'rmdhost_banner', 'banner_cta_label', __( 'Offer link label', 'rmdhost' ), $hero['banner_cta'] );
		self::field( $wp, 'rmdhost_banner', 'banner_cta_url', __( 'Offer link URL', 'rmdhost' ), $hero['banner_cta_url'] );
		self::field( $wp, 'rmdhost_banner', 'rating_label', __( 'Review source label', 'rmdhost' ), $hero['rating_label'] );
		self::field( $wp, 'rmdhost_banner', 'rating_note', __( 'Review note', 'rmdhost' ), $hero['rating_note'] );
		self::field( $wp, 'rmdhost_banner', 'rating_score', __( 'Review score (empty = hide)', 'rmdhost' ), $hero['rating_score'], 'number' );
		self::field( $wp, 'rmdhost_banner', 'rating_scale', __( 'Score scale', 'rmdhost' ), $hero['rating_scale'], 'number' );
		self::field( $wp, 'rmdhost_banner', 'rating_count', __( 'Number of reviews (empty = hide)', 'rmdhost' ), $hero['rating_count'], 'number' );
		self::field( $wp, 'rmdhost_banner', 'rating_url', __( 'Review page URL', 'rmdhost' ), $hero['rating_url'], 'url' );
		self::field( $wp, 'rmdhost_banner', 'rating_linktext', __( 'Review link text', 'rmdhost' ), $hero['rating_linktext'] );

		// Stats.
		self::section( $wp, 'rmdhost_stats', __( 'Hero stats row', 'rmdhost' ) );
		$home_stats = Data::catalog()['homepage']['stats'] ?? array();
		for ( $i = 0; $i < 4; $i++ ) {
			/* translators: %d: stat number */
			self::field( $wp, 'rmdhost_stats', "stat_{$i}_value", sprintf( __( 'Stat %d value', 'rmdhost' ), $i + 1 ), $home_stats[ $i ]['value'] ?? '' );
			/* translators: %d: stat number */
			self::field( $wp, 'rmdhost_stats', "stat_{$i}_label", sprintf( __( 'Stat %d label', 'rmdhost' ), $i + 1 ), $home_stats[ $i ]['label'] ?? '' );
		}

		// Homepage sections on/off.
		self::section( $wp, 'rmdhost_sections', __( 'Homepage sections', 'rmdhost' ), __( 'Used when the front page is not built with Elementor or blocks.', 'rmdhost' ) );
		foreach ( self::home_sections() as $slug => $label ) {
			self::field( $wp, 'rmdhost_sections', 'show_' . $slug, $label, true, 'checkbox' );
		}

		// Footer.
		self::section( $wp, 'rmdhost_footer', __( 'Footer', 'rmdhost' ) );
		/* translators: %s: site name */
		self::field( $wp, 'rmdhost_footer', 'copyright', __( 'Copyright text ({year} is replaced)', 'rmdhost' ), sprintf( __( '© {year} %s – High-performance VPS & dedicated servers.', 'rmdhost' ), get_bloginfo( 'name' ) ) );
		self::field( $wp, 'rmdhost_footer', 'footer_note', __( 'Right-hand note', 'rmdhost' ), __( 'Prices are listed without VAT.', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_footer', 'payments', __( 'Payment methods (comma separated)', 'rmdhost' ), 'VISA, Mastercard, AMEX, PayPal, Bank transfer, Crypto' );
		foreach ( (array) ( Data::catalog()['site']['social'] ?? array() ) as $net => $url ) {
			/* translators: %s: social network */
			self::field( $wp, 'rmdhost_footer', 'social_' . $net, sprintf( __( '%s URL', 'rmdhost' ), ucfirst( $net ) ), $url, 'url' );
		}

		// Cookie banner.
		self::section( $wp, 'rmdhost_cookies', __( 'Cookie banner', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_cookies', 'cookie_enabled', __( 'Show cookie consent banner', 'rmdhost' ), true, 'checkbox' );
		self::field( $wp, 'rmdhost_cookies', 'cookie_title', __( 'Title', 'rmdhost' ), __( 'We care about your privacy', 'rmdhost' ) );
		self::field( $wp, 'rmdhost_cookies', 'cookie_text', __( 'Text', 'rmdhost' ), __( 'We use cookies that are needed for the site to work, plus optional cookies for analytics and marketing. You can accept all, reject optional cookies or choose your preferences.', 'rmdhost' ), 'textarea' );

		// Colours.
		$wp->add_setting(
			'rmdhost_signal',
			array(
				'default'           => '#3ddc84',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);
		$wp->add_control(
			new \WP_Customize_Color_Control(
				$wp,
				'rmdhost_signal',
				array(
					'label'   => __( 'Signal accent colour', 'rmdhost' ),
					'section' => 'colors',
				)
			)
		);
	}

	/**
	 * Add a section to the theme panel.
	 *
	 * @param \WP_Customize_Manager $wp          Manager.
	 * @param string                $id          ID.
	 * @param string                $title       Title.
	 * @param string                $description Description.
	 */
	private static function section( $wp, $id, $title, $description = '' ) {
		$wp->add_section(
			$id,
			array(
				'title'       => $title,
				'panel'       => 'rmdhost',
				'description' => $description,
			)
		);
	}

	/**
	 * Add a setting + control.
	 *
	 * @param \WP_Customize_Manager $wp      Manager.
	 * @param string                $section Section.
	 * @param string                $key     Key without prefix.
	 * @param string                $label   Label.
	 * @param mixed                 $fallback Default.
	 * @param string                $type    text|textarea|url|email|number|checkbox.
	 */
	private static function field( $wp, $section, $key, $label, $fallback, $type = 'text' ) {
		$sanitizers = array(
			'text'     => 'sanitize_text_field',
			'textarea' => array( __CLASS__, 'sanitize_inline_html' ),
			'url'      => array( __CLASS__, 'sanitize_link' ),
			'email'    => 'sanitize_email',
			'number'   => array( __CLASS__, 'sanitize_number' ),
			'checkbox' => array( __CLASS__, 'sanitize_checkbox' ),
		);
		$wp->add_setting(
			'rmdhost_' . $key,
			array(
				'default'           => $fallback,
				'sanitize_callback' => $sanitizers[ $type ],
			)
		);
		$wp->add_control(
			'rmdhost_' . $key,
			array(
				'label'   => $label,
				'section' => $section,
				'type'    => 'url' === $type ? 'text' : $type,
			)
		);
	}

	/**
	 * Allow simple inline HTML.
	 *
	 * @param string $value Value.
	 * @return string
	 */
	public static function sanitize_inline_html( $value ) {
		return rmdhost_kses_inline( $value );
	}

	/**
	 * URL, site-relative path or anchor.
	 *
	 * @param string $value Value.
	 * @return string
	 */
	public static function sanitize_link( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value || '#' === $value[0] || '/' === $value[0] ) {
			return sanitize_text_field( $value );
		}
		return esc_url_raw( $value );
	}

	/**
	 * Number or empty string.
	 *
	 * @param mixed $value Value.
	 * @return string
	 */
	public static function sanitize_number( $value ) {
		return is_numeric( $value ) ? (string) ( 0 + $value ) : '';
	}

	/**
	 * Checkbox.
	 *
	 * @param mixed $value Value.
	 * @return bool
	 */
	public static function sanitize_checkbox( $value ) {
		return (bool) $value;
	}

	/**
	 * Currency code.
	 *
	 * @param string $value Value.
	 * @return string
	 */
	public static function sanitize_currency( $value ) {
		return in_array( $value, array( 'GBP', 'USD' ), true ) ? $value : 'GBP';
	}
}
