<?php
/**
 * Template Name: Tutorials
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	rmdhost_singular_hero(
		array(
			'eyebrow' => __( 'Tutorials', 'rmdhost' ),
			'title'   => __( 'Learn by doing', 'rmdhost' ),
			'lede'    => __( 'Step-by-step guides to get the most from your server.', 'rmdhost' ),
			'small'   => true,
		)
	);
	rmdhost_page_content();
	rmdhost_section( 'tutorials' );
endwhile;

get_footer();
