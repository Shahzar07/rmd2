<?php
/**
 * Template Name: FAQ (all products)
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	rmdhost_singular_hero(
		array(
			'eyebrow' => __( 'FAQ', 'rmdhost' ),
			'lede'    => __( 'Everything you need to know about our servers, billing and support.', 'rmdhost' ),
			'small'   => true,
		)
	);
	rmdhost_page_content();
	rmdhost_section( 'faq-all' );
	rmdhost_section(
		'cta',
		array(
			'title'      => __( 'Still have questions?', 'rmdhost' ),
			'text'       => __( 'Our engineers are available 24/7.', 'rmdhost' ),
			'button'     => __( 'Contact support', 'rmdhost' ),
			'button_url' => '/support/',
		)
	);
endwhile;

get_footer();
