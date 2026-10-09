<?php
/**
 * "Explore more servers" carousel.
 *
 * @package RMDHost
 * @var array $args title, exclude.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section">
	<div class="container">
		<?php echo rmdhost_section_head( array( 'title' => $args['title'] ?? __( 'Explore more servers', 'rmdhost' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php get_template_part( 'template-parts/components/product-carousel', null, array( 'exclude' => $args['exclude'] ?? '' ) ); ?>
	</div>
</section>
