<?php
/**
 * Template Name: Data centres
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	if ( rmdhost_is_elementor() ) {
		the_content();
		continue;
	}
	rmdhost_singular_hero(
		array(
			'eyebrow' => __( 'Global network', 'rmdhost' ),
			'lede'    => __( 'Deploy closer to your users. Six locations across the UK, Europe and North America with Cisco 10 Gb/s fibre and direct LINX connectivity.', 'rmdhost' ),
		)
	);
	rmdhost_section(
		'locations',
		array(
			'stats'     => false,
			'photo'     => has_post_thumbnail() ? get_post_thumbnail_id() : 'datacentre-aisle',
			'photo_alt' => __( 'Server racks lining a data centre aisle', 'rmdhost' ),
			'caption'   => __( 'Six data centres. One network built for uptime.', 'rmdhost' ),
		)
	);
	rmdhost_page_content( 'section pt-0' );
	rmdhost_section(
		'features',
		array(
			'group'    => '',
			'title'    => __( 'Built for uptime', 'rmdhost' ),
			'id'       => '',
			'features' => array(
				array(
					'icon'  => 'lock',
					'title' => __( '24/7 physical security', 'rmdhost' ),
					'text'  => __( 'Biometric access control, CCTV monitoring and on-site security teams at every facility.', 'rmdhost' ),
				),
				array(
					'icon'  => 'bolt',
					'title' => __( 'Redundant power', 'rmdhost' ),
					'text'  => __( 'Dual power feeds, N+1 UPS battery backup and diesel generators.', 'rmdhost' ),
				),
				array(
					'icon'  => 'leaf',
					'title' => __( 'Efficient cooling', 'rmdhost' ),
					'text'  => __( 'N+1 climate control with under-floor air distribution.', 'rmdhost' ),
				),
				array(
					'icon'  => 'globe',
					'title' => __( 'Carrier-grade network', 'rmdhost' ),
					'text'  => __( 'Cisco 10 Gb/s fibre backbone with multi-path connectivity and LINX peering.', 'rmdhost' ),
				),
				array(
					'icon'  => 'shield',
					'title' => __( 'DDoS mitigation', 'rmdhost' ),
					'text'  => __( 'Edge filtering protects every server in every location.', 'rmdhost' ),
				),
				array(
					'icon'  => 'headset',
					'title' => __( 'Remote hands', 'rmdhost' ),
					'text'  => __( 'Engineers on site to replace hardware and assist 24/7.', 'rmdhost' ),
				),
			),
		)
	);
	rmdhost_section( 'cta', array( 'title' => __( 'Pick a location.<br>Deploy in seconds.', 'rmdhost' ) ) );
endwhile;

get_footer();
