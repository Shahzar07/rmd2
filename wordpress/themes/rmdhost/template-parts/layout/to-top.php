<?php
/**
 * Back-to-top button: shown once the visitor has scrolled down (see main.js).
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;
?>
<button type="button" class="to-top" data-to-top aria-label="<?php esc_attr_e( 'Back to top', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'arrowUp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="to-top-bar" aria-hidden="true"></span></button>
