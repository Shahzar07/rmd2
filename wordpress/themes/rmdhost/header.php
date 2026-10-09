<?php
/**
 * Site header.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip" href="#main"><?php esc_html_e( 'Skip to content', 'rmdhost' ); ?></a>
<?php
if ( ! RMDHost\Elementor::location( 'header' ) ) {
	get_template_part( 'template-parts/layout/site-header' );
}
?>
<main id="main" class="site-main">
