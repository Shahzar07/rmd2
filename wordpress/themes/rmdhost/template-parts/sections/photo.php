<?php
/**
 * Full-width captioned photo.
 *
 * @package RMDHost
 * @var array $args image, alt, caption.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $args['image'] ) ) {
	return;
}
?>
<section class="section pb-0">
	<div class="container">
		<figure class="page-photo" data-reveal><?php echo rmdhost_image( $args['image'], $args['alt'] ?? '', array( 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php
		if ( ! empty( $args['caption'] ) ) :
			?>
			<figcaption><?php echo esc_html( $args['caption'] ); ?></figcaption><?php endif; ?></figure>
	</div>
</section>
