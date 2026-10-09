<?php
/**
 * Blog sidebar (shown only when it has widgets).
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside class="sidebar widget-area" aria-label="<?php esc_attr_e( 'Sidebar', 'rmdhost' ); ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
