<?php
/**
 * Fallback template.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();
rmdhost_page_hero(
	array(
		'title' => is_home() ? single_post_title( '', false ) : wp_strip_all_tags( get_the_archive_title() ),
		'small' => true,
	)
);
get_template_part( 'template-parts/content/loop' );
get_footer();
