<?php
/**
 * Customer reference logos + review masonry.
 *
 * @package RMDHost
 * @var array $args title.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section">
	<div class="container">
		<div class="ref-grid">
			<?php foreach ( RMDHost\Data::references() as $rmdhost_r ) : ?>
				<?php
				$rmdhost_initials = implode(
					'',
					array_map(
						static function ( $w ) {
							return mb_substr( $w, 0, 1 );
						},
						explode( ' ', $rmdhost_r['name'] )
					)
				);
				?>
				<div class="ref" data-reveal data-spot><span class="ref-logo"><?php echo esc_html( $rmdhost_initials ); ?></span><b><?php echo esc_html( $rmdhost_r['name'] ); ?></b><small><?php echo esc_html( $rmdhost_r['sector'] . ' · ' . $rmdhost_r['product'] ); ?></small></div>
			<?php endforeach; ?>
		</div>
		<?php echo rmdhost_section_head( array( 'title' => $args['title'] ?? __( 'What customers say', 'rmdhost' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="masonry masonry-3">
			<?php
			foreach ( RMDHost\Data::reviews() as $rmdhost_rv ) {
				get_template_part( 'template-parts/components/review', null, array( 'review' => $rmdhost_rv ) );
			}
			?>
		</div>
	</div>
</section>
