<?php
/**
 * Archives (categories, tags, authors, dates).
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();
rmdhost_page_hero(
	array(
		'eyebrow' => __( 'Archive', 'rmdhost' ),
		'title'   => wp_strip_all_tags( get_the_archive_title() ),
		'lede'    => wp_strip_all_tags( get_the_archive_description() ),
		'small'   => true,
	)
);
get_template_part( 'template-parts/content/loop' );
get_footer();
