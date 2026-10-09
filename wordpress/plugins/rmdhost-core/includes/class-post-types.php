<?php
/**
 * Editable content: Server plans, Locations, Reviews, FAQs (+ contact messages).
 *
 * Each type feeds the theme through its data filters, so anything you add here
 * replaces the theme's built-in catalogue for that list. Leave a list empty to
 * keep using the built-in data.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Post types, taxonomy, fields and theme filters.
 */
class Post_Types {

	/**
	 * Request cache.
	 *
	 * @var array
	 */
	private static $cache = array();

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'meta_boxes' ) );
		add_action( 'save_post', array( __CLASS__, 'save' ), 10, 2 );
		add_filter( 'manage_rmd_plan_posts_columns', array( __CLASS__, 'plan_columns' ) );
		add_action( 'manage_rmd_plan_posts_custom_column', array( __CLASS__, 'plan_column' ), 10, 2 );

		add_filter( 'rmdhost/plans', array( __CLASS__, 'filter_plans' ), 10, 2 );
		add_filter( 'rmdhost/locations', array( __CLASS__, 'filter_locations' ) );
		add_filter( 'rmdhost/reviews', array( __CLASS__, 'filter_reviews' ) );
		add_filter( 'rmdhost/faqs', array( __CLASS__, 'filter_faqs' ), 10, 2 );
		add_action( 'save_post', array( __CLASS__, 'flush_cache' ) );
	}

	/**
	 * Field definitions per post type: key => array( label, type ).
	 *
	 * @param string $type Post type.
	 * @return array
	 */
	public static function fields( $type ) {
		$map = array(
			'rmd_plan'     => array(
				'price'     => array( __( 'Monthly price in GBP (leave empty for “coming soon”)', 'rmdhost-core' ), 'number' ),
				'usd'       => array( __( 'Fixed USD price (optional, otherwise converted)', 'rmdhost-core' ), 'number' ),
				'badge'     => array( __( 'Badge (e.g. Most popular) – highlights the plan', 'rmdhost-core' ), 'text' ),
				'cpu'       => array( __( 'CPU', 'rmdhost-core' ), 'text' ),
				'ram'       => array( __( 'RAM', 'rmdhost-core' ), 'text' ),
				'storage'   => array( __( 'Storage', 'rmdhost-core' ), 'text' ),
				'bandwidth' => array( __( 'Bandwidth', 'rmdhost-core' ), 'text' ),
				'location'  => array( __( 'Location', 'rmdhost-core' ), 'text' ),
				'ddos'      => array( __( 'DDoS protection', 'rmdhost-core' ), 'text' ),
				'order'     => array( __( 'Order URL (client area / checkout)', 'rmdhost-core' ), 'url' ),
				'note'      => array( __( 'Note under the price', 'rmdhost-core' ), 'text' ),
				'extras'    => array( __( '“More details” list – one item per line', 'rmdhost-core' ), 'textarea' ),
			),
			'rmd_location' => array(
				'country'      => array( __( 'Country', 'rmdhost-core' ), 'text' ),
				'code'         => array( __( 'Country code (e.g. GB)', 'rmdhost-core' ), 'text' ),
				'region'       => array( __( 'Region (“Europe” shows the city on the Europe zoom map)', 'rmdhost-core' ), 'text' ),
				'lat'          => array( __( 'Latitude', 'rmdhost-core' ), 'number' ),
				'lon'          => array( __( 'Longitude', 'rmdhost-core' ), 'number' ),
				'label_dx'     => array( __( 'Map label offset X', 'rmdhost-core' ), 'number' ),
				'label_dy'     => array( __( 'Map label offset Y', 'rmdhost-core' ), 'number' ),
				'label_anchor' => array( __( 'Map label anchor (start, middle or end)', 'rmdhost-core' ), 'text' ),
			),
			'rmd_review'   => array(
				'role'    => array( __( 'Role / company', 'rmdhost-core' ), 'text' ),
				'product' => array( __( 'Product used', 'rmdhost-core' ), 'text' ),
				'rating'  => array( __( 'Rating (1–5)', 'rmdhost-core' ), 'number' ),
				'sample'  => array( __( 'Sample review (shows a “Sample” label – untick for real customer reviews)', 'rmdhost-core' ), 'checkbox' ),
			),
		);
		return $map[ $type ] ?? array();
	}

	/**
	 * Register post types and taxonomy.
	 */
	public static function register() {
		$common = array(
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => 'rmdhost-core',
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		);
		register_post_type(
			'rmd_plan',
			array_merge(
				$common,
				array(
					'labels'   => self::labels( __( 'Server plans', 'rmdhost-core' ), __( 'Server plan', 'rmdhost-core' ) ),
					'supports' => array( 'title', 'page-attributes', 'custom-fields' ),
				)
			)
		);
		register_post_type(
			'rmd_location',
			array_merge(
				$common,
				array(
					'labels'   => self::labels( __( 'Locations', 'rmdhost-core' ), __( 'Location', 'rmdhost-core' ) ),
					'supports' => array( 'title', 'page-attributes', 'custom-fields' ),
				)
			)
		);
		register_post_type(
			'rmd_review',
			array_merge(
				$common,
				array(
					'labels'   => self::labels( __( 'Reviews', 'rmdhost-core' ), __( 'Review', 'rmdhost-core' ) ),
					'supports' => array( 'title', 'editor', 'page-attributes', 'custom-fields' ),
				)
			)
		);
		register_post_type(
			'rmd_faq',
			array_merge(
				$common,
				array(
					'labels'   => self::labels( __( 'FAQs', 'rmdhost-core' ), __( 'FAQ', 'rmdhost-core' ) ),
					'supports' => array( 'title', 'editor', 'page-attributes' ),
				)
			)
		);
		register_post_type(
			'rmd_message',
			array_merge(
				$common,
				array(
					'labels'       => self::labels( __( 'Messages', 'rmdhost-core' ), __( 'Message', 'rmdhost-core' ) ),
					'supports'     => array( 'title', 'editor' ),
					'show_in_rest' => false,
					'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				)
			)
		);
		register_taxonomy(
			'rmd_group',
			array( 'rmd_plan', 'rmd_faq' ),
			array(
				'labels'            => array(
					'name'          => __( 'Product groups', 'rmdhost-core' ),
					'singular_name' => __( 'Product group', 'rmdhost-core' ),
					'add_new_item'  => __( 'Add product group', 'rmdhost-core' ),
				),
				'public'            => false,
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'hierarchical'      => true,
				'description'       => __( 'Slug must match a product page group, e.g. vps, windows-vps, dedicated-servers. Use "general" for homepage FAQs.', 'rmdhost-core' ),
			)
		);
		foreach ( array( 'rmd_plan', 'rmd_location', 'rmd_review' ) as $type ) {
			foreach ( self::fields( $type ) as $key => $field ) {
				register_post_meta(
					$type,
					'_rmd_' . $key,
					array(
						'type'          => 'checkbox' === $field[1] ? 'boolean' : 'string',
						'single'        => true,
						'show_in_rest'  => true,
						'auth_callback' => static function () {
							return current_user_can( 'edit_posts' );
						},
					)
				);
			}
		}
	}

	/**
	 * Labels helper.
	 *
	 * @param string $plural   Plural.
	 * @param string $singular Singular.
	 * @return array
	 */
	private static function labels( $plural, $singular ) {
		return array(
			'name'          => $plural,
			'singular_name' => $singular,
			'menu_name'     => $plural,
			'all_items'     => $plural,
			/* translators: %s: item name. */
			'add_new_item'  => sprintf( __( 'Add %s', 'rmdhost-core' ), $singular ),
			/* translators: %s: item name. */
			'edit_item'     => sprintf( __( 'Edit %s', 'rmdhost-core' ), $singular ),
			'search_items'  => __( 'Search', 'rmdhost-core' ),
			'not_found'     => __( 'Nothing found. The theme’s built-in list is used until you add items.', 'rmdhost-core' ),
		);
	}

	/**
	 * Meta boxes.
	 */
	public static function meta_boxes() {
		foreach ( array( 'rmd_plan', 'rmd_location', 'rmd_review' ) as $type ) {
			add_meta_box( 'rmd_fields', __( 'Details', 'rmdhost-core' ), array( __CLASS__, 'render' ), $type, 'normal', 'high' );
		}
		add_meta_box( 'rmd_message', __( 'Sender', 'rmdhost-core' ), array( __CLASS__, 'render_message' ), 'rmd_message', 'side' );
	}

	/**
	 * Fields UI.
	 *
	 * @param \WP_Post $post Post.
	 */
	public static function render( $post ) {
		wp_nonce_field( 'rmd_fields', 'rmd_fields_nonce' );
		echo '<table class="form-table" role="presentation"><tbody>';
		foreach ( self::fields( $post->post_type ) as $key => $field ) {
			$id    = 'rmd_' . $key;
			$value = get_post_meta( $post->ID, '_rmd_' . $key, true );
			echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field[0] ) . '</label></th><td>';
			switch ( $field[1] ) {
				case 'textarea':
					echo '<textarea class="large-text" rows="4" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '">' . esc_textarea( $value ) . '</textarea>';
					break;
				case 'checkbox':
					echo '<input type="checkbox" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="1" ' . checked( (bool) $value, true, false ) . '>';
					break;
				default:
					$type = 'number' === $field[1] ? 'text" inputmode="decimal' : ( 'url' === $field[1] ? 'url' : 'text' );
					echo '<input class="regular-text" type="' . $type . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $value ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed strings.
			}
			echo '</td></tr>';
		}
		echo '</tbody></table>';
		if ( 'rmd_plan' === $post->post_type ) {
			echo '<p class="description">' . esc_html__( 'Assign the plan to a Product group (right) so it appears on that product page and in the homepage pricing tabs. Drag order = “Order” attribute.', 'rmdhost-core' ) . '</p>';
		}
		if ( 'rmd_review' === $post->post_type ) {
			echo '<p class="description">' . esc_html__( 'Title = customer name, content = review text. Only publish reviews you have permission to use.', 'rmdhost-core' ) . '</p>';
		}
	}

	/**
	 * Message sender box.
	 *
	 * @param \WP_Post $post Post.
	 */
	public static function render_message( $post ) {
		foreach ( array( 'name', 'email', 'topic', 'ip' ) as $key ) {
			echo '<p><strong>' . esc_html( ucfirst( $key ) ) . ':</strong> ' . esc_html( get_post_meta( $post->ID, '_rmd_' . $key, true ) ) . '</p>';
		}
	}

	/**
	 * Save fields.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post.
	 */
	public static function save( $post_id, $post ) {
		if ( ! isset( $_POST['rmd_fields_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['rmd_fields_nonce'] ) ), 'rmd_fields' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( self::fields( $post->post_type ) as $key => $field ) {
			$name = 'rmd_' . $key;
			$raw  = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized per type below.
			switch ( $field[1] ) {
				case 'textarea':
					$value = sanitize_textarea_field( $raw );
					break;
				case 'checkbox':
					$value = ! empty( $raw );
					break;
				case 'url':
					$value = esc_url_raw( $raw );
					break;
				case 'number':
					$raw   = str_replace( ',', '.', trim( (string) $raw ) );
					$value = is_numeric( $raw ) ? $raw : '';
					break;
				default:
					$value = sanitize_text_field( $raw );
			}
			update_post_meta( $post_id, '_rmd_' . $key, $value );
		}
	}

	/**
	 * Admin columns for plans.
	 *
	 * @param array $cols Columns.
	 * @return array
	 */
	public static function plan_columns( $cols ) {
		$date = $cols['date'] ?? null;
		unset( $cols['date'] );
		$cols['rmd_price'] = __( 'Price (GBP)', 'rmdhost-core' );
		$cols['rmd_specs'] = __( 'Specs', 'rmdhost-core' );
		if ( $date ) {
			$cols['date'] = $date;
		}
		return $cols;
	}

	/**
	 * Admin column values.
	 *
	 * @param string $col     Column.
	 * @param int    $post_id Post ID.
	 */
	public static function plan_column( $col, $post_id ) {
		if ( 'rmd_price' === $col ) {
			$p = get_post_meta( $post_id, '_rmd_price', true );
			echo '' === $p ? esc_html__( 'Coming soon', 'rmdhost-core' ) : esc_html( '£' . $p );
		} elseif ( 'rmd_specs' === $col ) {
			echo esc_html( implode( ' · ', array_filter( array( get_post_meta( $post_id, '_rmd_cpu', true ), get_post_meta( $post_id, '_rmd_ram', true ), get_post_meta( $post_id, '_rmd_storage', true ) ) ) ) );
		}
	}

	/**
	 * Reset the request cache.
	 */
	public static function flush_cache() {
		self::$cache = array();
	}

	/**
	 * Query helper (cached per request).
	 *
	 * @param string $type  Post type.
	 * @param string $group Optional rmd_group slug.
	 * @return \WP_Post[]
	 */
	private static function posts( $type, $group = '' ) {
		$key = $type . ':' . $group;
		if ( ! isset( self::$cache[ $key ] ) ) {
			$args = array(
				'post_type'      => $type,
				'post_status'    => 'publish',
				'posts_per_page' => 100,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'ASC',
				),
				'no_found_rows'  => true,
			);
			if ( $group ) {
				$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- small, admin-curated lists.
					array(
						'taxonomy' => 'rmd_group',
						'field'    => 'slug',
						'terms'    => $group,
					),
				);
			}
			self::$cache[ $key ] = get_posts( $args );
		}
		return self::$cache[ $key ];
	}

	/**
	 * Meta getter.
	 *
	 * @param int    $id  Post ID.
	 * @param string $key Key without prefix.
	 * @return mixed
	 */
	private static function meta( $id, $key ) {
		return get_post_meta( $id, '_rmd_' . $key, true );
	}

	/**
	 * Plans for a group.
	 *
	 * @param array  $plans Plans.
	 * @param string $slug  Group slug.
	 * @return array
	 */
	public static function filter_plans( $plans, $slug ) {
		$posts = self::posts( 'rmd_plan', $slug );
		if ( ! $posts ) {
			return $plans;
		}
		$out = array();
		foreach ( $posts as $p ) {
			$row = array(
				'id'   => $p->post_name,
				'name' => $p->post_title,
			);
			foreach ( array_keys( self::fields( 'rmd_plan' ) ) as $key ) {
				$row[ $key ] = self::meta( $p->ID, $key );
			}
			$row['price'] = '' === $row['price'] ? null : (float) $row['price'];
			$out[]        = $row;
		}
		return $out;
	}

	/**
	 * Locations.
	 *
	 * @param array $locations Locations.
	 * @return array
	 */
	public static function filter_locations( $locations ) {
		$posts = self::posts( 'rmd_location' );
		if ( ! $posts ) {
			return $locations;
		}
		$out = array();
		foreach ( $posts as $p ) {
			$out[] = array(
				'city'    => $p->post_title,
				'country' => self::meta( $p->ID, 'country' ),
				'code'    => self::meta( $p->ID, 'code' ),
				'region'  => self::meta( $p->ID, 'region' ),
				'lat'     => (float) self::meta( $p->ID, 'lat' ),
				'lon'     => (float) self::meta( $p->ID, 'lon' ),
				'label'   => array( $p->post_title, (float) self::meta( $p->ID, 'label_dx' ), (float) self::meta( $p->ID, 'label_dy' ), self::meta( $p->ID, 'label_anchor' ) ? self::meta( $p->ID, 'label_anchor' ) : 'start' ),
			);
		}
		return $out;
	}

	/**
	 * Reviews.
	 *
	 * @param array $reviews Reviews.
	 * @return array
	 */
	public static function filter_reviews( $reviews ) {
		$posts = self::posts( 'rmd_review' );
		if ( ! $posts ) {
			return $reviews;
		}
		$out = array();
		foreach ( $posts as $p ) {
			$out[] = array(
				'name'    => $p->post_title,
				'role'    => self::meta( $p->ID, 'role' ),
				'product' => self::meta( $p->ID, 'product' ),
				'rating'  => max( 1, min( 5, (int) self::meta( $p->ID, 'rating' ) ) ),
				'text'    => wp_strip_all_tags( $p->post_content ),
				'sample'  => (bool) self::meta( $p->ID, 'sample' ),
			);
		}
		return $out;
	}

	/**
	 * FAQs for a group ("general" = homepage).
	 *
	 * @param array  $faqs  FAQs.
	 * @param string $group Group slug.
	 * @return array
	 */
	public static function filter_faqs( $faqs, $group ) {
		$posts = self::posts( 'rmd_faq', $group );
		if ( ! $posts ) {
			return $faqs;
		}
		$out = array();
		foreach ( $posts as $p ) {
			$out[] = array(
				'q' => $p->post_title,
				'a' => wp_strip_all_tags( $p->post_content ),
			);
		}
		return $out;
	}
}
