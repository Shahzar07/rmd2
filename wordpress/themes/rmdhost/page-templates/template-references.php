<?php
/**
 * Template Name: References
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	rmdhost_singular_hero(
		array(
			'eyebrow' => __( 'References', 'rmdhost' ),
			'lede'    => __( 'From agencies and traders to game networks and AI start-ups – here’s who trusts us with their infrastructure.', 'rmdhost' ),
		)
	);
	rmdhost_page_content();
	rmdhost_section( 'references' );
	rmdhost_section( 'cta', array( 'title' => __( 'Become our next<br>success story.', 'rmdhost' ) ) );
endwhile;

get_footer();
