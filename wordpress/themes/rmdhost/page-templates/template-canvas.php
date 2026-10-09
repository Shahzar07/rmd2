<?php
/**
 * Template Name: Blank canvas (header + footer)
 * Template Post Type: page, post
 *
 * No hero, no wrappers – just the header, your content and the footer.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	echo '<div class="entry-full">';
	the_content();
	echo '</div>';
endwhile;

get_footer();
