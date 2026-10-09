<?php
/**
 * Template Name: Server product
 *
 * Complete product landing page for the product group chosen in the
 * "RMDHost page settings" box (plans, features, OS, locations, FAQ…).
 * Anything written in the editor appears after the plans.
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

	$rmdhost_slug  = get_post_meta( get_the_ID(), '_rmd_group', true );
	$rmdhost_slug  = $rmdhost_slug ? $rmdhost_slug : get_post_field( 'post_name' );
	$rmdhost_group = RMDHost\Data::group( $rmdhost_slug );

	if ( ! $rmdhost_group ) {
		rmdhost_singular_hero();
		if ( current_user_can( 'edit_pages' ) ) {
			echo '<section class="section"><div class="container narrow"><p class="note">' . esc_html__( 'Choose a product group in “RMDHost page settings” to display plans on this page.', 'rmdhost' ) . '</p></div></section>';
		}
		rmdhost_page_content();
		continue;
	}

	$rmdhost_hide = get_post_meta( get_the_ID(), '_rmd_hide_hero', true );
	if ( ! $rmdhost_hide ) {
		rmdhost_section(
			'product-hero',
			array(
				'group'   => $rmdhost_slug,
				'eyebrow' => get_post_meta( get_the_ID(), '_rmd_hero_eyebrow', true ),
				'lede'    => get_post_meta( get_the_ID(), '_rmd_hero_lede', true ),
			)
		);
	}
	rmdhost_section( 'plans', array( 'group' => $rmdhost_slug ) );
	rmdhost_page_content();
	rmdhost_section( 'use-tabs', array( 'group' => $rmdhost_slug ) );
	rmdhost_section( 'features', array( 'group' => $rmdhost_slug ) );
	rmdhost_section(
		'locations',
		array(
			'id'    => 'locations',
			'stats' => false,
		)
	);
	rmdhost_section( 'apps', array( 'group' => $rmdhost_slug ) );
	rmdhost_section(
		'faq',
		array(
			'group' => $rmdhost_slug,
			/* translators: %s: product name */
			'title' => sprintf( __( '%s FAQs', 'rmdhost' ), $rmdhost_group['name'] ),
			'id'    => 'faq',
		)
	);
	rmdhost_section( 'carousel', array( 'exclude' => $rmdhost_slug ) );

	if ( ! empty( $rmdhost_group['disclaimer'] ) ) {
		echo '<section class="section pt-0 disclaimer"><div class="container"><p class="fine left">' . rmdhost_kses_inline( $rmdhost_group['disclaimer'] ) . '</p></div></section>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	$rmdhost_is_server = false !== stripos( $rmdhost_group['name'], 'server' );
	$rmdhost_has_price = null !== RMDHost\Data::from_price( $rmdhost_slug );
	rmdhost_section(
		'cta',
		array(
			/* translators: %s: product name, e.g. "Linux VPS server". */
			'title'      => sprintf( __( 'Ready to launch your<br>%s?', 'rmdhost' ), esc_html( $rmdhost_is_server ? $rmdhost_group['name'] : $rmdhost_group['name'] . ' ' . __( 'server', 'rmdhost' ) ) ),
			'button_url' => '#plans',
			'button'     => $rmdhost_has_price ? __( 'Choose plan', 'rmdhost' ) : __( 'Contact sales', 'rmdhost' ),
		)
	);
endwhile;

get_footer();
