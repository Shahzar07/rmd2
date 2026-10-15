<?php
/**
 * Template Name: DDoS protection
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
	get_template_part( 'template-parts/generated/protection-content' );
	rmdhost_page_content();
endwhile;
get_footer();
