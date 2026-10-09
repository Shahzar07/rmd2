<?php
/**
 * Lightweight SEO output: meta description, Open Graph and JSON-LD.
 * Steps aside automatically when a dedicated SEO plugin is active.
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * SEO.
 */
class SEO {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'wp', array( __CLASS__, 'maybe_hook' ) );
	}

	/**
	 * Only output when no SEO plugin handles it.
	 */
	public static function maybe_hook() {
		$plugin = defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' ) || class_exists( 'The_SEO_Framework\\Load' );
		if ( apply_filters( 'rmdhost/seo_enabled', ! $plugin ) ) {
			add_action( 'wp_head', array( __CLASS__, 'meta' ), 2 );
			add_action( 'wp_head', array( __CLASS__, 'schema' ), 30 );
			add_filter( 'document_title_parts', array( __CLASS__, 'title' ) );
		}
	}

	/**
	 * Product group for the current page (Server product template).
	 *
	 * @return array|null
	 */
	public static function current_group() {
		if ( ! is_page() || 'page-templates/template-server-product.php' !== get_page_template_slug() ) {
			return null;
		}
		$slug = get_post_meta( get_queried_object_id(), '_rmd_group', true );
		return $slug ? Data::group( $slug ) : null;
	}

	/**
	 * Use the product SEO title on server product pages.
	 *
	 * @param array $parts Title parts.
	 * @return array
	 */
	public static function title( $parts ) {
		$group = self::current_group();
		if ( $group && ! empty( $group['title'] ) ) {
			return array( 'title' => $group['title'] );
		}
		return $parts;
	}

	/**
	 * Description for the current request.
	 *
	 * @return string
	 */
	public static function description() {
		$group = self::current_group();
		if ( $group && ! empty( $group['description'] ) ) {
			return $group['description'];
		}
		if ( is_singular() ) {
			$post = get_queried_object();
			$lede = get_post_meta( $post->ID, '_rmd_hero_lede', true );
			if ( $lede ) {
				return $lede;
			}
			if ( has_excerpt( $post ) ) {
				return get_the_excerpt( $post );
			}
			return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '…' );
		}
		if ( is_category() || is_tag() || is_tax() ) {
			return wp_strip_all_tags( term_description() );
		}
		return get_bloginfo( 'description' );
	}

	/**
	 * Meta + Open Graph + Twitter.
	 */
	public static function meta() {
		$desc  = trim( self::description() );
		$title = wp_get_document_title();
		$url   = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
		$image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'large' ) : RMDHOST_URI . '/assets/img/og.svg';
		if ( $desc ) {
			echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		}
		printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( is_singular( 'post' ) ? 'article' : 'website' ) );
		printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
		if ( $desc ) {
			printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
		}
		printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	}

	/**
	 * JSON-LD structured data.
	 */
	public static function schema() {
		$graph   = array();
		$graph[] = array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
			'logo'  => RMDHOST_URI . '/assets/img/logo.svg',
		);
		if ( is_front_page() ) {
			$graph[] = array(
				'@type' => 'WebSite',
				'name'  => get_bloginfo( 'name' ),
				'url'   => home_url( '/' ),
			);
			$graph[] = self::faq_schema( Data::faqs( 'general' ) );
		}
		$group = self::current_group();
		if ( $group ) {
			$prices  = array_filter(
				wp_list_pluck( Data::plans( $group['slug'] ), 'price' ),
				static function ( $p ) {
					return null !== $p;
				}
			);
			$product = array(
				'@type'       => 'Product',
				'name'        => get_bloginfo( 'name' ) . ' ' . $group['name'],
				'description' => $group['description'] ?? '',
				'brand'       => array(
					'@type' => 'Brand',
					'name'  => get_bloginfo( 'name' ),
				),
			);
			if ( $prices ) {
				$product['offers'] = array(
					'@type'         => 'AggregateOffer',
					'priceCurrency' => 'GBP',
					'lowPrice'      => min( $prices ),
					'highPrice'     => max( $prices ),
					'offerCount'    => count( $prices ),
					'url'           => get_permalink(),
				);
			}
			$graph[] = $product;
			if ( Data::faqs( $group['slug'] ) ) {
				$graph[] = self::faq_schema( Data::faqs( $group['slug'] ) );
			}
		}
		if ( is_singular( 'post' ) ) {
			$graph[] = array(
				'@type'            => 'BlogPosting',
				'headline'         => get_the_title(),
				'datePublished'    => get_the_date( 'c' ),
				'dateModified'     => get_the_modified_date( 'c' ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => get_the_author(),
				),
				'mainEntityOfPage' => get_permalink(),
			);
		}
		if ( is_singular() && ! is_front_page() ) {
			$items = array();
			foreach ( rmdhost_current_trail() as $i => $crumb ) {
				$items[] = array(
					'@type'    => 'ListItem',
					'position' => $i + 1,
					'name'     => $crumb[0],
					'item'     => $crumb[1] ? $crumb[1] : get_permalink(),
				);
			}
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $items,
			);
		}
		$graph = array_values( array_filter( apply_filters( 'rmdhost/schema', $graph ) ) );
		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			)
		) . '</script>' . "\n";
	}

	/**
	 * FAQPage schema.
	 *
	 * @param array $faqs FAQs.
	 * @return array|null
	 */
	public static function faq_schema( $faqs ) {
		if ( ! $faqs ) {
			return null;
		}
		$entities = array();
		foreach ( $faqs as $f ) {
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $f['q'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $f['a'] ),
				),
			);
		}
		return array(
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		);
	}
}
