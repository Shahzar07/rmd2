<?php
/**
 * "Set up the essentials" cards with technical CSS/canvas visuals.
 *
 * @package RMDHost
 * @var array $args title, items.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a = $args;
?>
<section class="section pt-0">
	<div class="container">
		<?php echo rmdhost_section_head( array( 'title' => $rmdhost_a['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="ess-grid">
			<?php foreach ( (array) $rmdhost_a['items'] as $rmdhost_e ) : ?>
				<?php
				$rmdhost_href = ! empty( $rmdhost_e['url'] ) ? $rmdhost_e['url'] : '/' . $rmdhost_e['group'] . '/';
				$rmdhost_from = ! empty( $rmdhost_e['group'] ) ? RMDHost\Data::from_price( $rmdhost_e['group'] ) : null;
				?>
				<a class="ess" href="<?php echo esc_url( rmdhost_url( $rmdhost_href ) ); ?>" data-reveal>
					<span class="ess-photo ess-tech"><?php echo rmdhost_scene( 'tech-' . $rmdhost_e['visual'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="ess-chip"><i class="led"></i><?php echo esc_html( $rmdhost_e['chip'] ); ?></span></span>
					<strong><?php echo esc_html( $rmdhost_e['title'] ); ?></strong>
					<span><?php echo esc_html( $rmdhost_e['text'] ); ?></span>
					<?php if ( null !== $rmdhost_from ) : ?>
						<span class="ess-price"><?php esc_html_e( 'From', 'rmdhost' ); ?> <?php echo rmdhost_money( $rmdhost_from ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
