<?php
/**
 * Front page. Uses the page's own content when it is built with Elementor or the
 * block editor; otherwise renders the homepage sections enabled in
 * Customizer → RMDHost Theme → Homepage sections.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

$rmdhost_page = 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0;

if ( $rmdhost_page && ( rmdhost_is_elementor( $rmdhost_page ) || trim( (string) get_post_field( 'post_content', $rmdhost_page ) ) ) ) {
	while ( have_posts() ) {
		the_post();
		echo '<div class="entry-full">';
		the_content();
		echo '</div>';
	}
} else {
	foreach ( RMDHost\Customizer::home_sections() as $rmdhost_slug => $rmdhost_label ) {
		if ( get_theme_mod( 'rmdhost_show_' . $rmdhost_slug, true ) ) {
			rmdhost_section( $rmdhost_slug );
		}
	}
}

get_footer();
