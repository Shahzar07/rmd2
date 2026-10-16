<?php
/**
 * Template Name: Edge servers
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
	rmdhost_singular_hero();
	get_template_part( 'template-parts/generated/edge-content' );
	rmdhost_section( 'locations', array( 'stats' => false ) );
	rmdhost_page_content();
endwhile;
get_footer();
