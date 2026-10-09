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
			'eyebrow' => __( 'Plans and prices', 'rmdhost' ),
			'lede'    => __( 'Monthly billing, no setup fees on VPS, DDoS protection on every server. Switch between GBP and USD at any time.', 'rmdhost' ),
		)
	);
	rmdhost_section( 'all-plans' );
	rmdhost_page_content( 'section pt-0' );
	rmdhost_section( 'cta' );
endwhile;

get_footer();
