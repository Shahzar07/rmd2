<?php
/**
 * Blog index (Settings → Reading → Posts page).
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

$rmdhost_blog = (int) get_option( 'page_for_posts' );
if ( ! $rmdhost_blog || ! get_post_meta( $rmdhost_blog, '_rmd_hide_hero', true ) ) {
	rmdhost_page_hero(
		array(
			'eyebrow' => $rmdhost_blog && get_post_meta( $rmdhost_blog, '_rmd_hero_eyebrow', true ) ? get_post_meta( $rmdhost_blog, '_rmd_hero_eyebrow', true ) : __( 'Blog', 'rmdhost' ),
			'title'   => $rmdhost_blog && get_post_meta( $rmdhost_blog, '_rmd_hero_title', true ) ? get_post_meta( $rmdhost_blog, '_rmd_hero_title', true ) : ( $rmdhost_blog ? __( 'Guides, news and ideas', 'rmdhost' ) : get_bloginfo( 'name' ) ),
			'lede'    => $rmdhost_blog && get_post_meta( $rmdhost_blog, '_rmd_hero_lede', true ) ? get_post_meta( $rmdhost_blog, '_rmd_hero_lede', true ) : __( 'Practical advice for running fast, secure servers.', 'rmdhost' ),
			'small'   => true,
		)
	);
}
get_template_part( 'template-parts/content/loop' );
get_footer();
