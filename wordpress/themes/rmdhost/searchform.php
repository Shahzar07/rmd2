<?php
/**
 * Search form.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_id = wp_unique_id( 's-' );
?>
<form role="search" method="get" class="kb-search search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php echo rmdhost_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<label class="sr-only" for="<?php echo esc_attr( $rmdhost_id ); ?>"><?php esc_html_e( 'Search for:', 'rmdhost' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $rmdhost_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'rmdhost' ); ?>">
	<button class="btn btn-primary btn-sm" type="submit"><?php esc_html_e( 'Search', 'rmdhost' ); ?></button>
</form>
