<?php
/**
 * Search results.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();
rmdhost_page_hero(
	array(
		'eyebrow' => __( 'Search', 'rmdhost' ),
		/* translators: %s: search query. */
		'title'   => sprintf( __( 'Results for “%s”', 'rmdhost' ), esc_html( get_search_query() ) ),
		'small'   => true,
		'actions' => get_search_form( array( 'echo' => false ) ),
	)
);
get_template_part( 'template-parts/content/loop' );
get_footer();
