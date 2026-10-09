<?php
/**
 * "Tell us your project" server finder (recommendation logic in main.js).
 *
 * @package RMDHost
 * @var array $args title, note, phrases.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a = $args;
?>
<section class="idea">
	<div class="idea-bg" aria-hidden="true"></div>
	<div class="container idea-inner">
		<span class="idea-mark" data-reveal><?php echo rmdhost_icon( 'spark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<h2 class="h-display" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<form class="idea-form" data-idea data-reveal>
			<label class="sr-only" for="idea-in"><?php esc_html_e( 'Describe what you want to host', 'rmdhost' ); ?></label>
			<input id="idea-in" type="text" autocomplete="off" data-typewriter="<?php echo esc_attr( wp_json_encode( array_values( (array) $rmdhost_a['phrases'] ) ) ); ?>">
			<button type="submit" class="idea-go" aria-label="<?php esc_attr_e( 'Get a recommendation', 'rmdhost' ); ?>"><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
		</form>
		<p class="idea-note" data-reveal><?php echo esc_html( $rmdhost_a['note'] ); ?></p>
		<div class="idea-result" data-idea-result hidden aria-live="polite"></div>
	</div>
</section>
