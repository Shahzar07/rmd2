<?php
/**
 * Plugin Name: RMDHost Homepage Content
 * Description: Edit the RMDHost homepage hero, offer banner, review score and stats from WordPress. The static site pulls this content at build time from /wp-json/rmdhost/v1/homepage.
 * Version:     1.0.0
 * Author:      RMDHost
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RMDHOST_HP_OPTION = 'rmdhost_homepage';

/**
 * Field definitions: key => [label, type, section]. Keys use dots to map onto
 * the nested JSON the site expects (see src/data/homepage.json).
 */
function rmdhost_hp_fields() {
	$f = array(
		'hero.eyebrow'               => array( 'Eyebrow (small text above headline)', 'text', 'hero' ),
		'hero.headline'              => array( 'Headline', 'text', 'hero' ),
		'hero.text'                  => array( 'Benefit text', 'textarea', 'hero' ),
		'hero.offer.label'           => array( 'Offer label (e.g. "Linux VPS from")', 'text', 'hero' ),
		'hero.offer.price'           => array( 'Offer price in GBP (number, e.g. 8.99)', 'number', 'hero' ),
		'hero.offer.suffix'          => array( 'Offer price suffix (e.g. "/mo")', 'text', 'hero' ),
		'hero.offer.note'            => array( 'Offer note', 'text', 'hero' ),
		'hero.primaryCta.label'      => array( 'Primary button label', 'text', 'hero' ),
		'hero.primaryCta.href'       => array( 'Primary button link', 'url', 'hero' ),
		'hero.secondaryCta.label'    => array( 'Secondary button label', 'text', 'hero' ),
		'hero.secondaryCta.href'     => array( 'Secondary button link', 'url', 'hero' ),
		'banner.enabled'             => array( 'Show the offer & review banner', 'checkbox', 'banner' ),
		'banner.offerTag'            => array( 'Offer tag (e.g. "Offer")', 'text', 'banner' ),
		'banner.offerText'           => array( 'Offer text', 'textarea', 'banner' ),
		'banner.offerCta.label'      => array( 'Offer button label', 'text', 'banner' ),
		'banner.offerCta.href'       => array( 'Offer button link', 'url', 'banner' ),
		'banner.rating.label'        => array( 'Review source label (e.g. "DigitalBerg on HostAdvice")', 'text', 'banner' ),
		'banner.rating.note'         => array( 'Review note shown under the label', 'text', 'banner' ),
		'banner.rating.score'        => array( 'Review score (leave empty to hide)', 'number', 'banner' ),
		'banner.rating.scale'        => array( 'Score scale (e.g. 5 or 10)', 'number', 'banner' ),
		'banner.rating.count'        => array( 'Number of reviews (leave empty to hide)', 'number', 'banner' ),
		'banner.rating.url'          => array( 'Review page link', 'url', 'banner' ),
		'banner.rating.linkText'     => array( 'Review link text', 'text', 'banner' ),
	);
	for ( $i = 0; $i < 4; $i++ ) {
		$f[ "stats.$i.value" ] = array( 'Stat ' . ( $i + 1 ) . ' value', 'text', 'stats' );
		$f[ "stats.$i.label" ] = array( 'Stat ' . ( $i + 1 ) . ' label', 'text', 'stats' );
	}
	$f['build.deployHook'] = array( 'Deploy hook URL (Vercel/Netlify) – called after saving to rebuild the site', 'url', 'build' );
	return $f;
}

add_action( 'admin_menu', function () {
	add_options_page( 'RMDHost Homepage', 'RMDHost Homepage', 'manage_options', 'rmdhost-homepage', 'rmdhost_hp_render_page' );
} );

add_action( 'admin_init', function () {
	register_setting( 'rmdhost_homepage', RMDHOST_HP_OPTION, array( 'sanitize_callback' => 'rmdhost_hp_sanitize' ) );
	$sections = array(
		'hero'   => 'Hero',
		'banner' => 'Offer & review banner',
		'stats'  => 'Stats row',
		'build'  => 'Publishing',
	);
	foreach ( $sections as $id => $title ) {
		add_settings_section( "rmdhost_hp_$id", $title, '__return_false', 'rmdhost-homepage' );
	}
	foreach ( rmdhost_hp_fields() as $key => $def ) {
		add_settings_field( $key, esc_html( $def[0] ), 'rmdhost_hp_render_field', 'rmdhost-homepage', 'rmdhost_hp_' . $def[2], array( 'key' => $key, 'type' => $def[1] ) );
	}
} );

function rmdhost_hp_sanitize( $input ) {
	$out = array();
	foreach ( rmdhost_hp_fields() as $key => $def ) {
		$v = isset( $input[ $key ] ) ? $input[ $key ] : '';
		switch ( $def[1] ) {
			case 'checkbox':
				$out[ $key ] = empty( $v ) ? '0' : '1';
				break;
			case 'number':
				$out[ $key ] = ( '' === $v || ! is_numeric( $v ) ) ? '' : (string) floatval( $v );
				break;
			case 'url':
				$out[ $key ] = ( '' !== $v && '#' === $v[0] ) ? sanitize_text_field( $v ) : esc_url_raw( $v );
				break;
			case 'textarea':
				$out[ $key ] = sanitize_textarea_field( $v );
				break;
			default:
				$out[ $key ] = sanitize_text_field( $v );
		}
	}
	return $out;
}

function rmdhost_hp_render_field( $args ) {
	$opts  = get_option( RMDHOST_HP_OPTION, array() );
	$key   = $args['key'];
	$name  = RMDHOST_HP_OPTION . '[' . $key . ']';
	$value = isset( $opts[ $key ] ) ? $opts[ $key ] : '';
	switch ( $args['type'] ) {
		case 'textarea':
			printf( '<textarea name="%s" rows="3" class="large-text">%s</textarea>', esc_attr( $name ), esc_textarea( $value ) );
			break;
		case 'checkbox':
			printf( '<input type="checkbox" name="%s" value="1" %s>', esc_attr( $name ), checked( $value, '1', false ) );
			break;
		case 'number':
			printf( '<input type="number" step="any" name="%s" value="%s" class="small-text">', esc_attr( $name ), esc_attr( $value ) );
			break;
		default:
			printf( '<input type="text" name="%s" value="%s" class="regular-text">', esc_attr( $name ), esc_attr( $value ) );
	}
}

function rmdhost_hp_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>RMDHost Homepage</h1>';
	echo '<p>Empty fields keep the default content from the website. Review figures must match the review source exactly.</p>';
	echo '<form method="post" action="options.php">';
	settings_fields( 'rmdhost_homepage' );
	do_settings_sections( 'rmdhost-homepage' );
	submit_button( 'Save & rebuild site' );
	echo '</form></div>';
}

/** Turn the flat "a.b.c" option keys into the nested JSON the site expects. */
function rmdhost_hp_payload() {
	$opts = get_option( RMDHOST_HP_OPTION, array() );
	$data = array();
	foreach ( rmdhost_hp_fields() as $key => $def ) {
		if ( 0 === strpos( $key, 'build.' ) || ! isset( $opts[ $key ] ) || '' === $opts[ $key ] ) {
			continue;
		}
		$v = $opts[ $key ];
		if ( 'number' === $def[1] ) {
			$v = floatval( $v );
		} elseif ( 'checkbox' === $def[1] ) {
			$v = '1' === $v;
		}
		$ref = &$data;
		foreach ( explode( '.', $key ) as $part ) {
			if ( ! isset( $ref[ $part ] ) ) {
				$ref[ $part ] = array();
			}
			$ref = &$ref[ $part ];
		}
		$ref = $v;
		unset( $ref );
	}
	if ( isset( $data['stats'] ) ) {
		$data['stats'] = array_values( array_filter( $data['stats'], function ( $s ) {
			return ! empty( $s['value'] ) && ! empty( $s['label'] );
		} ) );
	}
	return $data;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'rmdhost/v1', '/homepage', array(
		'methods'             => 'GET',
		'callback'            => function () {
			return rest_ensure_response( (object) rmdhost_hp_payload() );
		},
		'permission_callback' => '__return_true', // Public homepage copy only.
	) );
} );

// Trigger a site rebuild after the settings are saved (first save or update).
function rmdhost_hp_rebuild( $value ) {
	if ( is_array( $value ) && ! empty( $value['build.deployHook'] ) ) {
		wp_remote_post( $value['build.deployHook'], array( 'timeout' => 5, 'blocking' => false ) );
	}
}
add_action( 'update_option_' . RMDHOST_HP_OPTION, function ( $old, $new ) {
	rmdhost_hp_rebuild( $new );
}, 10, 2 );
add_action( 'add_option_' . RMDHOST_HP_OPTION, function ( $name, $value ) {
	rmdhost_hp_rebuild( $value );
}, 10, 2 );
