<?php
/**
 * Template Name: Knowledge base
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$rmdhost_search = '<form class="kb-search" data-kb-search role="search" data-reveal onsubmit="return false">' . rmdhost_icon( 'search' ) . '<label class="sr-only" for="kbq">' . esc_html__( 'Search the knowledge base', 'rmdhost' ) . '</label><input id="kbq" type="search" placeholder="' . esc_attr__( 'e.g. reset root password', 'rmdhost' ) . '"></form>';
	rmdhost_singular_hero(
		array(
			'eyebrow' => __( 'Knowledge base', 'rmdhost' ),
			'title'   => __( 'How can we help?', 'rmdhost' ),
			'lede'    => __( 'Search our help articles or browse by topic.', 'rmdhost' ),
			'small'   => true,
			'actions' => $rmdhost_search,
		)
	);
	rmdhost_page_content();
	rmdhost_section( 'kb' );
	rmdhost_section(
		'cta',
		array(
			'title'      => __( 'Can’t find an answer?', 'rmdhost' ),
			'text'       => __( 'Open a ticket and an engineer will reply – usually within minutes.', 'rmdhost' ),
			'button'     => __( 'Contact support', 'rmdhost' ),
			'button_url' => '/support/',
		)
	);
endwhile;

get_footer();
