<?php
/**
 * Alternating text + animated scene rows.
 *
 * @package RMDHost
 * @var array $args rows.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section alt-sec">
	<div class="container">
		<?php foreach ( (array) $args['rows'] as $rmdhost_i => $rmdhost_r ) : ?>
			<div class="alt <?php echo $rmdhost_i % 2 ? 'alt-rev' : ''; ?>" data-reveal>
				<?php if ( $rmdhost_i % 2 ) : ?>
					<div class="alt-visual"><?php echo rmdhost_scene( $rmdhost_r['scene'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
				<div class="alt-copy">
					<h2 class="h3"><?php echo esc_html( $rmdhost_r['title'] ); ?></h2>
					<p class="muted"><?php echo esc_html( $rmdhost_r['text'] ); ?></p>
					<?php if ( ! empty( $rmdhost_r['link'] ) ) : ?>
						<a class="row-link" href="<?php echo esc_url( rmdhost_url( $rmdhost_r['url'] ) ); ?>"><?php echo esc_html( $rmdhost_r['link'] ); ?><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<?php endif; ?>
				</div>
				<?php if ( ! ( $rmdhost_i % 2 ) ) : ?>
					<div class="alt-visual"><?php echo rmdhost_scene( $rmdhost_r['scene'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
