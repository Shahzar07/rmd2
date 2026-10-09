<?php
/**
 * Data layer.
 *
 * The theme ships a complete catalogue (inc/data/catalog.json) so it renders
 * fully on activation. Every getter runs through a filter; the RMDHost Core
 * plugin hooks these filters to serve editable data from the database
 * (Server Plans, Locations, Reviews, FAQs).
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Catalogue access.
 */
class Data {

	/**
	 * Cached catalogue.
	 *
	 * @var array|null
	 */
	private static $catalog = null;

	/**
	 * Bundled catalogue.
	 *
	 * @return array
	 */
	public static function catalog() {
		if ( null === self::$catalog ) {
			$json          = file_get_contents( RMDHOST_DIR . '/inc/data/catalog.json' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme file.
			self::$catalog = json_decode( (string) $json, true );
			self::$catalog = is_array( self::$catalog ) ? self::$catalog : array();
		}
		return self::$catalog;
	}

	/**
	 * Product groups (Linux VPS, Windows VPS…) keyed by slug, including landing-page content.
	 *
	 * @return array
	 */
	public static function groups() {
		$groups = array();
		foreach ( (array) ( self::catalog()['products'] ?? array() ) as $p ) {
			unset( $p['plans'] );
			$groups[ $p['slug'] ] = $p;
		}
		/**
		 * Filters the product groups.
		 *
		 * @param array $groups Groups keyed by slug.
		 */
		return apply_filters( 'rmdhost/groups', $groups );
	}

	/**
	 * Single group.
	 *
	 * @param string $slug Group slug.
	 * @return array|null
	 */
	public static function group( $slug ) {
		$groups = self::groups();
		return isset( $groups[ $slug ] ) ? $groups[ $slug ] : null;
	}

	/**
	 * Group choices for selects.
	 *
	 * @return array slug => name
	 */
	public static function group_choices() {
		return wp_list_pluck( self::groups(), 'name' );
	}

	/**
	 * Plans in a group, normalised.
	 *
	 * @param string $slug Group slug.
	 * @return array
	 */
	public static function plans( $slug ) {
		$plans = array();
		foreach ( (array) ( self::catalog()['products'] ?? array() ) as $p ) {
			if ( $p['slug'] === $slug ) {
				$plans = $p['plans'];
				break;
			}
		}
		/**
		 * Filters the plans for a group.
		 *
		 * @param array  $plans Plans.
		 * @param string $slug  Group slug.
		 */
		$plans = apply_filters( 'rmdhost/plans', $plans, $slug );
		return array_map( array( __CLASS__, 'normalize_plan' ), (array) $plans );
	}

	/**
	 * Lowest price in a group (for "From £x" labels).
	 *
	 * @param string $slug Group slug.
	 * @return float|null
	 */
	public static function from_price( $slug ) {
		$group = self::group( $slug );
		$rows  = array_merge( self::plans( $slug ), (array) ( $group['servers'] ?? array() ) );
		$all   = array_filter(
			wp_list_pluck( $rows, 'price' ),
			static function ( $v ) {
				return null !== $v && '' !== $v;
			}
		);
		return $all ? (float) min( $all ) : ( isset( $group['from'] ) && null !== $group['from'] ? (float) $group['from'] : null );
	}

	/**
	 * Normalise a plan array.
	 *
	 * @param array $plan Raw plan.
	 * @return array
	 */
	public static function normalize_plan( $plan ) {
		$plan = wp_parse_args(
			(array) $plan,
			array(
				'id'        => '',
				'name'      => '',
				'price'     => null,
				'usd'       => '',
				'badge'     => '',
				'cpu'       => '',
				'ram'       => '',
				'storage'   => '',
				'bandwidth' => '',
				'location'  => '',
				'ddos'      => '',
				'extras'    => array(),
				'order'     => '',
				'note'      => '',
			)
		);
		if ( is_string( $plan['extras'] ) ) {
			$plan['extras'] = array_filter( array_map( 'trim', explode( "\n", $plan['extras'] ) ) );
		}
		$plan['price'] = ( null === $plan['price'] || '' === $plan['price'] ) ? null : (float) $plan['price'];
		if ( ! $plan['order'] ) {
			$plan['order'] = add_query_arg( 'plan', rawurlencode( $plan['id'] ? $plan['id'] : $plan['name'] ), self::setting( 'order_fallback' ) );
		}
		return $plan;
	}

	/**
	 * Data-centre locations.
	 *
	 * @return array
	 */
	public static function locations() {
		return (array) apply_filters( 'rmdhost/locations', self::catalog()['locations'] ?? array() );
	}

	/**
	 * Customer reviews.
	 *
	 * @return array
	 */
	public static function reviews() {
		return (array) apply_filters( 'rmdhost/reviews', self::catalog()['reviews'] ?? array() );
	}

	/**
	 * FAQs for a group ("general" = homepage FAQs).
	 *
	 * @param string $group Group slug.
	 * @return array List of array( q, a ).
	 */
	public static function faqs( $group = 'general' ) {
		if ( 'general' === $group ) {
			$faqs = self::catalog()['faqs'] ?? array();
		} else {
			$g    = self::group( $group );
			$faqs = array();
			foreach ( (array) ( $g['faqs'] ?? array() ) as $f ) {
				$faqs[] = isset( $f['q'] ) ? $f : array(
					'q' => $f[0],
					'a' => $f[1],
				);
			}
		}
		return (array) apply_filters( 'rmdhost/faqs', $faqs, $group );
	}

	/**
	 * Default navigation (used when no menu is assigned).
	 *
	 * @return array
	 */
	public static function nav() {
		return (array) apply_filters( 'rmdhost/default_nav', self::catalog()['nav'] ?? array() );
	}

	/**
	 * Default footer columns (used when no footer menus are assigned).
	 *
	 * @return array
	 */
	public static function footer() {
		return (array) apply_filters( 'rmdhost/default_footer', self::catalog()['footer'] ?? array() );
	}

	/**
	 * Company stats row.
	 *
	 * @return array
	 */
	public static function stats() {
		return (array) apply_filters( 'rmdhost/stats', self::catalog()['site']['stats'] ?? array() );
	}

	/**
	 * Network status services.
	 *
	 * @return array
	 */
	public static function status_services() {
		return (array) apply_filters( 'rmdhost/status_services', self::catalog()['statusServices'] ?? array() );
	}

	/**
	 * Knowledge-base categories (cat, items[]).
	 *
	 * @return array
	 */
	public static function kb() {
		return (array) apply_filters( 'rmdhost/kb', self::catalog()['kb'] ?? array() );
	}

	/**
	 * Tutorials (title, tag, level, time, url).
	 *
	 * @return array
	 */
	public static function tutorials() {
		return (array) apply_filters( 'rmdhost/tutorials', self::catalog()['tutorials'] ?? array() );
	}

	/**
	 * Customer references (name, sector, product, sample).
	 *
	 * @return array
	 */
	public static function references() {
		return (array) apply_filters( 'rmdhost/references', self::catalog()['references'] ?? array() );
	}

	/**
	 * Theme setting from the Customizer, with catalogue defaults.
	 *
	 * @param string $key Setting key (without rmdhost_ prefix).
	 * @return mixed
	 */
	public static function setting( $key ) {
		$site     = self::catalog()['site'] ?? array();
		$defaults = array(
			'client_area'    => $site['clientArea'] ?? '',
			'login_url'      => $site['loginUrl'] ?? '',
			'order_fallback' => $site['orderFallback'] ?? '',
			'domain_search'  => $site['domainSearchUrl'] ?? '',
			'ticket_url'     => $site['ticketUrl'] ?? '',
			'email'          => $site['email'] ?? '',
			'sales_email'    => $site['salesEmail'] ?? '',
			'usd_rate'       => 1.27,
			'currency'       => 'GBP',
		);
		return get_theme_mod( 'rmdhost_' . $key, $defaults[ $key ] ?? '' );
	}
}
