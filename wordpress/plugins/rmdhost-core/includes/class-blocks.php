<?php
/**
 * Block editor: the "RMDHost section" block (server-rendered), a preview
 * endpoint and page patterns.
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Blocks.
 */
class Blocks {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
		add_action( 'init', array( __CLASS__, 'patterns' ), 20 );
		add_action( 'rest_api_init', array( __CLASS__, 'routes' ) );
		add_filter( 'block_categories_all', array( __CLASS__, 'category' ) );
	}

	/**
	 * Block category.
	 *
	 * @param array $cats Categories.
	 * @return array
	 */
	public static function category( $cats ) {
		array_unshift(
			$cats,
			array(
				'slug'  => 'rmdhost',
				'title' => __( 'RMDHost', 'rmdhost-core' ),
			)
		);
		return $cats;
	}

	/**
	 * Register block + editor script.
	 */
	public static function register() {
		wp_register_script(
			'rmdhost-section-block',
			RMDHOST_CORE_URL . 'assets/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-api-fetch', 'wp-compose' ),
			RMDHOST_CORE_VERSION,
			true
		);
		wp_set_script_translations( 'rmdhost-section-block', 'rmdhost-core', RMDHOST_CORE_DIR . 'languages' );

		register_block_type(
			'rmdhost/section',
			array(
				'api_version'     => 3,
				'title'           => __( 'RMDHost section', 'rmdhost-core' ),
				'category'        => 'rmdhost',
				'icon'            => 'cloud',
				'editor_script'   => 'rmdhost-section-block',
				'attributes'      => array(
					'section' => array(
						'type'    => 'string',
						'default' => '',
					),
					'args'    => array(
						'type'    => 'object',
						'default' => array(),
					),
					'align'   => array(
						'type'    => 'string',
						'default' => 'full',
					),
				),
				'supports'        => array(
					'html'     => false,
					'align'    => array( 'full' ),
					'anchor'   => false,
					'multiple' => true,
				),
				'render_callback' => array( __CLASS__, 'render' ),
			)
		);

		if ( is_admin() ) {
			add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'editor_data' ) );
		}
	}

	/**
	 * Schema + preview assets for the editor script.
	 */
	public static function editor_data() {
		$sections = Render::sections();
		$fields   = array();
		$defaults = array();
		foreach ( array_keys( $sections ) as $slug ) {
			$fields[ $slug ]   = Schema::fields( $slug );
			$defaults[ $slug ] = function_exists( 'rmdhost_section_defaults' ) ? rmdhost_section_defaults( $slug ) : array();
		}
		$theme = get_template_directory_uri();
		$data  = array(
			'sections' => $sections,
			'fields'   => $fields,
			'defaults' => $defaults,
			'groups'   => Schema::groups(),
			'icons'    => Schema::icons(),
			'ready'    => Render::theme_ready(),
			'styles'   => array_values(
				array_filter(
					array(
						class_exists( '\RMDHost\Assets' ) ? \RMDHost\Assets::fonts_url() : '',
						$theme . '/assets/css/main.css',
						$theme . '/assets/css/wordpress.css',
					)
				)
			),
			'script'   => $theme . '/assets/js/main.js',
		);
		wp_add_inline_script( 'rmdhost-section-block', 'window.RMDHostBlock = ' . wp_json_encode( $data ) . ';', 'before' );
	}

	/**
	 * Front-end render.
	 *
	 * @param array $attrs Attributes.
	 * @return string
	 */
	public static function render( $attrs ) {
		$section = isset( $attrs['section'] ) ? sanitize_key( $attrs['section'] ) : '';
		if ( ! $section ) {
			return '';
		}
		return Render::section( $section, Schema::to_args( $section, (array) ( $attrs['args'] ?? array() ) ) );
	}

	/**
	 * Preview endpoint used by the editor.
	 */
	public static function routes() {
		register_rest_route(
			'rmdhost-core/v1',
			'/render',
			array(
				'methods'             => 'POST',
				'permission_callback' => static function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => array(
					'section' => array(
						'type'     => 'string',
						'required' => true,
					),
					'args'    => array(
						'type'    => 'object',
						'default' => array(),
					),
				),
				'callback'            => static function ( \WP_REST_Request $request ) {
					return array(
						'html' => self::render(
							array(
								'section' => $request['section'],
								'args'    => (array) $request['args'],
							)
						),
					);
				},
			)
		);
	}

	/**
	 * Block markup helper.
	 *
	 * @param string $section Section.
	 * @param array  $args    Args.
	 * @return string
	 */
	public static function block( $section, $args = array() ) {
		$attrs = array( 'section' => $section );
		if ( $args ) {
			$attrs['args'] = $args;
		}
		return '<!-- wp:rmdhost/section ' . wp_json_encode( $attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . ' /-->';
	}

	/**
	 * Page patterns (used by the demo importer, available in the inserter).
	 *
	 * @return array slug => array( title, content )
	 */
	public static function pattern_list() {
		$features = static function ( $rows ) {
			$out = array();
			foreach ( $rows as $r ) {
				$out[] = array(
					'icon'  => $r[0],
					'title' => $r[1],
					'text'  => $r[2],
				);
			}
			return $out;
		};
		return array(
			'about'          => array(
				__( 'About us page', 'rmdhost-core' ),
				self::block(
					'cards',
					array(
						'photo'     => 'agency-team',
						'photo_alt' => __( 'Team collaborating in a bright office', 'rmdhost-core' ),
						'caption'   => __( 'Real people building infrastructure you can trust.', 'rmdhost-core' ),
						'stats'     => true,
						'items'     => array(
							array(
								'title' => __( 'Our mission', 'rmdhost-core' ),
								'text'  => __( 'To deliver enterprise-grade hosting infrastructure that is fast, reliable and accessible to businesses of every size – without the enterprise price tag.', 'rmdhost-core' ),
							),
							array(
								'title' => __( 'Our vision', 'rmdhost-core' ),
								'text'  => __( 'A world where any business can deploy global infrastructure in minutes, with the confidence that their applications are protected by world-class security and support.', 'rmdhost-core' ),
							),
							array(
								'title' => __( 'Our values', 'rmdhost-core' ),
								'text'  => __( 'Transparency in pricing, excellence in support and a relentless commitment to performance. We treat your infrastructure like our own.', 'rmdhost-core' ),
							),
						),
					)
				) . "\n\n" . self::block(
					'features',
					array(
						'title'    => __( 'Why teams choose RMDHost', 'rmdhost-core' ),
						'id'       => '',
						'features' => $features(
							array(
								array( 'bolt', __( 'NVMe & SSD everywhere', 'rmdhost-core' ), __( 'Enterprise drives delivering up to 640,000 IOPS.', 'rmdhost-core' ) ),
								array( 'shield', __( 'Anti-DDoS Pro', 'rmdhost-core' ), __( 'Multi-layer filtering included on every plan.', 'rmdhost-core' ) ),
								array( 'globe', __( 'Six locations', 'rmdhost-core' ), __( 'UK, EU and US data centres with LINX connectivity.', 'rmdhost-core' ) ),
								array( 'headset', __( '24/7 engineers', 'rmdhost-core' ), __( 'Real people, real answers – no chatbots.', 'rmdhost-core' ) ),
								array( 'backup', __( 'Backups included', 'rmdhost-core' ), __( 'Free backups on Windows VPS, snapshots on Linux.', 'rmdhost-core' ) ),
								array( 'check', __( '99.99% uptime SLA', 'rmdhost-core' ), __( 'Redundant power, cooling and network paths.', 'rmdhost-core' ) ),
							)
						),
					)
				) . "\n\n" . self::block( 'cta' ),
			),
			'ddos'           => array(
				__( 'DDoS protection page', 'rmdhost-core' ),
				self::block(
					'steps',
					array(
						'title' => __( 'How it works', 'rmdhost-core' ),
						'items' => array(
							array(
								'title' => __( 'Detect', 'rmdhost-core' ),
								'text'  => __( 'Traffic is analysed continuously for volumetric and protocol anomalies.', 'rmdhost-core' ),
							),
							array(
								'title' => __( 'Filter', 'rmdhost-core' ),
								'text'  => __( 'Malicious packets are dropped at the edge before they reach your network port.', 'rmdhost-core' ),
							),
							array(
								'title' => __( 'Deliver', 'rmdhost-core' ),
								'text'  => __( 'Clean traffic is forwarded to your server with no change to your setup.', 'rmdhost-core' ),
							),
							array(
								'title' => __( 'Report', 'rmdhost-core' ),
								'text'  => __( 'Our team monitors every mitigation and contacts you if action is needed.', 'rmdhost-core' ),
							),
						),
					)
				) . "\n\n" . self::block(
					'features',
					array(
						'title'    => __( 'Protection for every workload', 'rmdhost-core' ),
						'id'       => '',
						'features' => $features(
							array(
								array( 'server', __( 'VPS & Cloud', 'rmdhost-core' ), __( 'Always-on filtering for websites, APIs and applications.', 'rmdhost-core' ) ),
								array( 'rack', __( 'Dedicated servers', 'rmdhost-core' ), __( 'High-capacity mitigation for bare-metal workloads.', 'rmdhost-core' ) ),
								array( 'game', __( 'Anti-DDoS Game', 'rmdhost-core' ), __( 'Game-aware filtering for Minecraft, CS, ARMA, GTA, Team Fortress and TeamSpeak.', 'rmdhost-core' ) ),
								array( 'pulse', __( 'Layer 3/4 attacks', 'rmdhost-core' ), __( 'UDP, SYN and amplification floods filtered automatically.', 'rmdhost-core' ) ),
								array( 'clock', __( 'Instant reaction', 'rmdhost-core' ), __( 'Mitigation starts within seconds – no manual switching.', 'rmdhost-core' ) ),
								array( 'check', __( 'No extra cost', 'rmdhost-core' ), __( 'Included in every price you see on our site.', 'rmdhost-core' ) ),
							)
						),
					)
				) . "\n\n" . self::block( 'cta', array( 'title' => __( 'Stay online.<br>Whatever happens.', 'rmdhost-core' ) ) ),
			),
			'sustainability' => array(
				__( 'Sustainability page', 'rmdhost-core' ),
				self::block(
					'features',
					array(
						'title'          => '',
						'soft'           => false,
						'id'             => '',
						'banner'         => 'datacentre-building',
						'banner_alt'     => __( 'Data centre building surrounded by trees', 'rmdhost-core' ),
						'banner_caption' => __( 'Doing more with every watt.', 'rmdhost-core' ),
						'features'       => $features(
							array(
								array( 'leaf', __( 'Efficient facilities', 'rmdhost-core' ), __( 'Our data centres use N+1 cooling with under-floor air distribution to reduce energy waste.', 'rmdhost-core' ) ),
								array( 'server', __( 'High utilisation', 'rmdhost-core' ), __( 'Virtualisation lets many customers share efficient hardware instead of idling separate machines.', 'rmdhost-core' ) ),
								array( 'refresh', __( 'Hardware reuse', 'rmdhost-core' ), __( 'Proven server platforms are refurbished and kept in service, reducing e-waste.', 'rmdhost-core' ) ),
								array( 'bolt', __( 'Modern components', 'rmdhost-core' ), __( 'NVMe and modern CPUs deliver more performance per watt.', 'rmdhost-core' ) ),
								array( 'globe', __( 'Local hosting', 'rmdhost-core' ), __( 'Serve users from the nearest location to reduce network energy and latency.', 'rmdhost-core' ) ),
								array( 'check', __( 'Continuous improvement', 'rmdhost-core' ), __( 'We review our energy sources and efficiency every year.', 'rmdhost-core' ) ),
							)
						),
					)
				) . "\n\n" . self::block( 'cta' ),
			),
			'landing'        => array(
				__( 'Product landing (Linux VPS)', 'rmdhost-core' ),
				implode(
					"\n\n",
					array(
						self::block( 'product-hero', array( 'group' => 'vps' ) ),
						self::block( 'plans', array( 'group' => 'vps' ) ),
						self::block( 'features', array( 'group' => 'vps' ) ),
						self::block(
							'locations',
							array(
								'id'    => 'locations',
								'stats' => false,
							)
						),
						self::block( 'apps', array( 'group' => 'vps' ) ),
						self::block(
							'faq',
							array(
								'group' => 'vps',
								'id'    => 'faq',
							)
						),
						self::block( 'cta' ),
					)
				),
			),
			'homepage'       => array(
				__( 'Full homepage', 'rmdhost-core' ),
				implode(
					"\n\n",
					array_map(
						array( __CLASS__, 'block' ),
						array( 'hero', 'promo', 'finder', 'tools', 'essentials', 'alt', 'support', 'pricing', 'automation', 'locations', 'testimonials', 'faq', 'cta' )
					)
				),
			),
		);
	}

	/**
	 * Register patterns.
	 */
	public static function patterns() {
		if ( ! function_exists( 'register_block_pattern' ) ) {
			return;
		}
		register_block_pattern_category( 'rmdhost', array( 'label' => __( 'RMDHost pages', 'rmdhost-core' ) ) );
		foreach ( self::pattern_list() as $slug => $p ) {
			register_block_pattern(
				'rmdhost/' . $slug,
				array(
					'title'      => $p[0],
					'categories' => array( 'rmdhost' ),
					'content'    => $p[1],
				)
			);
		}
	}
}
