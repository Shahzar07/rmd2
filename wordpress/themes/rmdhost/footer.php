<?php
/**
 * Site footer.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php
if ( ! RMDHost\Elementor::location( 'footer' ) ) {
	get_template_part( 'template-parts/layout/site-footer' );
}
if ( get_theme_mod( 'rmdhost_cookie_enabled', true ) ) {
	get_template_part( 'template-parts/layout/cookie' );
}
wp_footer();
?>
</body>
</html>
