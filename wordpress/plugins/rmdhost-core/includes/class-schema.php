<?php
/**
 * Editable fields per section, shared by the Elementor widgets and the block.
 *
 * Field types: text, textarea, url, number, switch, image, group (product group
 * select), groups (multi-select), faq_group, lines (one item per line),
 * select (with options), repeater (with sub-fields).
 *
 * @package RMDHost_Core
 */

namespace RMDHost_Core;

defined( 'ABSPATH' ) || exit;

/**
 * Section schema.
 */
class Schema {

	/**
	 * Field list for a section.
	 *
	 * @param string $section Section slug.
	 * @return array key => array( type, label, extra… )
	 */
	public static function fields( $section ) {
		$scenes  = array( 'dashboard', 'terminal', 'windows', 'cloud', 'storage', 'ai', 'mac', 'rack', 'game', 'shield' );
		$visuals = array( 'linux', 'windows', 'rack', 'storage' );
		$icon    = array(
			'select',
			__( 'Icon', 'rmdhost-core' ),
			'options' => self::icons(),
		);
		$t       = array( 'text', __( 'Title', 'rmdhost-core' ) );
		$x       = array( 'textarea', __( 'Text', 'rmdhost-core' ) );
		$u       = array( 'url', __( 'Link', 'rmdhost-core' ) );
		$map     = array(
			'hero'         => array(
				'eyebrow'        => array( 'text', __( 'Eyebrow', 'rmdhost-core' ) ),
				'headline'       => array( 'textarea', __( 'Headline (HTML <br> and <span class="grad-text"> allowed)', 'rmdhost-core' ) ),
				'text'           => $x,
				'offer_label'    => array( 'text', __( 'Offer label', 'rmdhost-core' ) ),
				'offer_price'    => array( 'text', __( 'Offer price (GBP)', 'rmdhost-core' ) ),
				'offer_suffix'   => array( 'text', __( 'Offer suffix', 'rmdhost-core' ) ),
				'offer_note'     => array( 'text', __( 'Offer note', 'rmdhost-core' ) ),
				'cta1_label'     => array( 'text', __( 'Primary button', 'rmdhost-core' ) ),
				'cta1_url'       => array( 'url', __( 'Primary button link', 'rmdhost-core' ) ),
				'cta2_label'     => array( 'text', __( 'Secondary button', 'rmdhost-core' ) ),
				'cta2_url'       => array( 'url', __( 'Secondary button link', 'rmdhost-core' ) ),
				'banner'         => array( 'switch', __( 'Show offer / review banner', 'rmdhost-core' ) ),
				'banner_tag'     => array( 'text', __( 'Banner tag', 'rmdhost-core' ) ),
				'banner_text'    => array( 'text', __( 'Banner offer text', 'rmdhost-core' ) ),
				'banner_cta'     => array( 'text', __( 'Banner link label', 'rmdhost-core' ) ),
				'banner_cta_url' => array( 'url', __( 'Banner link', 'rmdhost-core' ) ),
				'rating_label'   => array( 'text', __( 'Review source label', 'rmdhost-core' ) ),
				'rating_score'   => array( 'text', __( 'Review score (only a verified figure)', 'rmdhost-core' ) ),
				'rating_count'   => array( 'text', __( 'Review count (only a verified figure)', 'rmdhost-core' ) ),
				'rating_url'     => array( 'url', __( 'Review page link', 'rmdhost-core' ) ),
			),
			'promo'        => array(
				'big_tag'    => array( 'text', __( 'Large card tag', 'rmdhost-core' ) ),
				'big_title'  => array( 'text', __( 'Large card title', 'rmdhost-core' ) ),
				'big_text'   => array( 'textarea', __( 'Large card text ({price:vps} inserts the lowest price of a group)', 'rmdhost-core' ) ),
				'big_button' => array( 'text', __( 'Large card button', 'rmdhost-core' ) ),
				'big_url'    => array( 'url', __( 'Large card link', 'rmdhost-core' ) ),
				'big_image'  => array( 'image', __( 'Large card photo', 'rmdhost-core' ) ),
				'cards'      => array(
					'repeater',
					__( 'Small cards', 'rmdhost-core' ),
					'fields' => array(
						'tag'   => array( 'text', __( 'Tag', 'rmdhost-core' ) ),
						'title' => $t,
						'text'  => $x,
						'url'   => $u,
						'icon'  => $icon,
					),
				),
			),
			'finder'       => array(
				'title'   => array( 'textarea', __( 'Title (HTML allowed)', 'rmdhost-core' ) ),
				'note'    => array( 'text', __( 'Note', 'rmdhost-core' ) ),
				'phrases' => array( 'lines', __( 'Typewriter examples (one per line)', 'rmdhost-core' ) ),
			),
			'tools'        => array(
				'title'      => $t,
				'text'       => $x,
				'tabs'       => array(
					'repeater',
					__( 'Tabs', 'rmdhost-core' ),
					'fields' => array(
						'label' => array( 'text', __( 'Tab label', 'rmdhost-core' ) ),
						'scene' => array(
							'select',
							__( 'Visual', 'rmdhost-core' ),
							'options' => array_combine( $scenes, $scenes ),
						),
						'icon'  => $icon,
						'title' => $t,
						'text'  => $x,
						'link'  => array( 'text', __( 'Link label', 'rmdhost-core' ) ),
						'url'   => $u,
					),
				),
				'social'     => array( 'text', __( 'Social proof label', 'rmdhost-core' ) ),
				'social_num' => array( 'text', __( 'Social proof number', 'rmdhost-core' ) ),
				'sub_title'  => array( 'text', __( 'Cards heading', 'rmdhost-core' ) ),
				'cards'      => array(
					'repeater',
					__( 'Cards', 'rmdhost-core' ),
					'fields' => array(
						'icon'  => $icon,
						'title' => $t,
						'text'  => $x,
						'url'   => $u,
					),
				),
			),
			'essentials'   => array(
				'title' => $t,
				'items' => array(
					'repeater',
					__( 'Items', 'rmdhost-core' ),
					'fields' => array(
						'group'  => array( 'group', __( 'Product group (price + link)', 'rmdhost-core' ) ),
						'title'  => $t,
						'text'   => $x,
						'visual' => array(
							'select',
							__( 'Visual', 'rmdhost-core' ),
							'options' => array_combine( $visuals, $visuals ),
						),
						'chip'   => array( 'text', __( 'Status chip', 'rmdhost-core' ) ),
					),
				),
			),
			'alt'          => array(
				'rows' => array(
					'repeater',
					__( 'Rows', 'rmdhost-core' ),
					'fields' => array(
						'title' => $t,
						'text'  => $x,
						'link'  => array( 'text', __( 'Link label', 'rmdhost-core' ) ),
						'url'   => $u,
						'scene' => array(
							'select',
							__( 'Visual', 'rmdhost-core' ),
							'options' => array_combine( $scenes, $scenes ),
						),
					),
				),
			),
			'support'      => array(
				'award'      => array( 'text', __( 'Award text (HTML allowed)', 'rmdhost-core' ) ),
				'title'      => array( 'textarea', __( 'Title (HTML allowed)', 'rmdhost-core' ) ),
				'text'       => $x,
				'button'     => array( 'text', __( 'Button', 'rmdhost-core' ) ),
				'button_url' => array( 'url', __( 'Button link', 'rmdhost-core' ) ),
				'cards'      => array(
					'repeater',
					__( 'Expanding cards', 'rmdhost-core' ),
					'fields' => array(
						'title' => $t,
						'text'  => $x,
						'q'     => array( 'text', __( 'Customer question', 'rmdhost-core' ) ),
						'a'     => array( 'text', __( 'Engineer answer', 'rmdhost-core' ) ),
					),
				),
				'more_title' => array( 'text', __( 'Lower title', 'rmdhost-core' ) ),
				'more_text'  => array( 'textarea', __( 'Lower text', 'rmdhost-core' ) ),
				'more_link'  => array( 'text', __( 'Lower link label', 'rmdhost-core' ) ),
				'more_url'   => array( 'url', __( 'Lower link', 'rmdhost-core' ) ),
				'image'      => array( 'image', __( 'Photo', 'rmdhost-core' ) ),
				'chips'      => array( 'lines', __( 'Floating chips (one per line, max 3)', 'rmdhost-core' ) ),
			),
			'pricing'      => array(
				'title'  => array( 'textarea', __( 'Title (HTML allowed)', 'rmdhost-core' ) ),
				'text'   => $x,
				'assure' => array( 'lines', __( 'Assurances (one per line)', 'rmdhost-core' ) ),
				'groups' => array( 'groups', __( 'Product groups shown as tabs', 'rmdhost-core' ) ),
				'labels' => array( 'lines', __( 'Tab labels (optional, one per line)', 'rmdhost-core' ) ),
				'limit'  => array( 'number', __( 'Plans per tab (0 = all)', 'rmdhost-core' ) ),
				'fine'   => array( 'textarea', __( 'Fine print', 'rmdhost-core' ) ),
				'id'     => array( 'text', __( 'Anchor ID', 'rmdhost-core' ) ),
				'soft'   => array( 'switch', __( 'Grey background', 'rmdhost-core' ) ),
			),
			'automation'   => array(
				'title'          => $t,
				'text'           => $x,
				'flow_title'     => array( 'text', __( 'Card title', 'rmdhost-core' ) ),
				'flow_text'      => array( 'textarea', __( 'Card text', 'rmdhost-core' ) ),
				'links'          => array(
					'repeater',
					__( 'Links', 'rmdhost-core' ),
					'fields' => array(
						'label' => array( 'text', __( 'Label', 'rmdhost-core' ) ),
						'url'   => $u,
					),
				),
				'carousel_title' => array( 'text', __( 'Carousel title', 'rmdhost-core' ) ),
			),
			'locations'    => array(
				'kicker'     => array( 'text', __( 'Kicker', 'rmdhost-core' ) ),
				'title'      => $t,
				'text'       => $x,
				'button'     => array( 'text', __( 'Button', 'rmdhost-core' ) ),
				'button_url' => array( 'url', __( 'Button link', 'rmdhost-core' ) ),
				'status'     => array( 'text', __( 'Status label', 'rmdhost-core' ) ),
				'stats'      => array( 'switch', __( 'Show company stats', 'rmdhost-core' ) ),
				'photo'      => array( 'image', __( 'Photo above the map (optional)', 'rmdhost-core' ) ),
				'photo_alt'  => array( 'text', __( 'Photo alt text', 'rmdhost-core' ) ),
				'caption'    => array( 'text', __( 'Photo caption', 'rmdhost-core' ) ),
				'id'         => array( 'text', __( 'Anchor ID', 'rmdhost-core' ) ),
			),
			'testimonials' => array(
				'title'       => array( 'textarea', __( 'Title (HTML allowed)', 'rmdhost-core' ) ),
				'text'        => $x,
				'button'      => array( 'text', __( 'Button', 'rmdhost-core' ) ),
				'button_url'  => array( 'url', __( 'Button link', 'rmdhost-core' ) ),
				'tiles'       => array(
					'repeater',
					__( 'Media tiles (reviews come from RMDHost → Reviews)', 'rmdhost-core' ),
					'fields' => array(
						'photo' => array( 'image', __( 'Photo', 'rmdhost-core' ) ),
						'video' => array( 'text', __( 'Video (bundled name or MP4 URL – overrides photo)', 'rmdhost-core' ) ),
						'tag'   => array( 'text', __( 'Tag', 'rmdhost-core' ) ),
						'cap'   => array( 'text', __( 'Caption (HTML <br> allowed)', 'rmdhost-core' ) ),
						'url'   => $u,
						'tall'  => array( 'switch', __( 'Tall tile', 'rmdhost-core' ) ),
					),
				),
				'rating_text' => array( 'textarea', __( 'Footer note (HTML links allowed)', 'rmdhost-core' ) ),
			),
			'faq'          => array(
				'title' => $t,
				'group' => array( 'faq_group', __( 'Questions from', 'rmdhost-core' ) ),
				'soft'  => array( 'switch', __( 'Grey background', 'rmdhost-core' ) ),
				'id'    => array( 'text', __( 'Anchor ID', 'rmdhost-core' ) ),
			),
			'cta'          => array(
				'title'      => array( 'textarea', __( 'Title (HTML <br> allowed)', 'rmdhost-core' ) ),
				'text'       => $x,
				'button'     => array( 'text', __( 'Button', 'rmdhost-core' ) ),
				'button_url' => array( 'url', __( 'Button link', 'rmdhost-core' ) ),
				'image'      => array( 'image', __( 'Photo', 'rmdhost-core' ) ),
				'domain'     => array( 'text', __( 'Domain chip (HTML <b> allowed)', 'rmdhost-core' ) ),
				'toast'      => array( 'text', __( 'Toast title', 'rmdhost-core' ) ),
				'toast_note' => array( 'text', __( 'Toast note', 'rmdhost-core' ) ),
				'prompt'     => array( 'text', __( 'Prompt chip', 'rmdhost-core' ) ),
			),
			'product-hero' => array(
				'group'   => array( 'group', __( 'Product group', 'rmdhost-core' ) ),
				'eyebrow' => array( 'text', __( 'Eyebrow (default from product)', 'rmdhost-core' ) ),
				'title'   => array( 'textarea', __( 'Title (default from product)', 'rmdhost-core' ) ),
				'lede'    => array( 'textarea', __( 'Intro (default from product)', 'rmdhost-core' ) ),
				'subnav'  => array( 'switch', __( 'Sticky “on this page” navigation', 'rmdhost-core' ) ),
			),
			'plans'        => array(
				'group' => array( 'group', __( 'Product group', 'rmdhost-core' ) ),
				'title' => $t,
				'text'  => $x,
				'every' => array( 'switch', __( 'Show “every plan includes”', 'rmdhost-core' ) ),
				'fine'  => array( 'textarea', __( 'Fine print', 'rmdhost-core' ) ),
				'id'    => array( 'text', __( 'Anchor ID', 'rmdhost-core' ) ),
			),
			'use-tabs'     => array(
				'group' => array( 'group', __( 'Product group', 'rmdhost-core' ) ),
			),
			'features'     => array(
				'group'          => array( 'group', __( 'Product group (default features)', 'rmdhost-core' ) ),
				'title'          => $t,
				'photo'          => array( 'image', __( 'Photo in the first card', 'rmdhost-core' ) ),
				'banner'         => array( 'image', __( 'Wide photo above the grid (optional)', 'rmdhost-core' ) ),
				'banner_alt'     => array( 'text', __( 'Wide photo alt text', 'rmdhost-core' ) ),
				'banner_caption' => array( 'text', __( 'Wide photo caption', 'rmdhost-core' ) ),
				'features'       => array(
					'repeater',
					__( 'Custom features (replace the group’s)', 'rmdhost-core' ),
					'fields' => array(
						'icon'  => $icon,
						'title' => $t,
						'text'  => $x,
					),
				),
				'soft'           => array( 'switch', __( 'Grey background', 'rmdhost-core' ) ),
				'id'             => array( 'text', __( 'Anchor ID', 'rmdhost-core' ) ),
			),
			'apps'         => array(
				'group' => array( 'group', __( 'Product group', 'rmdhost-core' ) ),
				'title' => $t,
				'text'  => $x,
				'id'    => array( 'text', __( 'Anchor ID', 'rmdhost-core' ) ),
			),
			'carousel'     => array(
				'title'   => $t,
				'exclude' => array( 'group', __( 'Hide this group', 'rmdhost-core' ) ),
			),
			'all-plans'    => array(
				'callout' => array( 'switch', __( 'Show “instant servers” callout', 'rmdhost-core' ) ),
				'fine'    => array( 'textarea', __( 'Fine print', 'rmdhost-core' ) ),
			),
			'steps'        => array(
				'title' => $t,
				'items' => array(
					'repeater',
					__( 'Steps', 'rmdhost-core' ),
					'fields' => array(
						'title' => $t,
						'text'  => $x,
					),
				),
				'soft'  => array( 'switch', __( 'Grey background', 'rmdhost-core' ) ),
			),
			'cards'        => array(
				'photo'     => array( 'image', __( 'Photo (optional)', 'rmdhost-core' ) ),
				'photo_alt' => array( 'text', __( 'Photo alt text', 'rmdhost-core' ) ),
				'caption'   => array( 'text', __( 'Photo caption', 'rmdhost-core' ) ),
				'stats'     => array( 'switch', __( 'Show company stats', 'rmdhost-core' ) ),
				'title'     => $t,
				'items'     => array(
					'repeater',
					__( 'Cards', 'rmdhost-core' ),
					'fields' => array(
						'icon'  => $icon,
						'title' => $t,
						'text'  => $x,
						'url'   => $u,
					),
				),
				'soft'      => array( 'switch', __( 'Grey background', 'rmdhost-core' ) ),
			),
			'photo'        => array(
				'image'   => array( 'image', __( 'Photo', 'rmdhost-core' ) ),
				'alt'     => array( 'text', __( 'Alt text', 'rmdhost-core' ) ),
				'caption' => array( 'text', __( 'Caption', 'rmdhost-core' ) ),
			),
			'references'   => array( 'title' => $t ),
			'status'       => array(
				'title'       => $t,
				'maintenance' => array( 'textarea', __( 'Maintenance note', 'rmdhost-core' ) ),
			),
			'contact'      => array(
				'photo' => array( 'image', __( 'Photo', 'rmdhost-core' ) ),
				'title' => $t,
				'text'  => $x,
			),
			'kb'           => array(),
			'tutorials'    => array(),
			'faq-all'      => array(),
			'heading'      => array(
				'eyebrow' => array( 'text', __( 'Eyebrow', 'rmdhost-core' ) ),
				'title'   => array( 'textarea', __( 'Title (HTML allowed)', 'rmdhost-core' ) ),
				'text'    => $x,
				'soft'    => array( 'switch', __( 'Grey background', 'rmdhost-core' ) ),
			),
		);
		return $map[ $section ] ?? array();
	}

	/**
	 * Icon options.
	 *
	 * @return array
	 */
	public static function icons() {
		$names = function_exists( 'rmdhost_icon_names' ) ? rmdhost_icon_names() : array();
		return array( '' => '—' ) + ( $names ? array_combine( $names, $names ) : array() );
	}

	/**
	 * Product group options.
	 *
	 * @return array
	 */
	public static function groups() {
		return class_exists( '\RMDHost\Data' ) ? \RMDHost\Data::group_choices() : array();
	}

	/**
	 * Convert raw values (from Elementor settings or block attributes) to section args.
	 *
	 * @param string $section Section slug.
	 * @param array  $values  Raw values keyed like the schema.
	 * @return array
	 */
	public static function to_args( $section, $values ) {
		$args     = array();
		$defaults = function_exists( 'rmdhost_section_defaults' ) ? rmdhost_section_defaults( $section ) : array();
		foreach ( self::fields( $section ) as $key => $field ) {
			if ( ! array_key_exists( $key, $values ) ) {
				continue;
			}
			$value = self::value( $field, $values[ $key ] );
			if ( 'repeater' === $field[0] && is_array( $value ) ) {
				// Empty sub-fields fall back to the default item at the same position (e.g. bundled photos).
				$base = isset( $defaults[ $key ] ) && is_array( $defaults[ $key ] ) ? array_values( $defaults[ $key ] ) : array();
				foreach ( $value as $i => $item ) {
					foreach ( $item as $k => $v ) {
						if ( ( '' === $v || null === $v ) && isset( $base[ $i ][ $k ] ) ) {
							$value[ $i ][ $k ] = $base[ $i ][ $k ];
						}
					}
				}
			}
			$args[ $key ] = $value;
		}
		return Render::clean( $args );
	}

	/**
	 * Normalise one value.
	 *
	 * @param array $field Field.
	 * @param mixed $raw   Raw value.
	 * @return mixed
	 */
	private static function value( $field, $raw ) {
		switch ( $field[0] ) {
			case 'switch':
				return true === $raw || 'yes' === $raw || '1' === $raw || 1 === $raw;
			case 'image':
				if ( is_array( $raw ) ) {
					return ! empty( $raw['id'] ) ? (int) $raw['id'] : ( $raw['url'] ?? '' );
				}
				return is_numeric( $raw ) ? (int) $raw : (string) $raw;
			case 'url':
				return is_array( $raw ) ? (string) ( $raw['url'] ?? '' ) : (string) $raw;
			case 'number':
				return '' === $raw || null === $raw ? '' : (int) $raw;
			case 'lines':
				if ( is_array( $raw ) ) {
					return array_values( array_filter( array_map( 'trim', $raw ) ) );
				}
				$lines = array_values( array_filter( array_map( 'trim', explode( "\n", (string) $raw ) ) ) );
				return $lines ? $lines : '';
			case 'groups':
				return array_values( array_filter( array_map( 'sanitize_key', (array) $raw ) ) );
			case 'repeater':
				$out = array();
				foreach ( (array) $raw as $item ) {
					$row = array();
					foreach ( $field['fields'] as $k => $sub ) {
						$row[ $k ] = isset( $item[ $k ] ) ? self::value( $sub, $item[ $k ] ) : '';
					}
					$out[] = $row;
				}
				return $out;
			default:
				return is_scalar( $raw ) ? wp_kses_post( (string) $raw ) : '';
		}
	}
}
