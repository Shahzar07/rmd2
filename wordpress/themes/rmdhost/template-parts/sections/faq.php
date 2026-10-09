<?php
/**
 * FAQ accordion section.
 *
 * @package RMDHost
 * @var array $args title, group, soft, faqs (optional explicit list).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_faqs = ! empty( $args['faqs'] ) ? $args['faqs'] : RMDHost\Data::faqs( $args['group'] );
if ( ! $rmdhost_faqs ) {
	return;
}
?>
<section class="section <?php echo ! empty( $args['soft'] ) ? 'section-soft' : ''; ?>"<?php echo ! empty( $args['id'] ) ? ' id="' . esc_attr( $args['id'] ) . '"' : ''; ?>>
	<div class="container narrow">
		<?php echo rmdhost_section_head( array( 'title' => $args['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo rmdhost_faq_list( $rmdhost_faqs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
