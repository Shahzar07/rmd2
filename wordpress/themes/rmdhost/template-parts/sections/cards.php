<?php
/**
 * Three soft cards (mission/vision/values, contact routes…).
 *
 * @package RMDHost
 * @var array $args title, items[ icon, title, text, url ], soft, photo, stats.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a = $args;
?>
<section class="section <?php echo ! empty( $rmdhost_a['soft'] ) ? 'section-soft' : ''; ?>">
	<div class="container">
		<?php if ( ! empty( $rmdhost_a['photo'] ) ) : ?>
			<figure class="page-photo" data-reveal><?php echo rmdhost_image( $rmdhost_a['photo'], $rmdhost_a['photo_alt'] ?? '', array( 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			if ( ! empty( $rmdhost_a['caption'] ) ) :
				?>
				<figcaption><?php echo esc_html( $rmdhost_a['caption'] ); ?></figcaption><?php endif; ?></figure>
		<?php endif; ?>
		<?php if ( ! empty( $rmdhost_a['stats'] ) ) : ?>
			<div class="stats stats-plain" data-reveal>
				<?php foreach ( RMDHost\Data::stats() as $rmdhost_s ) : ?>
					<div class="stat"><b><span data-count="<?php echo esc_attr( $rmdhost_s['value'] ); ?>" data-decimals="<?php echo esc_attr( $rmdhost_s['decimals'] ?? 0 ); ?>">0</span><?php echo esc_html( $rmdhost_s['suffix'] ?? '' ); ?></b><span><?php echo esc_html( $rmdhost_s['label'] ); ?></span></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php echo rmdhost_section_head( array( 'title' => $rmdhost_a['title'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( ! empty( $rmdhost_a['items'] ) ) : ?>
			<div class="three">
				<?php foreach ( (array) $rmdhost_a['items'] as $rmdhost_c ) : ?>
					<?php if ( ! empty( $rmdhost_c['url'] ) ) : ?>
						<a class="soft-card" href="<?php echo esc_url( rmdhost_url( $rmdhost_c['url'] ) ); ?>" data-reveal data-spot><?php echo ! empty( $rmdhost_c['icon'] ) ? rmdhost_icon( $rmdhost_c['icon'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><strong><?php echo esc_html( $rmdhost_c['title'] ); ?></strong><span><?php echo esc_html( $rmdhost_c['text'] ); ?></span></a>
					<?php else : ?>
						<div class="soft-card static" data-reveal><?php echo ! empty( $rmdhost_c['icon'] ) ? rmdhost_icon( $rmdhost_c['icon'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><strong><?php echo esc_html( $rmdhost_c['title'] ); ?></strong><span><?php echo esc_html( $rmdhost_c['text'] ); ?></span></div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
