<?php
/**
 * Admin dashboard + one-click demo import (pages, menus, posts, plans,
 * locations, reviews, FAQs and – optionally – WooCommerce products and an
 * Elementor homepage). Safe to run more than once: existing items are updated,
 * not duplicated.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Importer.
 */
class Importer {

	/**
	 * Import log.
	 *
	 * @var string[]
	 */
	private static $log = array();

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_post_rmdhost_import', array( __CLASS__, 'handle' ) );
		add_action( 'admin_notices', array( __CLASS__, 'activation_notice' ) );
	}

	/**
	 * Admin menu.
	 */
	public static function menu() {
		add_menu_page( __( 'RMDHost', 'rmdhost-core' ), __( 'RMDHost', 'rmdhost-core' ), 'edit_posts', 'rmdhost-core', array( __CLASS__, 'page' ), 'dashicons-cloud', 3 );
		add_submenu_page( 'rmdhost-core', __( 'Welcome & demo import', 'rmdhost-core' ), __( 'Welcome & import', 'rmdhost-core' ), 'manage_options', 'rmdhost-core', array( __CLASS__, 'page' ) );
		add_submenu_page( 'rmdhost-core', __( 'Product groups', 'rmdhost-core' ), __( 'Product groups', 'rmdhost-core' ), 'manage_categories', 'edit-tags.php?taxonomy=rmd_group&post_type=rmd_plan' );
	}

	/**
	 * Nudge to import after activation.
	 */
	public static function activation_notice() {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_rmdhost-core' === $screen->id || get_option( 'rmdhost_demo_imported' ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( 'themes' !== $screen->id && 'plugins' !== $screen->id && 'dashboard' !== $screen->id ) {
			return;
		}
		printf(
			'<div class="notice notice-info"><p><strong>%1$s</strong> %2$s <a class="button button-primary" href="%3$s">%4$s</a></p></div>',
			esc_html__( 'RMDHost is ready.', 'rmdhost-core' ),
			esc_html__( 'Import the demo pages, menus and plans to get the complete site in one click.', 'rmdhost-core' ),
			esc_url( admin_url( 'admin.php?page=rmdhost-core' ) ),
			esc_html__( 'Open the importer', 'rmdhost-core' )
		);
	}

	/**
	 * Dashboard page.
	 */
	public static function page() {
		$imported = get_option( 'rmdhost_demo_imported' );
		$log      = get_transient( 'rmdhost_import_log' );
		delete_transient( 'rmdhost_import_log' );
		$wc = class_exists( 'WooCommerce' );
		$el = did_action( 'elementor/loaded' );
		$ok = Render::theme_ready();
		?>
		<div class="wrap rmdhost-admin">
			<h1><?php esc_html_e( 'RMDHost', 'rmdhost-core' ); ?></h1>
			<?php if ( ! $ok ) : ?>
				<div class="notice notice-warning inline"><p><?php esc_html_e( 'Activate the RMDHost theme (Appearance → Themes) before importing.', 'rmdhost-core' ); ?></p></div>
			<?php endif; ?>
			<?php if ( $log ) : ?>
				<div class="notice notice-success inline"><p><strong><?php esc_html_e( 'Import finished.', 'rmdhost-core' ); ?></strong></p><ul style="list-style:disc;margin-left:20px">
					<?php foreach ( (array) $log as $line ) : ?>
						<li><?php echo esc_html( $line ); ?></li>
					<?php endforeach; ?>
				</ul><p><a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'View site', 'rmdhost-core' ); ?></a></p></div>
			<?php endif; ?>

			<div class="card" style="max-width:820px">
				<h2><?php esc_html_e( 'One-click demo import', 'rmdhost-core' ); ?></h2>
				<p><?php esc_html_e( 'Creates every page of the RMDHost demo with the right templates, the mega menu and footer menus, blog posts, and editable server plans, locations, reviews and FAQs. Running it again updates the demo items instead of duplicating them.', 'rmdhost-core' ); ?></p>
				<?php if ( $imported ) : ?>
					<p><em><?php /* translators: %s: date. */ echo esc_html( sprintf( __( 'Last imported: %s', 'rmdhost-core' ), wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), (int) $imported ) ) ); ?></em></p>
				<?php endif; ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="rmdhost_import">
					<?php wp_nonce_field( 'rmdhost_import', 'rmdhost_import_nonce' ); ?>
					<fieldset>
						<p><label><input type="checkbox" name="parts[]" value="pages" checked> <?php esc_html_e( 'Pages (homepage, 12 product pages, pricing, data centres, support, legal…) and reading settings', 'rmdhost-core' ); ?></label></p>
						<p><label><input type="checkbox" name="parts[]" value="menus" checked> <?php esc_html_e( 'Menus: mega menu with promo panels, footer columns, legal links', 'rmdhost-core' ); ?></label></p>
						<p><label><input type="checkbox" name="parts[]" value="posts" checked> <?php esc_html_e( 'Blog posts', 'rmdhost-core' ); ?></label></p>
						<p><label><input type="checkbox" name="parts[]" value="data" checked> <?php esc_html_e( 'Editable data: server plans, locations, reviews and FAQs', 'rmdhost-core' ); ?></label></p>
						<p><label><input type="checkbox" name="parts[]" value="woocommerce" <?php disabled( ! $wc ); ?>> <?php esc_html_e( 'WooCommerce products for every plan (sell plans through your store)', 'rmdhost-core' ); ?><?php echo $wc ? '' : ' — ' . esc_html__( 'requires WooCommerce', 'rmdhost-core' ); ?></label></p>
						<p><label><input type="checkbox" name="parts[]" value="elementor" <?php disabled( ! $el ); ?> <?php checked( (bool) $el ); ?>> <?php esc_html_e( 'Build the homepage with Elementor widgets (otherwise it uses the Customizer homepage sections)', 'rmdhost-core' ); ?><?php echo $el ? '' : ' — ' . esc_html__( 'requires Elementor', 'rmdhost-core' ); ?></label></p>
					</fieldset>
					<?php submit_button( __( 'Import demo content', 'rmdhost-core' ), 'primary', 'submit', false, $ok ? array() : array( 'disabled' => 'disabled' ) ); ?>
				</form>
			</div>

			<div class="card" style="max-width:820px">
				<h2><?php esc_html_e( 'Where to edit what', 'rmdhost-core' ); ?></h2>
				<ul style="list-style:disc;margin-left:20px">
					<li><?php esc_html_e( 'Homepage hero, offer banner, review rating, stats, section toggles, colours, cookie banner: Appearance → Customize → RMDHost Theme.', 'rmdhost-core' ); ?></li>
					<li><?php esc_html_e( 'Plans, prices and order links: RMDHost → Server plans (assign a Product group).', 'rmdhost-core' ); ?></li>
					<li><?php esc_html_e( 'Map pins: RMDHost → Locations. Reviews: RMDHost → Reviews. FAQs: RMDHost → FAQs.', 'rmdhost-core' ); ?></li>
					<li><?php esc_html_e( 'Mega menu: Appearance → Menus (icons, tags and promo panels are under each item).', 'rmdhost-core' ); ?></li>
					<li><?php esc_html_e( 'Any page: use the “RMDHost section” block, the RMDHost Elementor widgets, or the [rmdhost_section] shortcode.', 'rmdhost-core' ); ?></li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Run the import.
	 */
	public static function handle() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'rmdhost-core' ) );
		}
		check_admin_referer( 'rmdhost_import', 'rmdhost_import_nonce' );
		$parts = isset( $_POST['parts'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['parts'] ) ) : array();
		self::run( $parts );
		set_transient( 'rmdhost_import_log', self::$log, 300 );
		wp_safe_redirect( admin_url( 'admin.php?page=rmdhost-core' ) );
		exit;
	}

	/**
	 * Import selected parts (also used by WP-CLI: wp eval 'RMDHost_Core\Importer::run();').
	 *
	 * @param array $parts Parts to import.
	 * @return string[] Log.
	 */
	public static function run( $parts = array( 'pages', 'menus', 'posts', 'data', 'elementor' ) ) {
		if ( function_exists( 'set_time_limit' ) ) {
			set_time_limit( 300 ); // phpcs:ignore Squiz.PHP.DiscouragedFunctions.Discouraged -- long import.
		}
		self::$log = array();
		$catalog   = self::catalog();
		if ( ! get_current_user_id() ) {
			$admins = get_users(
				array(
					'role'   => 'administrator',
					'number' => 1,
					'fields' => 'ID',
				)
			);
			if ( $admins ) {
				wp_set_current_user( (int) $admins[0] );
			}
		}
		if ( ! $catalog ) {
			self::$log[] = __( 'Demo data file is missing.', 'rmdhost-core' );
			return self::$log;
		}
		if ( in_array( 'data', $parts, true ) ) {
			self::import_data( $catalog );
		}
		if ( in_array( 'woocommerce', $parts, true ) && class_exists( 'WooCommerce' ) ) {
			self::import_products( $catalog );
		}
		if ( in_array( 'posts', $parts, true ) ) {
			self::import_posts( $catalog );
		}
		if ( in_array( 'pages', $parts, true ) ) {
			self::import_pages( $catalog, in_array( 'elementor', $parts, true ) && did_action( 'elementor/loaded' ) );
		}
		if ( in_array( 'menus', $parts, true ) ) {
			self::import_menus( $catalog );
		}
		update_option( 'rmdhost_demo_imported', time() );
		flush_rewrite_rules();
		return self::$log;
	}

	/**
	 * Demo catalogue.
	 *
	 * @return array
	 */
	private static function catalog() {
		$file = RMDHOST_CORE_DIR . 'demo/catalog.json';
		if ( ! file_exists( $file ) ) {
			return array();
		}
		$data = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
		return is_array( $data ) ? $data : array();
	}

	/**
	 * Find a demo item by key.
	 *
	 * @param string $type Post type.
	 * @param string $key  Demo key.
	 * @return int
	 */
	private static function find( $type, $key ) {
		$found = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => '_rmd_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- import only.
				'meta_value'     => $key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- import only.
			)
		);
		return $found ? (int) $found[0] : 0;
	}

	/**
	 * Insert or update a demo post.
	 *
	 * @param string $key  Demo key.
	 * @param array  $post Post array.
	 * @param array  $meta Meta.
	 * @return int
	 */
	private static function upsert( $key, $post, $meta = array() ) {
		$id = self::find( $post['post_type'], $key );
		if ( ! $id && 'page' === $post['post_type'] && ! empty( $post['post_name'] ) ) {
			$existing = get_page_by_path( $post['post_name'] );
			$id       = $existing ? (int) $existing->ID : 0;
		}
		$post = array_merge(
			array(
				'post_status' => 'publish',
				'post_author' => get_current_user_id(),
			),
			$post
		);
		if ( $id ) {
			$post['ID'] = $id;
			wp_update_post( wp_slash( $post ) );
		} else {
			$id = (int) wp_insert_post( wp_slash( $post ) );
		}
		if ( ! $id ) {
			return 0;
		}
		update_post_meta( $id, '_rmd_demo', $key );
		foreach ( $meta as $k => $v ) {
			update_post_meta( $id, $k, wp_slash( $v ) );
		}
		return $id;
	}

	/**
	 * Product-group terms.
	 *
	 * @param array $catalog Catalogue.
	 * @return array slug => term_id
	 */
	private static function terms( $catalog ) {
		$ids  = array();
		$list = array( 'general' => __( 'General', 'rmdhost-core' ) );
		foreach ( $catalog['products'] as $p ) {
			$list[ $p['slug'] ] = $p['name'];
		}
		foreach ( $list as $slug => $name ) {
			$term = term_exists( $slug, 'rmd_group' );
			if ( ! $term ) {
				$term = wp_insert_term( $name, 'rmd_group', array( 'slug' => $slug ) );
			}
			if ( ! is_wp_error( $term ) ) {
				$ids[ $slug ] = (int) $term['term_id'];
			}
		}
		return $ids;
	}

	/**
	 * Plans, locations, reviews, FAQs.
	 *
	 * @param array $catalog Catalogue.
	 */
	private static function import_data( $catalog ) {
		$terms = self::terms( $catalog );
		$n     = 0;
		foreach ( $catalog['products'] as $p ) {
			foreach ( $p['plans'] as $i => $plan ) {
				$meta = array();
				foreach ( array_keys( Post_Types::fields( 'rmd_plan' ) ) as $k ) {
					$v                    = $plan[ $k ] ?? '';
					$meta[ '_rmd_' . $k ] = is_array( $v ) ? implode( "\n", $v ) : ( null === $v ? '' : (string) $v );
				}
				$id = self::upsert(
					'plan:' . $p['slug'] . ':' . ( $plan['id'] ?? sanitize_title( $plan['name'] ) ),
					array(
						'post_type'  => 'rmd_plan',
						'post_title' => $plan['name'],
						'post_name'  => $plan['id'] ?? sanitize_title( $plan['name'] ),
						'menu_order' => $i,
					),
					$meta
				);
				if ( $id && isset( $terms[ $p['slug'] ] ) ) {
					wp_set_object_terms( $id, array( $terms[ $p['slug'] ] ), 'rmd_group' );
					++$n;
				}
			}
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d server plans', 'rmdhost-core' ), $n );

		foreach ( $catalog['locations'] as $i => $l ) {
			self::upsert(
				'loc:' . sanitize_title( $l['city'] ),
				array(
					'post_type'  => 'rmd_location',
					'post_title' => $l['city'],
					'menu_order' => $i,
				),
				array(
					'_rmd_country'      => $l['country'],
					'_rmd_code'         => $l['code'],
					'_rmd_region'       => $l['region'],
					'_rmd_lat'          => (string) $l['lat'],
					'_rmd_lon'          => (string) $l['lon'],
					'_rmd_label_dx'     => (string) ( $l['label'][1] ?? 0 ),
					'_rmd_label_dy'     => (string) ( $l['label'][2] ?? 0 ),
					'_rmd_label_anchor' => (string) ( $l['label'][3] ?? 'start' ),
				)
			);
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d data-centre locations', 'rmdhost-core' ), count( $catalog['locations'] ) );

		foreach ( $catalog['reviews'] as $i => $r ) {
			self::upsert(
				'review:' . $i,
				array(
					'post_type'    => 'rmd_review',
					'post_title'   => $r['name'],
					'post_content' => $r['text'],
					'menu_order'   => $i,
				),
				array(
					'_rmd_role'    => $r['role'],
					'_rmd_product' => $r['product'],
					'_rmd_rating'  => (string) $r['rating'],
					'_rmd_sample'  => ! empty( $r['sample'] ),
				)
			);
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d reviews (marked “Sample” – replace with real ones)', 'rmdhost-core' ), count( $catalog['reviews'] ) );

		$faqs = array( 'general' => $catalog['faqs'] );
		foreach ( $catalog['products'] as $p ) {
			$faqs[ $p['slug'] ] = $p['faqs'];
		}
		$n = 0;
		foreach ( $faqs as $group => $list ) {
			foreach ( $list as $i => $f ) {
				$q  = $f['q'] ?? $f[0];
				$a  = $f['a'] ?? $f[1];
				$id = self::upsert(
					'faq:' . $group . ':' . $i,
					array(
						'post_type'    => 'rmd_faq',
						'post_title'   => $q,
						'post_content' => $a,
						'menu_order'   => $i,
					)
				);
				if ( $id && isset( $terms[ $group ] ) ) {
					wp_set_object_terms( $id, array( $terms[ $group ] ), 'rmd_group' );
					++$n;
				}
			}
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d FAQs', 'rmdhost-core' ), $n );
	}

	/**
	 * WooCommerce products for plans.
	 *
	 * @param array $catalog Catalogue.
	 */
	private static function import_products( $catalog ) {
		$n = 0;
		// Plan prices are in GBP; match the store currency on a store without orders.
		if ( 'GBP' !== get_option( 'woocommerce_currency' ) && ! wc_get_orders(
			array(
				'limit'  => 1,
				'return' => 'ids',
			)
		) ) {
			update_option( 'woocommerce_currency', 'GBP' );
			self::$log[] = __( 'Store currency set to GBP', 'rmdhost-core' );
		}
		foreach ( $catalog['products'] as $p ) {
			$cat = term_exists( $p['slug'], 'product_cat' );
			if ( ! $cat ) {
				$cat = wp_insert_term( $p['name'], 'product_cat', array( 'slug' => $p['slug'] ) );
			}
			if ( is_wp_error( $cat ) ) {
				continue;
			}
			foreach ( $p['plans'] as $i => $plan ) {
				if ( null === $plan['price'] ) {
					continue;
				}
				$key     = 'product:' . $p['slug'] . ':' . ( $plan['id'] ?? sanitize_title( $plan['name'] ) );
				$id      = self::find( 'product', $key );
				$product = $id ? wc_get_product( $id ) : new \WC_Product_Simple();
				$product->set_name( $plan['name'] . ' – ' . $p['name'] );
				$product->set_status( 'publish' );
				$product->set_regular_price( (string) $plan['price'] );
				$product->set_virtual( true );
				$product->set_menu_order( $i );
				$product->set_category_ids( array( (int) $cat['term_id'] ) );
				$product->set_short_description( implode( ' · ', array_filter( array( $plan['cpu'], $plan['ram'], $plan['storage'] ) ) ) );
				$product->set_featured( ! empty( $plan['badge'] ) );
				foreach ( array( 'cpu', 'ram', 'storage', 'bandwidth', 'location', 'ddos', 'badge', 'note' ) as $k ) {
					$product->update_meta_data( '_rmd_' . $k, (string) ( $plan[ $k ] ?? '' ) );
				}
				$product->update_meta_data( '_rmd_extras', implode( "\n", (array) ( $plan['extras'] ?? array() ) ) );
				$product->update_meta_data( '_rmd_demo', $key );
				$product->save();
				++$n;
			}
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d WooCommerce products (switch plan boxes to WooCommerce under Customize → RMDHost Theme → Store)', 'rmdhost-core' ), $n );
	}

	/**
	 * Blog posts.
	 *
	 * @param array $catalog Catalogue.
	 */
	private static function import_posts( $catalog ) {
		foreach ( $catalog['posts'] as $p ) {
			$html = '';
			foreach ( $p['body'] as $block ) {
				if ( 'ol' === $block[0] ) {
					$items = '';
					foreach ( (array) $block[1] as $li ) {
						$items .= '<li>' . $li . '</li>';
					}
					$html .= "<!-- wp:list {\"ordered\":true} -->\n<ol class=\"wp-block-list\">" . $items . "</ol>\n<!-- /wp:list -->\n\n";
				} elseif ( 'h2' === $block[0] ) {
					$html .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $block[1] . "</h2>\n<!-- /wp:heading -->\n\n";
				} else {
					$html .= "<!-- wp:paragraph -->\n<p>" . $block[1] . "</p>\n<!-- /wp:paragraph -->\n\n";
				}
			}
			$cat = term_exists( $p['tag'], 'category' );
			if ( ! $cat ) {
				$cat = wp_insert_term( $p['tag'], 'category' );
			}
			self::upsert(
				'post:' . $p['slug'],
				array(
					'post_type'      => 'post',
					'post_title'     => $p['title'],
					'post_name'      => $p['slug'],
					'post_content'   => $html,
					'post_excerpt'   => $p['excerpt'],
					'post_date'      => $p['date'] . ' 09:00:00',
					'comment_status' => 'closed',
					'post_category'  => is_wp_error( $cat ) ? array() : array( (int) $cat['term_id'] ),
				)
			);
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d blog posts', 'rmdhost-core' ), count( $catalog['posts'] ) );
	}

	/**
	 * Pages and reading settings.
	 *
	 * @param array $catalog   Catalogue.
	 * @param bool  $elementor Build the homepage with Elementor.
	 */
	private static function import_pages( $catalog, $elementor ) {
		$patterns = Blocks::pattern_list();
		$legal    = json_decode( (string) file_get_contents( RMDHOST_CORE_DIR . 'demo/legal.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local file.
		$tpl      = static function ( $name ) {
			return 'page-templates/template-' . $name . '.php';
		};
		$pages    = array(
			'home'            => array( __( 'Home', 'rmdhost-core' ), '', '', array() ),
			'blog'            => array( __( 'Blog', 'rmdhost-core' ), '', '', array( '_rmd_hero_title' => __( 'Guides, news and ideas', 'rmdhost-core' ) ) ),
			'pricing'         => array( __( 'Pricing', 'rmdhost-core' ), $tpl( 'pricing' ), '', array( '_rmd_hero_title' => __( 'Simple, transparent pricing', 'rmdhost-core' ) ) ),
			'data-centres'    => array( __( 'Data centres', 'rmdhost-core' ), $tpl( 'data-centres' ), '', array( '_rmd_hero_title' => __( 'Data centres around the world', 'rmdhost-core' ) ) ),
			'ddos-protection' => array(
				__( 'DDoS protection', 'rmdhost-core' ),
				$tpl( 'full-width' ),
				$patterns['ddos'][1],
				array(
					'_rmd_hero_eyebrow' => __( 'Security', 'rmdhost-core' ),
					'_rmd_hero_title'   => __( 'DDoS protection that never sleeps', 'rmdhost-core' ),
					'_rmd_hero_lede'    => __( 'Attacks are detected and filtered at the network edge, so only clean traffic reaches your server. Included on every plan.', 'rmdhost-core' ),
					'_rmd_hero_visual'  => 'shield',
				),
			),
			'network-status'  => array( __( 'Network status', 'rmdhost-core' ), $tpl( 'network-status' ), '', array() ),
			'about'           => array(
				__( 'About us', 'rmdhost-core' ),
				$tpl( 'full-width' ),
				$patterns['about'][1],
				array(
					'_rmd_hero_eyebrow' => __( 'About us', 'rmdhost-core' ),
					'_rmd_hero_title'   => __( 'Infrastructure built on trust and performance', 'rmdhost-core' ),
					'_rmd_hero_lede'    => __( 'We deliver VPS, Windows VPS and dedicated server solutions from state-of-the-art data centres across the UK, Europe and North America.', 'rmdhost-core' ),
				),
			),
			'references'      => array( __( 'References', 'rmdhost-core' ), $tpl( 'references' ), '', array( '_rmd_hero_title' => __( 'Teams that run on RMDHost', 'rmdhost-core' ) ) ),
			'sustainability'  => array(
				__( 'Sustainability', 'rmdhost-core' ),
				$tpl( 'full-width' ),
				$patterns['sustainability'][1],
				array(
					'_rmd_hero_eyebrow' => __( 'Sustainability', 'rmdhost-core' ),
					'_rmd_hero_title'   => __( 'Hosting with a smaller footprint', 'rmdhost-core' ),
					'_rmd_hero_lede'    => __( 'Every watt counts. We design our infrastructure to do more with less energy – and keep hardware in service for longer.', 'rmdhost-core' ),
				),
			),
			'faq'             => array( __( 'FAQ', 'rmdhost-core' ), $tpl( 'faq' ), '', array( '_rmd_hero_title' => __( 'Questions? We’ve got answers', 'rmdhost-core' ) ) ),
			'knowledge-base'  => array( __( 'Knowledge base', 'rmdhost-core' ), $tpl( 'knowledge-base' ), '', array() ),
			'tutorials'       => array( __( 'Tutorials', 'rmdhost-core' ), $tpl( 'tutorials' ), '', array() ),
			'support'         => array( __( 'Support', 'rmdhost-core' ), $tpl( 'support' ), '', array( '_rmd_hero_title' => __( 'Talk to a real engineer, 24/7', 'rmdhost-core' ) ) ),
		);
		foreach ( (array) $legal as $slug => $l ) {
			$pages[ $slug ] = array(
				$l['title'],
				'',
				"<!-- wp:html -->\n" . $l['html'] . "\n<!-- /wp:html -->",
				array(
					'_rmd_hero_eyebrow' => __( 'Legal', 'rmdhost-core' ),
					'_rmd_hero_lede'    => __( 'Last updated: 1 October 2026', 'rmdhost-core' ),
				),
			);
		}

		$ids = array();
		foreach ( $pages as $slug => $p ) {
			$ids[ $slug ] = self::upsert(
				'page:' . $slug,
				array(
					'post_type'    => 'page',
					'post_title'   => $p[0],
					'post_name'    => $slug,
					'post_content' => $p[2],
				),
				array_merge( array( '_wp_page_template' => $p[1] ? $p[1] : 'default' ), $p[3] )
			);
		}
		foreach ( $catalog['products'] as $i => $prod ) {
			$ids[ $prod['slug'] ] = self::upsert(
				'page:' . $prod['slug'],
				array(
					'post_type'    => 'page',
					'post_title'   => $prod['name'],
					'post_name'    => $prod['slug'],
					'post_excerpt' => $prod['description'],
					'menu_order'   => $i,
				),
				array(
					'_wp_page_template' => $tpl( 'server-product' ),
					'_rmd_group'        => $prod['slug'],
				)
			);
		}

		self::tidy_defaults();
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
		update_option( 'page_for_posts', $ids['blog'] );
		if ( ! empty( $ids['privacy-policy'] ) ) {
			update_option( 'wp_page_for_privacy_policy', $ids['privacy-policy'] );
		}
		if ( ! get_option( 'permalink_structure' ) ) {
			// Same URLs as the design: /vps/, /blog/post-name/.
			update_option( 'permalink_structure', '/blog/%postname%/' );
		}

		if ( $elementor ) {
			self::elementor_home( $ids['home'] );
		}
		/* translators: %d: count. */
		self::$log[] = sprintf( __( '%d pages (homepage, product pages, company and legal pages)', 'rmdhost-core' ), count( $ids ) );
	}

	/**
	 * Move WordPress' default widgets out of the blog sidebar and trash the
	 * untouched sample post/page, so the demo looks like the design.
	 */
	private static function tidy_defaults() {
		$sidebars = wp_get_sidebars_widgets();
		if ( ! empty( $sidebars['sidebar-1'] ) ) {
			$sidebars['wp_inactive_widgets'] = array_merge( (array) ( $sidebars['wp_inactive_widgets'] ?? array() ), $sidebars['sidebar-1'] );
			$sidebars['sidebar-1']           = array();
			wp_set_sidebars_widgets( $sidebars );
		}
		foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $sample ) {
			$found = get_posts(
				array(
					'name'        => $sample[0],
					'post_type'   => $sample[1],
					'post_status' => 'publish',
					'numberposts' => 1,
				)
			);
			if ( $found && ! get_post_meta( $found[0]->ID, '_rmd_demo', true ) && strtotime( $found[0]->post_modified_gmt ) - strtotime( $found[0]->post_date_gmt ) < 60 ) {
				wp_trash_post( $found[0]->ID );
			}
		}
	}

	/**
	 * Homepage as an Elementor layout made of RMDHost widgets.
	 *
	 * @param int $page_id Page ID.
	 */
	private static function elementor_home( $page_id ) {
		$widgets = array();
		foreach ( array( 'hero', 'promo', 'finder', 'tools', 'essentials', 'alt', 'support', 'pricing', 'automation', 'locations', 'testimonials', 'faq', 'cta' ) as $slug ) {
			$widgets[] = array(
				'id'         => substr( md5( 'rmd-w-' . $slug ), 0, 7 ),
				'elType'     => 'widget',
				'widgetType' => 'rmdhost-' . $slug,
				'settings'   => new \stdClass(),
				'elements'   => array(),
			);
		}
		$zero = array(
			'unit'     => 'px',
			'top'      => '0',
			'right'    => '0',
			'bottom'   => '0',
			'left'     => '0',
			'isLinked' => true,
		);
		$data = array(
			array(
				'id'       => 'rmdhome',
				'elType'   => 'container',
				'isInner'  => false,
				'settings' => array(
					'content_width' => 'full',
					'flex_gap'      => array(
						'unit'     => 'px',
						'size'     => 0,
						'column'   => '0',
						'row'      => '0',
						'isLinked' => true,
					),
					'padding'       => $zero,
					'margin'        => $zero,
				),
				'elements' => $widgets,
			),
		);
		update_post_meta( $page_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $page_id, '_elementor_template_type', 'wp-page' );
		update_post_meta( $page_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
		update_post_meta( $page_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
		update_post_meta( $page_id, '_rmd_transparent_header', true );
		update_post_meta( $page_id, '_wp_page_template', 'page-templates/template-canvas.php' );
		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
		self::$log[] = __( 'Homepage built with RMDHost Elementor widgets', 'rmdhost-core' );
	}

	/**
	 * Menus.
	 *
	 * @param array $catalog Catalogue.
	 */
	private static function import_menus( $catalog ) {
		$url       = static function ( $href ) {
			return ( $href && '/' === $href[0] ) ? home_url( $href ) : $href;
		};
		$locations = get_theme_mod( 'nav_menu_locations', array() );

		$menu = self::fresh_menu( __( 'Primary (mega menu)', 'rmdhost-core' ) );
		foreach ( $catalog['nav'] as $pos => $top ) {
			$top_id = wp_update_nav_menu_item(
				$menu,
				0,
				array(
					'menu-item-title'    => $top['label'],
					'menu-item-url'      => ! empty( $top['href'] ) ? $url( $top['href'] ) : '#',
					'menu-item-status'   => 'publish',
					'menu-item-position' => $pos + 1,
				)
			);
			if ( ! empty( $top['promo'] ) ) {
				foreach ( array( 'visual', 'eyebrow', 'title', 'text', 'cta' ) as $k ) {
					update_post_meta( $top_id, '_rmd_promo_' . $k, $top['promo'][ $k ] ?? '' );
				}
				update_post_meta( $top_id, '_rmd_promo_url', $url( $top['promo']['href'] ?? '' ) );
			}
			foreach ( (array) ( $top['mega'] ?? array() ) as $col ) {
				$col_id = wp_update_nav_menu_item(
					$menu,
					0,
					array(
						'menu-item-title'     => $col['title'],
						'menu-item-url'       => '#',
						'menu-item-parent-id' => $top_id,
						'menu-item-status'    => 'publish',
					)
				);
				foreach ( $col['items'] as $item ) {
					$id = wp_update_nav_menu_item(
						$menu,
						0,
						array(
							'menu-item-title'       => $item['label'],
							'menu-item-url'         => $url( $item['href'] ),
							'menu-item-description' => $item['desc'] ?? '',
							'menu-item-parent-id'   => $col_id,
							'menu-item-status'      => 'publish',
						)
					);
					update_post_meta( $id, '_rmd_icon', $item['icon'] ?? 'server' );
					update_post_meta( $id, '_rmd_tag', $item['tag'] ?? '' );
				}
			}
		}
		$locations['primary'] = $menu;

		foreach ( array_values( $catalog['footer'] ) as $i => $col ) {
			if ( $i >= 5 ) {
				break;
			}
			$m = self::fresh_menu( $col['title'] );
			foreach ( $col['links'] as $pos => $link ) {
				wp_update_nav_menu_item(
					$m,
					0,
					array(
						'menu-item-title'    => $link['label'],
						'menu-item-url'      => $url( $link['href'] ),
						'menu-item-status'   => 'publish',
						'menu-item-position' => $pos + 1,
					)
				);
			}
			$locations[ 'footer-' . ( $i + 1 ) ] = $m;
		}

		$legal = self::fresh_menu( __( 'Legal', 'rmdhost-core' ) );
		foreach ( array( 'privacy-policy', 'terms-of-service', 'cookie-policy' ) as $pos => $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page ) {
				wp_update_nav_menu_item(
					$legal,
					0,
					array(
						'menu-item-object-id' => $page->ID,
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $pos + 1,
					)
				);
			}
		}
		$locations['legal'] = $legal;
		set_theme_mod( 'nav_menu_locations', $locations );
		self::$log[] = __( 'Mega menu, footer menus and legal menu assigned', 'rmdhost-core' );
	}

	/**
	 * Create (or empty) a menu by name.
	 *
	 * @param string $name Menu name.
	 * @return int
	 */
	private static function fresh_menu( $name ) {
		$menu = wp_get_nav_menu_object( $name );
		if ( $menu ) {
			foreach ( (array) wp_get_nav_menu_items( $menu->term_id, array( 'post_status' => 'any' ) ) as $item ) {
				wp_delete_post( $item->ID, true );
			}
			return (int) $menu->term_id;
		}
		return (int) wp_create_nav_menu( $name );
	}
}
