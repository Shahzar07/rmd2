<?php
/**
 * Data-centre world map, Europe zoom, numbered list and stats.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'locations' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a                          = $args;
$rmdhost_locations                  = array_values( RMDHost\Data::locations() );
list( $rmdhost_world, $rmdhost_eu ) = rmdhost_maps( $rmdhost_locations );
?>
<section class="section"<?php echo ! empty( $rmdhost_a['id'] ) ? ' id="' . esc_attr( $rmdhost_a['id'] ) . '"' : ''; ?>>
	<div class="container">
		<?php if ( ! empty( $rmdhost_a['photo'] ) ) : ?>
			<figure class="page-photo" data-reveal><?php echo rmdhost_image( $rmdhost_a['photo'], $rmdhost_a['photo_alt'] ?? '', array( 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php
			if ( ! empty( $rmdhost_a['caption'] ) ) :
				?>
				<figcaption><?php echo esc_html( $rmdhost_a['caption'] ); ?></figcaption><?php endif; ?></figure>
		<?php endif; ?>
		<div class="dark-panel dark">
			<div class="dc" data-reveal data-dc>
				<div class="dc-head">
					<div>
						<p class="kicker"><?php echo esc_html( $rmdhost_a['kicker'] ); ?></p>
						<h3 class="h3"><?php echo esc_html( $rmdhost_a['title'] ); ?></h3>
					</div>
					<p class="muted"><?php echo esc_html( $rmdhost_a['text'] ); ?></p>
					<?php if ( $rmdhost_a['button'] ) : ?>
						<a class="btn btn-white" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['button_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['button'] ); ?></a>
					<?php endif; ?>
				</div>
				<div class="dc-maps">
					<figure class="dc-world"><figcaption><?php esc_html_e( 'WORLD', 'rmdhost' ); ?></figcaption><?php echo $rmdhost_world; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built with escaping in rmdhost_maps(). ?></figure>
					<figure class="dc-eu"><figcaption><?php esc_html_e( 'EUROPE · ZOOM', 'rmdhost' ); ?></figcaption><?php echo $rmdhost_eu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></figure>
				</div>
				<ol class="dc-list">
					<?php foreach ( $rmdhost_locations as $rmdhost_i => $rmdhost_l ) : ?>
						<li data-loc="<?php echo (int) $rmdhost_i; ?>" tabindex="0">
							<span class="dc-n"><?php echo esc_html( str_pad( (string) ( $rmdhost_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span class="dc-city"><b><?php echo esc_html( $rmdhost_l['city'] ); ?></b><small><?php echo esc_html( $rmdhost_l['country'] ); ?></small></span>
							<span class="dc-code"><?php echo esc_html( $rmdhost_l['code'] ); ?></span>
							<span class="dc-status"><i class="led"></i><?php echo esc_html( $rmdhost_a['status'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
		<?php if ( ! empty( $rmdhost_a['stats'] ) ) : ?>
			<div class="stats" data-reveal>
				<?php foreach ( RMDHost\Data::stats() as $rmdhost_s ) : ?>
					<div class="stat"><b><span data-count="<?php echo esc_attr( $rmdhost_s['value'] ); ?>" data-decimals="<?php echo esc_attr( $rmdhost_s['decimals'] ?? 0 ); ?>">0</span><?php echo esc_html( $rmdhost_s['suffix'] ?? '' ); ?></b><span><?php echo esc_html( $rmdhost_s['label'] ); ?></span></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
