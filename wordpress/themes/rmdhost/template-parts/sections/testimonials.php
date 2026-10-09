<?php
/**
 * Testimonials wall: four columns mixing videos, photos and reviews.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'testimonials' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a       = $args;
$rmdhost_reviews = array_values( RMDHost\Data::reviews() );
$rmdhost_tiles   = array_values( (array) $rmdhost_a['tiles'] );

// Interleave media tiles and reviews into four balanced columns (same order as the design).
$rmdhost_layout = array(
	array( array( 't', 0 ), array( 'r', 0 ), array( 't', 1 ) ),
	array( array( 'r', 1 ), array( 't', 2 ), array( 'r', 2 ) ),
	array( array( 't', 3 ), array( 'r', 3 ), array( 't', 4 ) ),
	array( array( 'r', 4 ), array( 't', 5 ), array( 'r', 5 ) ),
);
$rmdhost_used_r = array();
$rmdhost_used_t = array();
?>
<section class="section pt-0">
	<div class="container">
		<?php
		echo rmdhost_section_head(
			array(
				'title' => $rmdhost_a['title'],
				'text'  => $rmdhost_a['text'],
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		?>
		<?php if ( $rmdhost_a['button'] ) : ?>
			<p class="center" data-reveal><a class="btn btn-primary" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['button_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['button'] ); ?></a></p>
		<?php endif; ?>
		<div class="wall">
			<?php foreach ( $rmdhost_layout as $rmdhost_ci => $rmdhost_col ) : ?>
				<div class="wall-col<?php echo $rmdhost_ci % 2 ? ' off' : ''; ?>">
					<?php
					foreach ( $rmdhost_col as $rmdhost_cell ) {
						if ( 'r' === $rmdhost_cell[0] && isset( $rmdhost_reviews[ $rmdhost_cell[1] ] ) ) {
							$rmdhost_used_r[] = $rmdhost_cell[1];
							get_template_part( 'template-parts/components/review', null, array( 'review' => $rmdhost_reviews[ $rmdhost_cell[1] ] ) );
						} elseif ( 't' === $rmdhost_cell[0] && isset( $rmdhost_tiles[ $rmdhost_cell[1] ] ) ) {
							$rmdhost_used_t[] = $rmdhost_cell[1];
							$rmdhost_t        = $rmdhost_tiles[ $rmdhost_cell[1] ];
							?>
							<a class="wall-tile <?php echo ! empty( $rmdhost_t['tall'] ) ? 'tall' : ''; ?>" href="<?php echo esc_url( rmdhost_url( $rmdhost_t['url'] ) ); ?>" data-reveal>
								<?php
								echo ! empty( $rmdhost_t['video'] )
									? rmdhost_video( $rmdhost_t['video'], 'wall-media' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									: rmdhost_image(
										$rmdhost_t['photo'],
										wp_strip_all_tags( $rmdhost_t['cap'] ),
										array(
											'class' => 'wall-media',
											'sizes' => '(max-width: 640px) 100vw, 25vw',
										)
									); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
								<span class="m-tag"><?php echo esc_html( $rmdhost_t['tag'] ); ?></span>
								<?php if ( ! empty( $rmdhost_t['video'] ) ) : ?>
									<span class="play"><?php echo rmdhost_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<?php endif; ?>
								<span class="wall-cap"><?php echo rmdhost_kses_inline( $rmdhost_t['cap'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</a>
							<?php
						}
					}
					// Extra reviews beyond the design grid flow into the last column.
					if ( 3 === $rmdhost_ci ) {
						foreach ( $rmdhost_reviews as $rmdhost_ri => $rmdhost_rv ) {
							if ( $rmdhost_ri > 5 ) {
								get_template_part( 'template-parts/components/review', null, array( 'review' => $rmdhost_rv ) );
							}
						}
					}
					?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( $rmdhost_a['rating_text'] ) : ?>
			<div class="rating-bar" data-reveal>
				<span><?php echo rmdhost_stars( 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p><?php echo rmdhost_kses_inline( $rmdhost_a['rating_text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
