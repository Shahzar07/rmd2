<?php
/**
 * Template Name: Full width (sections)
 * Template Post Type: page, post
 *
 * Page hero + edge-to-edge content. Use with RMDHost section blocks, Elementor
 * or full-width core blocks.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	if ( ! rmdhost_is_elementor() ) {
		rmdhost_singular_hero();
	}
	echo '<div class="entry-full">';
	the_content();
	echo '</div>';
endwhile;

get_footer();
