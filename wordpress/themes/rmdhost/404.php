<?php
/**
 * 404.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();
rmdhost_page_hero(
	array(
		'eyebrow' => __( 'Error 404', 'rmdhost' ),
		'title'   => __( 'This page went offline', 'rmdhost' ),
		'lede'    => __( 'The page you’re looking for doesn’t exist or has moved.', 'rmdhost' ),
		'crumbs'  => array(),
		'actions' => '<div class="hero-cta"><a class="btn btn-white btn-lg" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Back to home', 'rmdhost' ) . '</a></div>',
	)
);
get_footer();
