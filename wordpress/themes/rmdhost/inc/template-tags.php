<?php
/**
 * Template tags: small, reusable render helpers shared by templates,
 * Elementor widgets and blocks. Each mirrors a component of the static site.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

/**
 * Inline SVG icon.
 *
 * @param string $name  Icon key (see inc/generated/icons.php).
 * @param string $css_class Extra CSS class.
 * @return string
 */
function rmdhost_icon( $name, $css_class = '' ) {
	static $icons = null;
	if ( null === $icons ) {
		$icons = require RMDHOST_DIR . '/inc/generated/icons.php';
	}
	$path = isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['server'];
	return '<svg class="ico' . ( $css_class ? ' ' . esc_attr( $css_class ) : '' ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>'; // Paths are static theme data.
}

/**
 * List of available icon keys (for admin selects and widgets).
 *
 * @return string[]
 */
function rmdhost_icon_names() {
	$icons = require RMDHOST_DIR . '/inc/generated/icons.php';
	return array_keys( $icons );
}

/**
 * Allowed inline HTML for headings and short copy (line breaks, emphasis).
 *
 * @param string $html Text with optional inline markup.
 * @return string
 */
function rmdhost_kses_inline( $html ) {
	return wp_kses(
		(string) $html,
		array(
			'br'     => array(),
			'b'      => array(),
			'strong' => array(),
			'em'     => array( 'class' => true ),
			'i'      => array( 'class' => true ),
			'u'      => array(),
			'span'   => array( 'class' => true ),
			'a'      => array(
				'href'   => true,
				'class'  => true,
				'target' => true,
				'rel'    => true,
			),
		)
	);
}

/**
 * Turn a site-relative path ("/vps/") into a full URL; leave absolute URLs alone.
 *
 * @param string $href Path, anchor or URL.
 * @return string
 */
function rmdhost_url( $href ) {
	$href = (string) $href;
	if ( '' === $href ) {
		return '';
	}
	if ( '#' === $href[0] || preg_match( '#^(https?:|mailto:|tel:)#i', $href ) ) {
		return $href;
	}
	if ( '/' === $href[0] ) {
		return home_url( $href );
	}
	return $href;
}

/**
 * Price markup with GBP/USD switching (handled by main.js).
 *
 * @param float|null $gbp  Monthly price in GBP; null shows "coming soon".
 * @param array      $args usd (fixed USD price), per (suffix), class.
 * @return string
 */
function rmdhost_money( $gbp, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'usd'   => '',
			'per'   => __( '/mo', 'rmdhost' ),
			'class' => '',
		)
	);
	if ( null === $gbp || '' === $gbp ) {
		return '<span class="price-soon ' . esc_attr( $args['class'] ) . '">' . esc_html__( 'Price coming soon', 'rmdhost' ) . '</span>';
	}
	$gbp    = (float) $gbp;
	$amount = ( floor( $gbp ) === $gbp ) ? (string) (int) $gbp : number_format( $gbp, 2, '.', '' );
	$out    = sprintf(
		'<span class="price %1$s" data-price data-gbp="%2$s"%3$s><span data-sym>£</span><span data-amt>%4$s</span></span>',
		esc_attr( $args['class'] ),
		esc_attr( $gbp ),
		$args['usd'] ? ' data-usd="' . esc_attr( $args['usd'] ) . '"' : '',
		esc_html( $amount )
	);
	if ( $args['per'] ) {
		$out .= '<span class="per">' . esc_html( $args['per'] ) . '</span>';
	}
	return $out;
}

/**
 * Theme logo: custom logo if set, otherwise the RMDHost mark + site name.
 *
 * @param string $css_class Extra class.
 * @return string
 */
function rmdhost_logo( $css_class = '' ) {
	if ( has_custom_logo() ) {
		$logo = get_custom_logo();
		return str_replace( 'custom-logo-link', 'custom-logo-link logo ' . esc_attr( $css_class ), $logo );
	}
	return sprintf(
		'<a href="%1$s" class="logo %2$s" aria-label="%3$s"><svg class="logo-mark" viewBox="0 0 28 28" aria-hidden="true"><rect width="28" height="28" fill="currentColor"/><path class="logo-slots" d="M7 8.5h14M7 14h14M7 19.5h9"/><rect class="logo-led" x="19" y="18" width="3" height="3"/></svg><span class="logo-word">%4$s</span></a>',
		esc_url( home_url( '/' ) ),
		esc_attr( $css_class ),
		/* translators: %s: site name */
		esc_attr( sprintf( __( '%s home', 'rmdhost' ), get_bloginfo( 'name' ) ) ),
		esc_html( get_bloginfo( 'name' ) )
	);
}

/**
 * Image from the media library (attachment ID) or a bundled theme image (name).
 *
 * @param int|string $src  Attachment ID, bundled image name or URL.
 * @param string     $alt  Alt text (bundled images / URLs).
 * @param array      $args class, sizes, eager.
 * @return string
 */
function rmdhost_image( $src, $alt = '', $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'class' => '',
			'sizes' => '(max-width: 900px) 100vw, 50vw',
			'eager' => false,
		)
	);
	if ( empty( $src ) ) {
		return '';
	}
	if ( is_numeric( $src ) ) {
		$attr = array(
			'class'    => $args['class'],
			'sizes'    => $args['sizes'],
			'decoding' => 'async',
			'loading'  => $args['eager'] ? 'eager' : 'lazy',
		);
		if ( $alt ) {
			$attr['alt'] = $alt;
		}
		return wp_get_attachment_image( (int) $src, 'large', false, $attr );
	}
	if ( preg_match( '#^https?://#', $src ) ) {
		return sprintf( '<img class="%1$s" src="%2$s" alt="%3$s" loading="lazy" decoding="async">', esc_attr( $args['class'] ), esc_url( $src ), esc_attr( $alt ) );
	}
	static $media = null;
	if ( null === $media ) {
		$media = require RMDHOST_DIR . '/inc/generated/media.php';
	}
	$name = sanitize_file_name( $src );
	$m    = isset( $media[ $name ] ) ? $media[ $name ] : array();
	$base = RMDHOST_URI . '/assets/media/' . $name;
	return sprintf(
		'<img class="%1$s" src="%2$s"%3$s alt="%4$s"%5$s %6$s decoding="async">',
		esc_attr( $args['class'] ),
		esc_url( $base . '.webp' ),
		! empty( $m['small'] ) ? ' srcset="' . esc_url( $base . '-sm.webp' ) . ' ' . (int) $m['small'] . 'w, ' . esc_url( $base . '.webp' ) . ' ' . (int) $m['w'] . 'w" sizes="' . esc_attr( $args['sizes'] ) . '"' : '',
		esc_attr( $alt ),
		! empty( $m['w'] ) ? ' width="' . (int) $m['w'] . '" height="' . (int) $m['h'] . '"' : '',
		$args['eager'] ? 'fetchpriority="high"' : 'loading="lazy"'
	);
}

/**
 * Muted, looping background video (plays only while visible – see main.js).
 *
 * @param int|string $src  Attachment ID or bundled video name.
 * @param string     $css_class CSS class.
 * @return string
 */
function rmdhost_video( $src, $css_class = '' ) {
	if ( is_numeric( $src ) ) {
		$url = wp_get_attachment_url( (int) $src );
		return $url ? sprintf( '<video class="%1$s" muted loop playsinline preload="none" data-autoplay aria-hidden="true"><source src="%2$s"></video>', esc_attr( $css_class ), esc_url( $url ) ) : '';
	}
	$base = RMDHOST_URI . '/assets/media/' . sanitize_file_name( $src );
	return sprintf(
		'<video class="%1$s" muted loop playsinline preload="none" data-autoplay poster="%2$s" aria-hidden="true"><source src="%3$s" type="video/mp4"><source src="%4$s" type="video/webm"></video>',
		esc_attr( $css_class ),
		esc_url( $base . '-poster.webp' ),
		esc_url( $base . '.mp4' ),
		esc_url( $base . '.webm' )
	);
}

/**
 * Decorative animated scene (static markup generated from the design source).
 *
 * @param string $name Scene name: dashboard, terminal, windows, cloud, storage, ai, mac, rack, game, shield or tech-*.
 * @return string
 */
function rmdhost_scene( $name ) {
	$name = sanitize_key( $name );
	$file = RMDHOST_DIR . '/template-parts/scenes/' . $name . '.php';
	if ( ! file_exists( $file ) ) {
		return '';
	}
	ob_start();
	include $file;
	return ob_get_clean();
}

/**
 * Scene names offered to editors.
 *
 * @return array
 */
function rmdhost_scene_choices() {
	return array(
		'dashboard' => __( 'Server dashboard', 'rmdhost' ),
		'terminal'  => __( 'Terminal deploy', 'rmdhost' ),
		'windows'   => __( 'Remote desktop', 'rmdhost' ),
		'cloud'     => __( 'Cloud nodes', 'rmdhost' ),
		'storage'   => __( 'File sync', 'rmdhost' ),
		'ai'        => __( 'AI chat', 'rmdhost' ),
		'mac'       => __( 'Xcode build', 'rmdhost' ),
		'rack'      => __( 'Server rack', 'rmdhost' ),
		'game'      => __( 'Game panel', 'rmdhost' ),
		'shield'    => __( 'DDoS shield', 'rmdhost' ),
	);
}

/**
 * Section heading block.
 *
 * @param array $args eyebrow, title, text, center, class.
 * @return string
 */
function rmdhost_section_head( $args ) {
	$args = wp_parse_args(
		$args,
		array(
			'eyebrow' => '',
			'title'   => '',
			'text'    => '',
			'center'  => true,
			'class'   => '',
		)
	);
	if ( ! $args['title'] && ! $args['text'] ) {
		return '';
	}
	$out  = '<div class="s-head ' . ( $args['center'] ? 'center ' : '' ) . esc_attr( $args['class'] ) . '" data-reveal>';
	$out .= $args['eyebrow'] ? '<p class="eyebrow">' . esc_html( $args['eyebrow'] ) . '</p>' : '';
	$out .= $args['title'] ? '<h2 class="h2">' . rmdhost_kses_inline( $args['title'] ) . '</h2>' : '';
	$out .= $args['text'] ? '<p class="lead">' . rmdhost_kses_inline( $args['text'] ) . '</p>' : '';
	return $out . '</div>';
}

/**
 * Check-mark list.
 *
 * @param string[] $items Items.
 * @param string   $css_class Extra class.
 * @return string
 */
function rmdhost_checklist( $items, $css_class = '' ) {
	$items = array_filter( (array) $items );
	if ( ! $items ) {
		return '';
	}
	$out = '<ul class="checks ' . esc_attr( $css_class ) . '">';
	foreach ( $items as $item ) {
		$out .= '<li>' . rmdhost_icon( 'check' ) . esc_html( $item ) . '</li>';
	}
	return $out . '</ul>';
}

/**
 * Five-star rating.
 *
 * @param int $n Stars on.
 * @return string
 */
function rmdhost_stars( $n ) {
	$n = max( 0, min( 5, (int) $n ) );
	/* translators: %d: number of stars */
	$out = '<span class="stars" aria-label="' . esc_attr( sprintf( __( '%d out of 5 stars', 'rmdhost' ), $n ) ) . '">';
	for ( $i = 0; $i < 5; $i++ ) {
		$out .= '<i class="' . ( $i < $n ? 'on' : '' ) . '">★</i>';
	}
	return $out . '</span>';
}

/**
 * Plan box: CPU | RAM | Storage | Bandwidth | Location | DDoS | Price | More details | Order now.
 *
 * @param array $plan    Normalised plan (see RMDHost\Data::normalize_plan()).
 * @param array $args    featured, product.
 * @return string
 */
function rmdhost_plan_card( $plan, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'featured' => ! empty( $plan['badge'] ),
			'product'  => '',
		)
	);
	ob_start();
	get_template_part( 'template-parts/components/plan-card', null, array_merge( $args, array( 'plan' => $plan ) ) );
	return ob_get_clean();
}

/**
 * FAQ accordion.
 *
 * @param array $faqs List of array( 'q' => ..., 'a' => ... ).
 * @return string
 */
function rmdhost_faq_list( $faqs ) {
	ob_start();
	get_template_part( 'template-parts/components/faq-list', null, array( 'faqs' => $faqs ) );
	return ob_get_clean();
}

/**
 * Breadcrumb trail for page heroes.
 *
 * @param array $trail List of array( label, url ); last item is current.
 * @return string
 */
function rmdhost_breadcrumbs( $trail ) {
	if ( count( $trail ) < 2 ) {
		return '';
	}
	$out  = '<nav class="crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'rmdhost' ) . '">';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => $crumb ) {
		if ( $i < $last ) {
			$out .= '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a><span>/</span>';
		} else {
			$out .= '<span aria-current="page">' . esc_html( $crumb[0] ) . '</span>';
		}
	}
	return $out . '</nav>';
}

/**
 * Default breadcrumb trail for the current request.
 *
 * @return array
 */
function rmdhost_current_trail() {
	$trail = array( array( __( 'Home', 'rmdhost' ), home_url( '/' ) ) );
	if ( is_singular( 'post' ) ) {
		$blog = (int) get_option( 'page_for_posts' );
		if ( $blog ) {
			$trail[] = array( get_the_title( $blog ), get_permalink( $blog ) );
		}
		$trail[] = array( get_the_title(), '' );
	} elseif ( is_singular() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
			$trail[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
		$trail[] = array( get_the_title(), '' );
	} elseif ( is_home() ) {
		$trail[] = array( single_post_title( '', false ) ? single_post_title( '', false ) : __( 'Blog', 'rmdhost' ), '' );
	} elseif ( is_search() ) {
		/* translators: %s: search query */
		$trail[] = array( sprintf( __( 'Search: %s', 'rmdhost' ), get_search_query() ), '' );
	} elseif ( is_archive() ) {
		$trail[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	}
	/**
	 * Filters the breadcrumb trail shown in page heroes.
	 *
	 * @param array $trail List of array( label, url ).
	 */
	return (array) apply_filters( 'rmdhost/breadcrumbs', $trail );
}

/**
 * Dark page hero used by inner pages.
 *
 * @param array $args eyebrow, title, lede, actions (HTML), visual (HTML), crumbs, small.
 */
function rmdhost_page_hero( $args = array() ) {
	get_template_part( 'template-parts/layout/page-hero', null, $args );
}

/**
 * Hero for the current page, using the page's hero fields with template defaults.
 *
 * @param array $defaults eyebrow, title, lede, actions, visual, small.
 */
function rmdhost_singular_hero( $defaults = array() ) {
	$id = get_queried_object_id();
	if ( $id && get_post_meta( $id, '_rmd_hide_hero', true ) ) {
		return;
	}
	$args    = wp_parse_args( $defaults, array( 'title' => single_post_title( '', false ) ) );
	$title   = $id ? get_post_meta( $id, '_rmd_hero_title', true ) : '';
	$eyebrow = $id ? get_post_meta( $id, '_rmd_hero_eyebrow', true ) : '';
	if ( $title ) {
		$args['title'] = $title;
	}
	$visual = $id ? get_post_meta( $id, '_rmd_hero_visual', true ) : '';
	if ( $visual && array_key_exists( $visual, rmdhost_scene_choices() ) ) {
		$args['visual'] = rmdhost_scene( $visual );
	}
	$lede = $id ? get_post_meta( $id, '_rmd_hero_lede', true ) : '';
	if ( $eyebrow ) {
		$args['eyebrow'] = $eyebrow;
	}
	if ( $lede ) {
		$args['lede'] = $lede;
	} elseif ( empty( $args['lede'] ) && $id && has_excerpt( $id ) ) {
		$args['lede'] = get_the_excerpt( $id );
	}
	rmdhost_page_hero( $args );
}

/**
 * Container classes for regular page content (wider for shop pages).
 *
 * @return string
 */
function rmdhost_content_container_class() {
	/**
	 * Filters the page content container class.
	 *
	 * @param string $css_class Class list.
	 */
	return (string) apply_filters( 'rmdhost/content_container_class', 'container narrow prose entry-content' );
}

/**
 * Editor content of the current page wrapped in a prose container (skipped when empty).
 *
 * @param string $css_class Section class.
 */
function rmdhost_page_content( $css_class = 'section' ) {
	if ( ! trim( (string) get_post_field( 'post_content', get_the_ID() ) ) ) {
		return;
	}
	echo '<section class="' . esc_attr( $css_class ) . '"><div class="container narrow prose entry-content">';
	the_content();
	wp_link_pages(
		array(
			'before' => '<nav class="page-links">',
			'after'  => '</nav>',
		)
	);
	echo '</div></section>';
}

/**
 * Whether the current singular post is built with Elementor.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function rmdhost_is_elementor( $post_id = 0 ) {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}
	$post_id  = $post_id ? $post_id : get_the_ID();
	$document = \Elementor\Plugin::$instance->documents->get( $post_id );
	return $document && $document->is_built_with_elementor();
}

/**
 * Render a homepage-style section by name. Used by front-page.php,
 * Elementor widgets and the block editor (via the companion plugin).
 *
 * @param string $name Section slug (template-parts/sections/{name}.php).
 * @param array  $args Overrides for the section defaults.
 */
function rmdhost_section( $name, $args = array() ) {
	$name     = sanitize_key( $name );
	$defaults = rmdhost_section_defaults( $name );
	get_template_part( 'template-parts/sections/' . $name, null, wp_parse_args( $args, $defaults ) );
}

/**
 * Pick text for a light/dark theme toggle button.
 *
 * @return string
 */
function rmdhost_theme_toggle() {
	return '<button class="icon-btn theme-toggle" type="button" data-theme-toggle aria-label="' . esc_attr__( 'Toggle dark mode', 'rmdhost' ) . '">' . rmdhost_icon( 'moon', 'only-light' ) . rmdhost_icon( 'sun', 'only-dark' ) . '</button>';
}

/**
 * Currency select (GBP/USD).
 *
 * @param string $id Element ID.
 * @return string
 */
function rmdhost_currency_select( $id ) {
	$out  = '<label class="currency" for="' . esc_attr( $id ) . '"><span class="sr-only">' . esc_html__( 'Currency', 'rmdhost' ) . '</span><select id="' . esc_attr( $id ) . '" data-currency-select>';
	$out .= '<option value="GBP">£ GBP</option><option value="USD">$ USD</option>';
	return $out . '</select>' . rmdhost_icon( 'chevron', 'currency-caret' ) . '</label>';
}

/**
 * Map projection helper.
 *
 * @param float $lon Longitude.
 * @param float $lat Latitude.
 * @return float[]
 */
function rmdhost_lonlat( $lon, $lat ) {
	$m = rmdhost_map_data();
	return array(
		( ( $lon + 180 ) / 360 ) * $m['map']['w'],
		( ( $m['map']['lat_top'] - $lat ) / ( $m['map']['lat_top'] - $m['map']['lat_bottom'] ) ) * $m['map']['h'],
	);
}

/**
 * Generated dotted-map data.
 *
 * @return array
 */
function rmdhost_map_data() {
	static $map = null;
	if ( null === $map ) {
		$map = require RMDHOST_DIR . '/inc/generated/map.php';
	}
	return $map;
}

/**
 * Square, numbered map marker.
 *
 * @param array $loc   Location.
 * @param int   $i     Index.
 * @param float $size  Marker size.
 * @param bool  $label Show label.
 * @return string
 */
function rmdhost_map_marker( $loc, $i, $size, $label ) {
	list( $x, $y ) = rmdhost_lonlat( (float) $loc['lon'], (float) $loc['lat'] );
	$h             = $size / 2;
	$out           = sprintf( '<g class="mk" data-loc="%1$d" transform="translate(%2$.2f %3$.2f)" style="--d:%4$ss"><rect class="mk-pulse" x="%5$s" y="%5$s" width="%6$s" height="%6$s"/><rect class="mk-dot" x="%5$s" y="%5$s" width="%6$s" height="%6$s"/>', $i, $x, $y, esc_attr( $i * 0.35 ), -$h, $size );
	if ( $label && ! empty( $loc['label'] ) ) {
		$lb   = $loc['label'];
		$out .= sprintf( '<text class="mk-label" x="%1$s" y="%2$s" text-anchor="%3$s"><tspan class="mk-n">%4$s</tspan> %5$s</text>', esc_attr( $lb[1] ), esc_attr( $lb[2] ), esc_attr( $lb[3] ), str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ), esc_html( $loc['city'] ) );
	}
	return $out . '</g>';
}

/**
 * World map + Europe zoom SVGs.
 *
 * @param array $locations Locations.
 * @return string[] array( world, europe ).
 */
function rmdhost_maps( $locations ) {
	$m                 = rmdhost_map_data();
	$eu                = $m['europe'];
	list( $ex1, $ey1 ) = rmdhost_lonlat( $eu['lon_min'], $eu['lat_max'] );
	list( $ex2, $ey2 ) = rmdhost_lonlat( $eu['lon_max'], $eu['lat_min'] );

	$eu_count = 0;
	$markers  = '';
	$eu_marks = '';
	foreach ( array_values( $locations ) as $i => $loc ) {
		$is_eu     = isset( $loc['region'] ) && 'Europe' === $loc['region'];
		$eu_count += $is_eu ? 1 : 0;
		$markers  .= rmdhost_map_marker( $loc, $i, 10, ! $is_eu );
		$eu_marks .= $is_eu ? rmdhost_map_marker( $loc, $i, 2.6, true ) : '';
	}
	$world = sprintf(
		'<svg class="worldmap" viewBox="0 0 %1$d %2$d" role="img" aria-label="%3$s"><path class="land" d="%4$s"/><rect class="eu-box" x="%5$.1f" y="%6$.1f" width="%7$.1f" height="%8$.1f"/><text class="eu-box-label" x="%9$.1f" y="%10$.1f">%11$s</text>%12$s</svg>',
		$m['map']['w'],
		$m['map']['h'],
		esc_attr__( 'World map showing data-centre locations', 'rmdhost' ),
		esc_attr( $m['world'] ),
		$ex1,
		$ey1,
		$ex2 - $ex1,
		$ey2 - $ey1,
		$ex1 + 2,
		$ey1 - 5,
		/* translators: %d: number of European data centres */
		esc_html( sprintf( __( 'EUROPE · %d SITES', 'rmdhost' ), $eu_count ) ),
		$markers
	);
	$europe = sprintf(
		'<svg class="eumap" viewBox="%1$.2f %2$.2f %3$.2f %4$.2f" role="img" aria-label="%5$s"><path class="land" d="%6$s"/>%7$s</svg>',
		$ex1,
		$ey1,
		$ex2 - $ex1,
		$ey2 - $ey1,
		esc_attr__( 'Map of data centres in Europe', 'rmdhost' ),
		esc_attr( $m['eu'] ),
		$eu_marks
	);
	return array( $world, $europe );
}

/**
 * Pagination using theme styling.
 */
function rmdhost_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => rmdhost_icon( 'chevronLeft' ) . '<span class="sr-only">' . esc_html__( 'Previous', 'rmdhost' ) . '</span>',
			'next_text' => '<span class="sr-only">' . esc_html__( 'Next', 'rmdhost' ) . '</span>' . rmdhost_icon( 'chevronRight' ),
		)
	);
}
