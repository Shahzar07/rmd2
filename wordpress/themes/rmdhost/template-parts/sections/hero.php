<?php
/**
 * Homepage hero with offer, CTAs, stats and the translucent offer/review banner.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'hero' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a     = $args;
$rmdhost_score = ( '' !== $rmdhost_a['rating_score'] && null !== $rmdhost_a['rating_score'] );
$rmdhost_scale = $rmdhost_a['rating_scale'] ? (float) $rmdhost_a['rating_scale'] : 5;
?>
<section class="hero hero-home hero-promo dark">
	<div class="hero-arcs" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
	<div class="grid-bg" aria-hidden="true"></div>
	<div class="container hero-center">
		<?php if ( $rmdhost_a['eyebrow'] ) : ?>
			<p class="hero-eyebrow" data-reveal><?php echo esc_html( $rmdhost_a['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="h-display hero-title" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_a['headline'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
		<?php if ( $rmdhost_a['text'] ) : ?>
			<p class="lead" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_a['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
		<?php if ( '' !== $rmdhost_a['offer_price'] && null !== $rmdhost_a['offer_price'] ) : ?>
			<p class="hero-offer" data-reveal><span class="ho-label"><?php echo esc_html( $rmdhost_a['offer_label'] ); ?></span> <?php echo rmdhost_money( (float) $rmdhost_a['offer_price'], array( 'per' => $rmdhost_a['offer_suffix'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $rmdhost_a['offer_note'] ? '<span class="ho-note">' . esc_html( $rmdhost_a['offer_note'] ) . '</span>' : ''; ?></p>
		<?php endif; ?>
		<div class="hero-cta" data-reveal>
			<?php if ( $rmdhost_a['cta1_label'] ) : ?>
				<a class="btn btn-white btn-lg" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['cta1_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['cta1_label'] ); ?></a>
			<?php endif; ?>
			<?php if ( $rmdhost_a['cta2_label'] ) : ?>
				<a class="btn btn-ghost-light btn-lg" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['cta2_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['cta2_label'] ); ?></a>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $rmdhost_a['stats'] ) ) : ?>
			<ul class="hero-stats" data-reveal>
				<?php foreach ( $rmdhost_a['stats'] as $rmdhost_s ) : ?>
					<li><b><?php echo esc_html( $rmdhost_s['value'] ); ?></b><span><?php echo esc_html( $rmdhost_s['label'] ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php if ( $rmdhost_a['banner'] ) : ?>
		<div class="hero-banner" data-reveal>
			<div class="container hb-inner">
				<div class="hb-offer">
					<?php if ( $rmdhost_a['banner_tag'] ) : ?>
						<span class="hb-tag"><?php echo esc_html( $rmdhost_a['banner_tag'] ); ?></span>
					<?php endif; ?>
					<p><?php echo esc_html( $rmdhost_a['banner_text'] ); ?></p>
					<?php if ( $rmdhost_a['banner_cta'] ) : ?>
						<a class="hb-link" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['banner_cta_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['banner_cta'] ); ?> <?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<?php endif; ?>
				</div>
				<?php if ( $rmdhost_a['rating_url'] ) : ?>
					<a class="hb-rating" href="<?php echo esc_url( $rmdhost_a['rating_url'] ); ?>" target="_blank" rel="noopener">
						<?php if ( $rmdhost_score ) : ?>
							<span class="hb-stars" style="--pct:<?php echo esc_attr( max( 0, min( 100, ( (float) $rmdhost_a['rating_score'] / $rmdhost_scale ) * 100 ) ) ); ?>%" aria-hidden="true"><i>★★★★★</i><i>★★★★★</i></span>
						<?php else : ?>
							<span class="hb-badge" aria-hidden="true"><?php echo rmdhost_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<?php endif; ?>
						<span class="hb-text">
							<?php if ( $rmdhost_score ) : ?>
								<b><?php echo esc_html( $rmdhost_a['rating_score'] ); ?></b><span class="hb-scale">/ <?php echo esc_html( $rmdhost_scale ); ?></span>
							<?php endif; ?>
							<span class="hb-label"><?php echo esc_html( $rmdhost_a['rating_label'] ); ?><?php echo $rmdhost_a['rating_count'] ? ' · ' . esc_html( sprintf( /* translators: %s: number of reviews */ __( '%s reviews', 'rmdhost' ), $rmdhost_a['rating_count'] ) ) : ''; ?></span>
							<?php if ( $rmdhost_a['rating_note'] ) : ?>
								<small><?php echo esc_html( $rmdhost_a['rating_note'] ); ?></small>
							<?php endif; ?>
						</span>
						<span class="hb-link"><?php echo esc_html( $rmdhost_a['rating_linktext'] ); ?> <?php echo rmdhost_icon( 'arrowUpRight' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
