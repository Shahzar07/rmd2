<?php
/**
 * Template Name: Network status
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	rmdhost_singular_hero(
		array(
			'eyebrow' => __( 'Network status', 'rmdhost' ),
			'title'   => __( 'All systems operational', 'rmdhost' ),
			'lede'    => __( 'Live health of our platform, data centres and services. Subscribe to updates from the client area.', 'rmdhost' ),
			'small'   => true,
		)
	);
	rmdhost_section( 'status' );
	rmdhost_page_content( 'section pt-0' );
endwhile;

get_footer();
