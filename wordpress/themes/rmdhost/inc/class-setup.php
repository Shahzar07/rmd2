<?php
/**
 * Theme supports, menus, sidebars and editor integration.
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup.
 */
class Setup {

	/**
	 * Hook everything up.
	 */
	public static function init() {
		add_action( 'after_setup_theme', array( __CLASS__, 'setup' ) );
		add_action( 'widgets_init', array( __CLASS__, 'sidebars' ) );
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
		add_filter( 'excerpt_more', array( __CLASS__, 'excerpt_more' ) );
		add_filter( 'post_class', array( __CLASS__, 'post_class' ), 10, 2 );
	}

	/**
	 * Theme supports.
	 */
	public static function setup() {
		load_theme_textdomain( 'rmdhost', RMDHOST_DIR . '/languages' );

		$GLOBALS['content_width'] = apply_filters( 'rmdhost_content_width', 880 );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 56,
				'width'       => 220,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		add_editor_style( array( 'assets/css/editor.css' ) );

		add_image_size( 'rmdhost-card', 800, 450, true );

		register_nav_menus(
			array(
				'primary'  => __( 'Primary (mega menu)', 'rmdhost' ),
				'footer-1' => __( 'Footer column 1', 'rmdhost' ),
				'footer-2' => __( 'Footer column 2', 'rmdhost' ),
				'footer-3' => __( 'Footer column 3', 'rmdhost' ),
				'footer-4' => __( 'Footer column 4', 'rmdhost' ),
				'footer-5' => __( 'Footer column 5', 'rmdhost' ),
				'legal'    => __( 'Footer legal links', 'rmdhost' ),
			)
		);
	}

	/**
	 * Widget areas.
	 */
	public static function sidebars() {
		$common = array(
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		);
		register_sidebar(
			array_merge(
				$common,
				array(
					'name'        => __( 'Blog sidebar', 'rmdhost' ),
					'id'          => 'sidebar-1',
					'description' => __( 'Shown next to blog posts and archives.', 'rmdhost' ),
				)
			)
		);
		register_sidebar(
			array_merge(
				$common,
				array(
					'name'        => __( 'Shop sidebar', 'rmdhost' ),
					'id'          => 'sidebar-shop',
					'description' => __( 'Shown on WooCommerce shop and category pages.', 'rmdhost' ),
				)
			)
		);
		register_sidebar(
			array_merge(
				$common,
				array(
					'name'        => __( 'Footer top', 'rmdhost' ),
					'id'          => 'footer-top',
					'description' => __( 'Optional full-width widget row above the footer columns.', 'rmdhost' ),
				)
			)
		);
	}

	/**
	 * Body classes.
	 *
	 * @param string[] $classes Classes.
	 * @return string[]
	 */
	public static function body_class( $classes ) {
		if ( is_singular() && rmdhost_is_elementor() ) {
			$classes[] = 'rmd-elementor';
		}
		if ( ! self::has_dark_top() ) {
			$classes[] = 'rmd-solid-header';
		}
		if ( is_active_sidebar( 'sidebar-1' ) && ( is_home() || is_singular( 'post' ) || is_archive() ) ) {
			$classes[] = 'has-sidebar';
		}
		return $classes;
	}

	/**
	 * Whether the page starts with a dark hero the header can sit on transparently.
	 *
	 * @return bool
	 */
	public static function has_dark_top() {
		$dark = true;
		if ( is_singular() && ! is_front_page() ) {
			$id   = get_queried_object_id();
			$tpl  = get_page_template_slug( $id );
			$dark = ! get_post_meta( $id, '_rmd_hide_hero', true )
				&& 'page-templates/template-canvas.php' !== $tpl
				&& ( ! rmdhost_is_elementor( $id ) || get_post_meta( $id, '_rmd_transparent_header', true ) );
		} elseif ( is_front_page() && 'page' === get_option( 'show_on_front' ) ) {
			$id   = (int) get_option( 'page_on_front' );
			$dark = ! rmdhost_is_elementor( $id ) || (bool) get_post_meta( $id, '_rmd_transparent_header', true );
		}
		if ( function_exists( 'is_product' ) && is_product() ) {
			$dark = false;
		}
		/**
		 * Filters whether the header is transparent over a dark first section.
		 *
		 * @param bool $dark Dark top.
		 */
		return (bool) apply_filters( 'rmdhost/transparent_header', $dark );
	}

	/**
	 * Drop the bare "post" class WordPress adds for the post type (it would pick up
	 * the blog-card styles); "type-post" stays. Kept when requested explicitly.
	 *
	 * @param string[]        $classes Classes.
	 * @param string|string[] $extra   Classes requested by the template.
	 * @return string[]
	 */
	public static function post_class( $classes, $extra ) {
		$extra = is_array( $extra ) ? $extra : preg_split( '#\s+#', (string) $extra );
		if ( ! in_array( 'post', $extra, true ) ) {
			$classes = array_values( array_diff( $classes, array( 'post' ) ) );
		}
		return $classes;
	}

	/**
	 * Excerpt "more" string.
	 *
	 * @return string
	 */
	public static function excerpt_more() {
		return '…';
	}
}
