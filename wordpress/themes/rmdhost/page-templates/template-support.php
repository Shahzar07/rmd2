<?php
/**
 * Template Name: Support & contact
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
			'eyebrow' => __( 'Support', 'rmdhost' ),
			'lede'    => __( 'Our team answers every ticket – day or night, every day of the year.', 'rmdhost' ),
			'small'   => true,
		)
	);
	rmdhost_section( 'contact', array( 'photo' => has_post_thumbnail() ? get_post_thumbnail_id() : 'support-engineer' ) );
	rmdhost_page_content( 'section pt-0' );
	rmdhost_section(
		'faq',
		array(
			'title' => __( 'Quick answers', 'rmdhost' ),
			'faqs'  => array_slice( RMDHost\Data::faqs(), 0, 5 ),
		)
	);
endwhile;

get_footer();
