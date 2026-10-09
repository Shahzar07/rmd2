<?php
/**
 * WooCommerce integration.
 *
 * - Theme support, wrappers and styles that match the RMDHost design.
 * - Header mini-cart with live count (cart fragments).
 * - "Server plan" specs on products (CPU, RAM, storage…), shown on the product page.
 * - Optional: sell server plans through WooCommerce. When "Plans source" is set to
 *   WooCommerce, every plan box on the site is built from products in the product
 *   category whose slug matches the product group (e.g. "vps") and "Order now"
 *   goes straight to checkout.
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce.
 */
class WooCommerce {

	const SPECS = array( 'cpu', 'ram', 'storage', 'bandwidth', 'location', 'ddos', 'badge', 'usd', 'note', 'extras' );

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'after_setup_theme', array( __CLASS__, 'support' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'assets' ), 20 );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );

		// Layout wrappers.
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
		add_action( 'woocommerce_before_main_content', array( __CLASS__, 'wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( __CLASS__, 'wrapper_end' ), 10 );
		add_filter( 'woocommerce_show_page_title', '__return_false' );
		add_filter( 'rmdhost/breadcrumbs', array( __CLASS__, 'breadcrumbs' ) );
		add_filter( 'rmdhost/content_container_class', array( __CLASS__, 'container_class' ) );

		// Loop.
		add_filter( 'loop_shop_columns', array( __CLASS__, 'columns' ) );
		add_filter( 'woocommerce_output_related_products_args', array( __CLASS__, 'related' ) );
		add_filter( 'woocommerce_upsell_display_args', array( __CLASS__, 'related' ) );

		// Header cart.
		add_action( 'rmdhost/header_actions', array( __CLASS__, 'header_cart' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'fragments' ) );

		// Plan specs on products.
		add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'spec_fields' ) );
		add_action( 'woocommerce_admin_process_product_object', array( __CLASS__, 'save_specs' ) );
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'single_specs' ), 25 );

		// Plans from WooCommerce.
		add_action( 'customize_register', array( __CLASS__, 'customize' ), 20 );
		add_filter( 'rmdhost/plans', array( __CLASS__, 'plans' ), 20, 2 );
	}

	/**
	 * Theme support.
	 */
	public static function support() {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 600,
				'single_image_width'    => 900,
				'product_grid'          => array(
					'default_rows'    => 4,
					'min_rows'        => 1,
					'default_columns' => 3,
					'min_columns'     => 1,
					'max_columns'     => 4,
				),
			)
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Styles.
	 */
	public static function assets() {
		wp_enqueue_style( 'rmdhost-woocommerce', RMDHOST_URI . '/assets/css/woocommerce.css', array( 'rmdhost-wordpress' ), RMDHOST_VERSION );
	}

	/**
	 * Body classes.
	 *
	 * @param array $classes Classes.
	 * @return array
	 */
	public static function body_class( $classes ) {
		$classes[] = 'rmd-woo';
		if ( is_active_sidebar( 'sidebar-shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
			$classes[] = 'has-shop-sidebar';
		}
		return $classes;
	}

	/**
	 * Opening wrapper: hero + section container.
	 */
	public static function wrapper_start() {
		if ( is_product() ) {
			echo '<section class="section wc-single"><div class="container">';
			echo rmdhost_breadcrumbs( rmdhost_current_trail() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
			return;
		}
		$title = woocommerce_page_title( false );
		$lede  = '';
		if ( is_product_taxonomy() ) {
			$lede = wp_strip_all_tags( term_description() );
		} elseif ( is_shop() ) {
			$shop = wc_get_page_id( 'shop' );
			$lede = $shop > 0 ? get_post_meta( $shop, '_rmd_hero_lede', true ) : '';
		}
		rmdhost_page_hero(
			array(
				'eyebrow' => __( 'Store', 'rmdhost' ),
				'title'   => $title,
				'lede'    => $lede,
				'small'   => true,
			)
		);
		$sidebar = is_active_sidebar( 'sidebar-shop' );
		echo '<section class="section wc-archive"><div class="container' . ( $sidebar ? ' with-sidebar' : '' ) . '"><div class="content-col">';
	}

	/**
	 * Closing wrapper.
	 */
	public static function wrapper_end() {
		if ( is_product() ) {
			echo '</div></section>';
			return;
		}
		echo '</div>';
		if ( is_active_sidebar( 'sidebar-shop' ) ) {
			echo '<aside class="sidebar widget-area" aria-label="' . esc_attr__( 'Shop sidebar', 'rmdhost' ) . '">';
			dynamic_sidebar( 'sidebar-shop' );
			echo '</aside>';
		}
		echo '</div></section>';
	}

	/**
	 * Shop-aware breadcrumbs.
	 *
	 * @param array $trail Trail.
	 * @return array
	 */
	public static function breadcrumbs( $trail ) {
		if ( ! is_woocommerce() ) {
			return $trail;
		}
		$shop  = wc_get_page_id( 'shop' );
		$home  = array_shift( $trail );
		$crumb = array( $home );
		if ( $shop > 0 && ! is_shop() ) {
			$crumb[] = array( get_the_title( $shop ), get_permalink( $shop ) );
		}
		if ( is_product() ) {
			$terms = wc_get_product_terms( get_the_ID(), 'product_cat', array( 'orderby' => 'parent' ) );
			if ( $terms ) {
				$crumb[] = array( $terms[0]->name, get_term_link( $terms[0] ) );
			}
			$crumb[] = array( get_the_title(), '' );
			return $crumb;
		}
		$crumb[] = array( woocommerce_page_title( false ), '' );
		return $crumb;
	}

	/**
	 * Wider, non-prose container for cart, checkout and account pages.
	 *
	 * @param string $css_class Class.
	 * @return string
	 */
	public static function container_class( $css_class ) {
		if ( is_cart() || is_checkout() || is_account_page() ) {
			return 'container wc-page entry-content';
		}
		return $css_class;
	}

	/**
	 * Products per row.
	 *
	 * @return int
	 */
	public static function columns() {
		return 3;
	}

	/**
	 * Related/up-sell products.
	 *
	 * @param array $args Args.
	 * @return array
	 */
	public static function related( $args ) {
		$args['posts_per_page'] = 3;
		$args['columns']        = 3;
		return $args;
	}

	/**
	 * Cart link markup.
	 *
	 * @return string
	 */
	public static function cart_link() {
		$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
		return sprintf(
			'<a class="icon-btn header-cart" href="%1$s" aria-label="%2$s">%3$s<span class="cart-count"%4$s>%5$s</span></a>',
			esc_url( wc_get_cart_url() ),
			/* translators: %d: items in cart. */
			esc_attr( sprintf( _n( 'Cart, %d item', 'Cart, %d items', $count, 'rmdhost' ), $count ) ),
			rmdhost_icon( 'cart' ),
			$count ? '' : ' hidden',
			esc_html( $count )
		);
	}

	/**
	 * Header cart icon.
	 */
	public static function header_cart() {
		if ( get_theme_mod( 'rmdhost_show_cart', true ) ) {
			echo self::cart_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in cart_link().
		}
	}

	/**
	 * AJAX cart fragments.
	 *
	 * @param array $fragments Fragments.
	 * @return array
	 */
	public static function fragments( $fragments ) {
		$fragments['a.header-cart'] = self::cart_link();
		return $fragments;
	}

	/**
	 * Spec fields in Product data → General.
	 */
	public static function spec_fields() {
		echo '<div class="options_group rmd-specs">';
		echo '<p class="form-field"><strong>' . esc_html__( 'Server plan (RMDHost)', 'rmdhost' ) . '</strong><br><span class="description">' . esc_html__( 'Shown in plan boxes and on the product page. Put the product in a category whose slug matches a product group (e.g. "vps") to list it on that page.', 'rmdhost' ) . '</span></p>';
		$labels = array(
			'cpu'       => __( 'CPU', 'rmdhost' ),
			'ram'       => __( 'RAM', 'rmdhost' ),
			'storage'   => __( 'Storage', 'rmdhost' ),
			'bandwidth' => __( 'Bandwidth', 'rmdhost' ),
			'location'  => __( 'Location', 'rmdhost' ),
			'ddos'      => __( 'DDoS protection', 'rmdhost' ),
			'badge'     => __( 'Badge (e.g. Most popular)', 'rmdhost' ),
			'usd'       => __( 'Fixed USD price (optional)', 'rmdhost' ),
			'note'      => __( 'Note under the price', 'rmdhost' ),
		);
		foreach ( $labels as $key => $label ) {
			woocommerce_wp_text_input(
				array(
					'id'    => '_rmd_' . $key,
					'label' => $label,
				)
			);
		}
		woocommerce_wp_textarea_input(
			array(
				'id'          => '_rmd_extras',
				'label'       => __( 'More details (one per line)', 'rmdhost' ),
				'placeholder' => __( "IPv6 /64\nFree setup", 'rmdhost' ),
			)
		);
		echo '</div>';
	}

	/**
	 * Save spec fields (nonce verified by WooCommerce before this hook runs).
	 *
	 * @param \WC_Product $product Product.
	 */
	public static function save_specs( $product ) {
		foreach ( self::SPECS as $key ) {
			$field = '_rmd_' . $key;
			if ( ! isset( $_POST[ $field ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
				continue;
			}
			$value = wp_unslash( $_POST[ $field ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.
			$product->update_meta_data( $field, 'extras' === $key ? sanitize_textarea_field( $value ) : sanitize_text_field( $value ) );
		}
	}

	/**
	 * Specs list on the product page.
	 */
	public static function single_specs() {
		$plan = self::plan_from_product( wc_get_product() );
		if ( ! $plan || ! array_filter( array( $plan['cpu'], $plan['ram'], $plan['storage'] ) ) ) {
			return;
		}
		$rows = array(
			array( 'cpu', __( 'CPU', 'rmdhost' ), $plan['cpu'] ),
			array( 'memory', __( 'RAM', 'rmdhost' ), $plan['ram'] ),
			array( 'drive', __( 'Storage', 'rmdhost' ), $plan['storage'] ),
			array( 'globe', __( 'Bandwidth', 'rmdhost' ), $plan['bandwidth'] ),
			array( 'location', __( 'Location', 'rmdhost' ), $plan['location'] ),
			array( 'shield', __( 'DDoS', 'rmdhost' ), $plan['ddos'] ),
		);
		echo '<ul class="specs wc-specs">';
		foreach ( $rows as $row ) {
			if ( '' === (string) $row[2] ) {
				continue;
			}
			echo '<li>' . rmdhost_icon( $row[0] ) . '<span class="k">' . esc_html( $row[1] ) . '</span><span class="v">' . esc_html( $row[2] ) . '</span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</ul>';
		if ( $plan['extras'] ) {
			echo rmdhost_checklist( $plan['extras'], 'checks-sm' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Customizer: plans source.
	 *
	 * @param \WP_Customize_Manager $wp_customize Manager.
	 */
	public static function customize( $wp_customize ) {
		$wp_customize->add_section(
			'rmdhost_store',
			array(
				'title' => __( 'Store (WooCommerce)', 'rmdhost' ),
				'panel' => 'rmdhost',
			)
		);
		$wp_customize->add_setting(
			'rmdhost_plans_source',
			array(
				'default'           => 'catalog',
				'sanitize_callback' => static function ( $v ) {
					return in_array( $v, array( 'catalog', 'woocommerce' ), true ) ? $v : 'catalog';
				},
			)
		);
		$wp_customize->add_control(
			'rmdhost_plans_source',
			array(
				'label'       => __( 'Plan boxes use', 'rmdhost' ),
				'description' => __( 'WooCommerce: plans come from products in the category matching each product group, and “Order now” adds the plan to the cart and opens checkout.', 'rmdhost' ),
				'section'     => 'rmdhost_store',
				'type'        => 'radio',
				'choices'     => array(
					'catalog'     => __( 'Server plans / client area links', 'rmdhost' ),
					'woocommerce' => __( 'WooCommerce products', 'rmdhost' ),
				),
			)
		);
		$wp_customize->add_setting(
			'rmdhost_show_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'rest_sanitize_boolean',
			)
		);
		$wp_customize->add_control(
			'rmdhost_show_cart',
			array(
				'label'   => __( 'Show cart icon in the header', 'rmdhost' ),
				'section' => 'rmdhost_store',
				'type'    => 'checkbox',
			)
		);
	}

	/**
	 * Plans from WooCommerce products when enabled.
	 *
	 * @param array  $plans Plans.
	 * @param string $slug  Group slug.
	 * @return array
	 */
	public static function plans( $plans, $slug ) {
		if ( 'woocommerce' !== get_theme_mod( 'rmdhost_plans_source', 'catalog' ) ) {
			return $plans;
		}
		$products = wc_get_products(
			array(
				'status'   => 'publish',
				'limit'    => 24,
				'category' => array( $slug ),
				'orderby'  => 'menu_order',
				'order'    => 'ASC',
			)
		);
		if ( ! $products ) {
			return $plans;
		}
		return array_values( array_filter( array_map( array( __CLASS__, 'plan_from_product' ), $products ) ) );
	}

	/**
	 * Map a product to a plan array.
	 *
	 * @param \WC_Product|false $product Product.
	 * @return array|null
	 */
	public static function plan_from_product( $product ) {
		if ( ! $product ) {
			return null;
		}
		$meta  = static function ( $key ) use ( $product ) {
			return (string) $product->get_meta( '_rmd_' . $key );
		};
		$price = $product->get_price();
		$order = $product->is_purchasable() && $product->is_in_stock() && $product->is_type( 'simple' )
			? add_query_arg( 'add-to-cart', $product->get_id(), wc_get_checkout_url() )
			: $product->get_permalink();
		return Data::normalize_plan(
			array(
				'id'        => 'wc-' . $product->get_id(),
				'name'      => $product->get_name(),
				'price'     => '' === $price ? null : (float) wc_get_price_to_display( $product ),
				'usd'       => $meta( 'usd' ),
				'badge'     => $meta( 'badge' ) ? $meta( 'badge' ) : ( $product->is_featured() ? __( 'Most popular', 'rmdhost' ) : '' ),
				'cpu'       => $meta( 'cpu' ),
				'ram'       => $meta( 'ram' ),
				'storage'   => $meta( 'storage' ),
				'bandwidth' => $meta( 'bandwidth' ),
				'location'  => $meta( 'location' ),
				'ddos'      => $meta( 'ddos' ),
				'extras'    => $meta( 'extras' ),
				'note'      => $meta( 'note' ),
				'order'     => $order,
			)
		);
	}
}
