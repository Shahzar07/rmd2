<?php
/**
 * Generic section heading (eyebrow, title, intro) – useful between builder blocks.
 *
 * @package RMDHost
 * @var array $args eyebrow, title, text, soft.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section <?php echo ! empty( $args['soft'] ) ? 'section-soft' : ''; ?> section-head-only">
	<div class="container">
		<?php echo rmdhost_section_head( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
