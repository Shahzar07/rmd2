<?php
/**
 * Template Name: Pricing (all plans)
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
			'title'   => __( 'Powerful VPS & Dedicated Servers. Built for Performance.', 'rmdhost' ),
			'eyebrow' => __( 'Plans and prices', 'rmdhost' ),
			'lede'    => __( 'Intel, AMD Ryzen™ and AMD EPYC processors, NVMe Gen4 SSD storage, and free 30 Tbps+ DDoS protection. Flexible monthly billing, no VPS setup fees, and 24/7 support.', 'rmdhost' ),
		)
	);
	rmdhost_section( 'all-plans' );
	rmdhost_page_content( 'section pt-0' );
	rmdhost_section( 'cta' );
endwhile;

get_footer();
