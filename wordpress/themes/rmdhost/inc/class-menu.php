<?php
/**
 * Mega menu built from a standard WordPress menu.
 *
 * Structure in Appearance → Menus (location "Primary"):
 *   Top-level item                → nav link, or mega-menu trigger if it has children
 *     └ Child item (column title) → column heading (use a "#" custom link)
 *         └ Grandchild item       → link with description, icon and optional tag
 * Top-level items can also hold a promo panel (fields below the item).
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Menu builder.
 */
class Menu {

	const FIELDS = array( 'icon', 'tag', 'promo_visual', 'promo_eyebrow', 'promo_title', 'promo_text', 'promo_url', 'promo_cta' );

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, 'fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, 'save' ), 10, 2 );
	}

	/**
	 * Header navigation as a normalised tree (same shape as the default nav).
	 *
	 * @return array
	 */
	public static function primary() {
		$locations = get_nav_menu_locations();
		if ( empty( $locations['primary'] ) ) {
			return Data::nav();
		}
		$items = wp_get_nav_menu_items( $locations['primary'], array( 'update_post_term_cache' => false ) );
		if ( ! $items ) {
			return Data::nav();
		}
		$by_parent = array();
		foreach ( $items as $item ) {
			$by_parent[ (int) $item->menu_item_parent ][] = $item;
		}
		$tree = array();
		foreach ( $by_parent[0] ?? array() as $top ) {
			$node = array(
				'label' => $top->title,
				'href'  => $top->url,
			);
			if ( ! empty( $by_parent[ $top->ID ] ) ) {
				$node['mega'] = array();
				foreach ( $by_parent[ $top->ID ] as $col ) {
					$column = array(
						'title' => $col->title,
						'items' => array(),
					);
					foreach ( $by_parent[ $col->ID ] ?? array() as $link ) {
						$column['items'][] = array(
							'label' => $link->title,
							'href'  => $link->url,
							'desc'  => $link->description,
							'icon'  => get_post_meta( $link->ID, '_rmd_icon', true ) ? get_post_meta( $link->ID, '_rmd_icon', true ) : 'server',
							'tag'   => get_post_meta( $link->ID, '_rmd_tag', true ),
						);
					}
					$node['mega'][] = $column;
				}
				if ( get_post_meta( $top->ID, '_rmd_promo_title', true ) ) {
					$node['promo'] = array(
						'visual'  => get_post_meta( $top->ID, '_rmd_promo_visual', true ),
						'eyebrow' => get_post_meta( $top->ID, '_rmd_promo_eyebrow', true ),
						'title'   => get_post_meta( $top->ID, '_rmd_promo_title', true ),
						'text'    => get_post_meta( $top->ID, '_rmd_promo_text', true ),
						'href'    => get_post_meta( $top->ID, '_rmd_promo_url', true ),
						'cta'     => get_post_meta( $top->ID, '_rmd_promo_cta', true ),
					);
				}
			}
			$tree[] = $node;
		}
		return $tree;
	}

	/**
	 * Footer columns from menus "Footer column 1–5" (title = menu name).
	 *
	 * @return array
	 */
	public static function footer_columns() {
		$locations = get_nav_menu_locations();
		$cols      = array();
		for ( $i = 1; $i <= 5; $i++ ) {
			if ( empty( $locations[ "footer-$i" ] ) ) {
				continue;
			}
			$menu  = wp_get_nav_menu_object( $locations[ "footer-$i" ] );
			$items = wp_get_nav_menu_items( $locations[ "footer-$i" ] );
			if ( ! $menu || ! $items ) {
				continue;
			}
			$links = array();
			foreach ( $items as $item ) {
				$links[] = array(
					'label' => $item->title,
					'href'  => $item->url,
				);
			}
			$cols[] = array(
				'title' => $menu->name,
				'links' => $links,
			);
		}
		return $cols ? $cols : Data::footer();
	}

	/**
	 * Small CSS visual for promo panels.
	 *
	 * @param string $kind rack|map|terminal.
	 * @return string
	 */
	public static function promo_visual( $kind ) {
		$out = '';
		if ( 'rack' === $kind ) {
			$out = '<span class="mp-vis mp-rack" aria-hidden="true">';
			for ( $i = 0; $i < 4; $i++ ) {
				$out .= '<i style="--d:' . esc_attr( $i * 0.3 ) . 's"><b></b><b></b></i>';
			}
			$out .= '</span>';
		} elseif ( 'map' === $kind ) {
			$out = '<span class="mp-vis mp-map" aria-hidden="true">';
			foreach ( array( array( 22, 38 ), array( 23, 34 ), array( 32, 36 ), array( 40, 46 ), array( 58, 24 ), array( -62, 58 ) ) as $i => $p ) {
				$out .= '<i style="left:' . esc_attr( $p[0] < 0 ? 12 : 30 + $p[0] ) . '%;top:' . esc_attr( $p[1] ) . '%;--d:' . esc_attr( $i * 0.3 ) . 's"></i>';
			}
			$out .= '</span>';
		} elseif ( 'terminal' === $kind ) {
			$out = '<span class="mp-vis mp-term" aria-hidden="true"><b>$ ssh root@srv-482</b><b>$ ufw allow 22/tcp</b><b class="g">Rules updated ✓</b></span>';
		}
		return $out;
	}

	/**
	 * Extra fields on menu items (Appearance → Menus).
	 *
	 * @param int      $item_id Item ID.
	 * @param \WP_Post $item    Item.
	 * @param int      $depth   Depth.
	 * @param array    $args    Args.
	 */
	public static function fields( $item_id, $item, $depth, $args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- hook signature.
		$get = static function ( $key ) use ( $item_id ) {
			return get_post_meta( $item_id, '_rmd_' . $key, true );
		};
		echo '<div class="rmd-menu-fields" style="clear:both">';
		if ( (int) $depth >= 2 ) {
			printf(
				'<p class="description description-thin"><label>%1$s<br><select name="rmd_icon[%2$d]">%3$s</select></label></p>',
				esc_html__( 'Icon (mega menu)', 'rmdhost' ),
				(int) $item_id,
				self::options( rmdhost_icon_names(), $get( 'icon' ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in options().
			);
			printf(
				'<p class="description description-thin"><label>%1$s<br><input type="text" class="widefat" name="rmd_tag[%2$d]" value="%3$s"></label></p>',
				esc_html__( 'Tag (e.g. New)', 'rmdhost' ),
				(int) $item_id,
				esc_attr( $get( 'tag' ) )
			);
		}
		if ( 0 === (int) $depth ) {
			echo '<p class="description description-wide"><strong>' . esc_html__( 'Mega-menu promo panel (optional)', 'rmdhost' ) . '</strong></p>';
			printf(
				'<p class="description description-thin"><label>%1$s<br><select name="rmd_promo_visual[%2$d]">%3$s</select></label></p>',
				esc_html__( 'Visual', 'rmdhost' ),
				(int) $item_id,
				self::options( array( '', 'rack', 'map', 'terminal' ), $get( 'promo_visual' ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in options().
			);
			foreach ( array(
				'promo_eyebrow' => __( 'Eyebrow', 'rmdhost' ),
				'promo_title'   => __( 'Title', 'rmdhost' ),
				'promo_text'    => __( 'Text', 'rmdhost' ),
				'promo_url'     => __( 'Link URL', 'rmdhost' ),
				'promo_cta'     => __( 'Link label', 'rmdhost' ),
			) as $key => $label ) {
				printf(
					'<p class="description description-wide"><label>%1$s<br><input type="text" class="widefat" name="rmd_%2$s[%3$d]" value="%4$s"></label></p>',
					esc_html( $label ),
					esc_attr( $key ),
					(int) $item_id,
					esc_attr( $get( $key ) )
				);
			}
		}
		echo '</div>';
	}

	/**
	 * Option tags.
	 *
	 * @param array  $values  Values.
	 * @param string $current Selected.
	 * @return string
	 */
	private static function options( $values, $current ) {
		$out = '';
		foreach ( $values as $v ) {
			$out .= '<option value="' . esc_attr( $v ) . '"' . selected( $current, $v, false ) . '>' . esc_html( $v ? $v : '—' ) . '</option>';
		}
		return $out;
	}

	/**
	 * Save menu item fields.
	 *
	 * @param int $menu_id Menu ID.
	 * @param int $item_id Item ID.
	 */
	public static function save( $menu_id, $item_id ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		// Core verifies this nonce before saving menus; re-check because we read $_POST directly.
		if ( ! isset( $_POST['update-nav-menu-nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['update-nav-menu-nonce'] ) ), 'update-nav_menu' ) ) {
			return;
		}
		foreach ( self::FIELDS as $field ) {
			$key = 'rmd_' . $field;
			if ( ! isset( $_POST[ $key ][ $item_id ] ) ) {
				continue;
			}
			$raw   = wp_unslash( $_POST[ $key ][ $item_id ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitized below.
			$value = 'promo_url' === $field ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
			update_post_meta( $item_id, '_rmd_' . $field, $value );
		}
	}
}
