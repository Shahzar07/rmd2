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
 * @param int   $open Index of the item open on load (-1 for none).
 * @return string
 */
function rmdhost_faq_list( $faqs, $open = -1 ) {
	ob_start();
	get_template_part(
		'template-parts/components/faq-list',
		null,
		array(
			'faqs' => $faqs,
			'open' => $open,
		)
	);
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
 * Number formatting for SVG attributes (max 2 decimals, no trailing zeros).
 *
 * @param float $v Value.
 * @return string
 */
function rmdhost_svg_num( $v ) {
	$s = rtrim( rtrim( number_format( (float) $v, 2, '.', '' ), '0' ), '.' );
	return '-0' === $s ? '0' : $s;
}

/**
 * Formatted coordinates, e.g. "52.92°N 1.48°W".
 *
 * @param array $loc Location with lat/lon.
 * @return string
 */
function rmdhost_coords( $loc ) {
	$lat = (float) $loc['lat'];
	$lon = (float) $loc['lon'];
	return sprintf( '%s°%s %s°%s', number_format( abs( $lat ), 2 ), $lat >= 0 ? 'N' : 'S', number_format( abs( $lon ), 2 ), $lon >= 0 ? 'E' : 'W' );
}

/**
 * Links between sites: a minimum spanning tree over lon/lat, so each site
 * joins its nearest neighbour whatever the location list holds.
 *
 * @param array $locs Locations.
 * @return array List of array( a, b ) index pairs.
 */
function rmdhost_map_links( $locs ) {
	$locs = array_values( $locs );
	if ( count( $locs ) < 2 ) {
		return array();
	}
	$in    = array( 0 );
	$links = array();
	$total = count( $locs );
	for ( $n = 1; $n < $total; $n++ ) {
		$best = null;
		foreach ( $in as $a ) {
			foreach ( $locs as $b => $l ) {
				if ( in_array( $b, $in, true ) ) {
					continue;
				}
				$d = hypot( (float) $locs[ $a ]['lon'] - (float) $l['lon'], (float) $locs[ $a ]['lat'] - (float) $l['lat'] );
				if ( null === $best || $d < $best[2] ) {
					$best = array( $a, $b, $d );
				}
			}
		}
		$in[]    = $best[1];
		$links[] = array( $best[0], $best[1] );
	}
	return $links;
}

/**
 * Link paths (faint line + travelling packet) bowed upwards.
 *
 * @param array $links Index pairs into $locs.
 * @param array $locs  Locations.
 * @return string
 */
function rmdhost_map_link_paths( $links, $locs ) {
	$out = '';
	foreach ( $links as $link ) {
		list( $a, $b )   = $link;
		list( $x1, $y1 ) = rmdhost_lonlat( (float) $locs[ $a ]['lon'], (float) $locs[ $a ]['lat'] );
		list( $x2, $y2 ) = rmdhost_lonlat( (float) $locs[ $b ]['lon'], (float) $locs[ $b ]['lat'] );
		$nx              = -( $y2 - $y1 );
		$ny              = $x2 - $x1;
		if ( $ny > 0 || ( 0.0 === (float) $ny && $nx > 0 ) ) {
			$nx = -$nx;
			$ny = -$ny;
		}
		$d    = 'M' . rmdhost_svg_num( $x1 ) . ' ' . rmdhost_svg_num( $y1 ) . 'Q' . rmdhost_svg_num( ( $x1 + $x2 ) / 2 + $nx * 0.2 ) . ' ' . rmdhost_svg_num( ( $y1 + $y2 ) / 2 + $ny * 0.2 ) . ' ' . rmdhost_svg_num( $x2 ) . ' ' . rmdhost_svg_num( $y2 );
		$out .= sprintf( '<path class="ln" d="%1$s"/><path class="ln-pk" d="%1$s" pathLength="100" style="--d:%2$ss"/>', esc_attr( $d ), rmdhost_svg_num( $a * 0.4 + $b * 0.25 ) );
	}
	return $out;
}

/**
 * Label chip with an optional leader line. The mono font advances 0.6em per
 * character, so the chip width is exact.
 *
 * @param string $text   Label.
 * @param float  $ox     Chip x offset.
 * @param float  $oy     Chip y offset.
 * @param string $anchor start|end.
 * @param float  $fs     Font size in map units.
 * @param bool   $lead   Draw a leader line from the origin.
 * @return string
 */
function rmdhost_map_chip( $text, $ox, $oy, $anchor, $fs, $lead = true ) {
	$w = mb_strlen( $text ) * $fs * 0.6 + $fs * 1.2;
	$h = $fs * 1.8;
	$x = 'end' === $anchor ? $ox - $w : $ox;
	return '<g class="mk-tag">'
		. ( $lead ? sprintf( '<line class="mk-lead" x1="0" y1="0" x2="%s" y2="%s"/>', rmdhost_svg_num( $ox ), rmdhost_svg_num( $oy ) ) : '' )
		. sprintf( '<rect class="mk-chip" x="%s" y="%s" width="%s" height="%s"/>', rmdhost_svg_num( $x ), rmdhost_svg_num( $oy - $h / 2 ), rmdhost_svg_num( $w ), rmdhost_svg_num( $h ) )
		. sprintf( '<text x="%s" y="%s" font-size="%s">%s</text>', rmdhost_svg_num( $x + $fs * 0.6 ), rmdhost_svg_num( $oy + $fs * 0.36 ), rmdhost_svg_num( $fs ), esc_html( $text ) )
		. '</g>';
}

/**
 * Square, numbered map marker with glow, pulse and optional label chip.
 *
 * @param array $loc  Location.
 * @param int   $i    Index.
 * @param array $opts size, glow, fs, label.
 * @return string
 */
function rmdhost_map_marker( $loc, $i, $opts ) {
	list( $x, $y ) = rmdhost_lonlat( (float) $loc['lon'], (float) $loc['lat'] );
	$s             = $opts['size'];
	$h             = rmdhost_svg_num( -$s / 2 );
	$tag           = '';
	if ( $opts['label'] && ! empty( $loc['label'] ) && count( $loc['label'] ) >= 4 ) {
		$lb  = $loc['label'];
		$tag = rmdhost_map_chip( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) . ' ' . mb_strtoupper( $loc['city'] ), (float) $lb[1], (float) $lb[2], $lb[3], $opts['fs'] );
	}
	return sprintf(
		'<g class="mk" data-loc="%1$d" transform="translate(%2$s %3$s)" style="--d:%4$ss"><circle class="mk-glow" r="%5$s"/>%6$s<rect class="mk-pulse" x="%7$s" y="%7$s" width="%8$s" height="%8$s"/><rect class="mk-dot" x="%7$s" y="%7$s" width="%8$s" height="%8$s"/></g>',
		$i,
		rmdhost_svg_num( $x ),
		rmdhost_svg_num( $y ),
		rmdhost_svg_num( $i * 0.35 ),
		rmdhost_svg_num( $opts['glow'] ),
		$tag,
		$h,
		rmdhost_svg_num( $s )
	);
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
	$locations         = array_values( $locations );
	list( $ex1, $ey1 ) = rmdhost_lonlat( $eu['lon_min'], $eu['lat_max'] );
	list( $ex2, $ey2 ) = rmdhost_lonlat( $eu['lon_max'], $eu['lat_min'] );
	$land              = function ( $g, $d ) {
		return sprintf( '<path class="land" transform="translate(%s %s) scale(%s)" d="%s"/>', esc_attr( $g['x'] ), esc_attr( $g['y'] ), esc_attr( $g['s'] ), esc_attr( $d ) );
	};

	$markers  = '';
	$eu_marks = '';
	$eu_locs  = array();
	foreach ( $locations as $i => $loc ) {
		$is_eu    = isset( $loc['region'] ) && 'Europe' === $loc['region'];
		$markers .= rmdhost_map_marker(
			$loc,
			$i,
			array(
				'size'  => 9,
				'glow'  => 34,
				'fs'    => 17,
				'label' => ! $is_eu,
			)
		);
		if ( $is_eu ) {
			$eu_locs[ $i ] = $loc;
			$eu_marks     .= rmdhost_map_marker(
				$loc,
				$i,
				array(
					'size'  => 2.4,
					'glow'  => 9,
					'fs'    => 3.1,
					'label' => true,
				)
			);
		}
	}
	$eu_index = array_keys( $eu_locs );
	$eu_links = array();
	foreach ( rmdhost_map_links( $eu_locs ) as $link ) {
		$eu_links[] = array( $eu_index[ $link[0] ], $eu_index[ $link[1] ] );
	}

	$grat = '';
	for ( $lon = -150; $lon < 180; $lon += 30 ) {
		$grat .= 'M' . rmdhost_svg_num( rmdhost_lonlat( $lon, 0 )[0] ) . ' 0V' . $m['map']['h'];
	}
	for ( $lat = -40; $lat <= 60; $lat += 20 ) {
		$grat .= 'M0 ' . rmdhost_svg_num( rmdhost_lonlat( 0, $lat )[1] ) . 'H' . $m['map']['w'];
	}
	$eq = rmdhost_svg_num( rmdhost_lonlat( 0, 0 )[1] );

	$world = sprintf(
		'<svg class="worldmap" viewBox="0 0 %1$d %2$d" role="img" aria-label="%3$s"><defs><radialGradient id="mkGlow"><stop offset="0" stop-color="#3ddc84" stop-opacity=".45"/><stop offset="1" stop-color="#3ddc84" stop-opacity="0"/></radialGradient></defs><path class="grat" d="%4$s"/><path class="equator" d="M0 %5$sH%1$d"/>%6$s<g class="eu-zone"><rect class="eu-box" x="%7$s" y="%8$s" width="%9$s" height="%10$s"/>%11$s</g><g class="links">%12$s</g>%13$s</svg>',
		$m['map']['w'],
		$m['map']['h'],
		esc_attr__( 'World map showing data-centre locations', 'rmdhost' ),
		$grat,
		$eq,
		$land( $m['world_grid'], $m['world'] ),
		rmdhost_svg_num( $ex1 ),
		rmdhost_svg_num( $ey1 ),
		rmdhost_svg_num( $ex2 - $ex1 ),
		rmdhost_svg_num( $ey2 - $ey1 ),
		/* translators: %d: number of European data centres */
		rmdhost_map_chip( sprintf( __( 'EUROPE · %d SITES', 'rmdhost' ), count( $eu_locs ) ), $ex1 - 1, $ey1 - 15, 'start', 13, false ),
		rmdhost_map_link_paths( rmdhost_map_links( $locations ), $locations ),
		$markers
	);
	$europe = sprintf(
		'<svg class="eumap" viewBox="%1$s %2$s %3$s %4$s" role="img" aria-label="%5$s">%6$s<g class="links">%7$s</g>%8$s</svg>',
		rmdhost_svg_num( $ex1 ),
		rmdhost_svg_num( $ey1 ),
		rmdhost_svg_num( $ex2 - $ex1 ),
		rmdhost_svg_num( $ey2 - $ey1 ),
		esc_attr__( 'Map of data centres in Europe', 'rmdhost' ),
		$land( $m['eu_grid'], $m['eu'] ),
		rmdhost_map_link_paths( $eu_links, $locations ),
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
