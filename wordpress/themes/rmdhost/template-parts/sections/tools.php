<?php
/**
 * Feature tabs with animated scenes + "more control" cards.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'tools' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a   = $args;
$rmdhost_uid = wp_unique_id( 'tools-' );
?>
<section class="section">
	<div class="container">
		<?php
		echo rmdhost_section_head(
			array(
				'title' => $rmdhost_a['title'],
				'text'  => $rmdhost_a['text'],
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		?>
		<div class="tabs" data-tabs data-reveal>
			<div class="tablist" role="tablist" aria-label="<?php esc_attr_e( 'What you can do', 'rmdhost' ); ?>">
				<span class="tab-ind" aria-hidden="true"></span>
				<?php foreach ( $rmdhost_a['tabs'] as $rmdhost_i => $rmdhost_t ) : ?>
					<button role="tab" id="<?php echo esc_attr( "$rmdhost_uid-tab-$rmdhost_i" ); ?>" aria-controls="<?php echo esc_attr( "$rmdhost_uid-panel-$rmdhost_i" ); ?>" aria-selected="<?php echo 0 === $rmdhost_i ? 'true' : 'false'; ?>" tabindex="<?php echo 0 === $rmdhost_i ? '0' : '-1'; ?>"><?php echo esc_html( $rmdhost_t['label'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $rmdhost_a['tabs'] as $rmdhost_i => $rmdhost_t ) : ?>
				<div class="tabpanel tool-panel" role="tabpanel" id="<?php echo esc_attr( "$rmdhost_uid-panel-$rmdhost_i" ); ?>" aria-labelledby="<?php echo esc_attr( "$rmdhost_uid-tab-$rmdhost_i" ); ?>" <?php echo $rmdhost_i ? 'hidden' : ''; ?>>
					<div class="tool-visual"><?php echo rmdhost_scene( $rmdhost_t['scene'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div class="tool-copy">
						<span class="tool-ico"><?php echo rmdhost_icon( $rmdhost_t['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3 class="h3"><?php echo esc_html( $rmdhost_t['title'] ); ?></h3>
						<p class="muted"><?php echo esc_html( $rmdhost_t['text'] ); ?></p>
						<?php if ( ! empty( $rmdhost_t['link'] ) ) : ?>
							<a class="row-link" href="<?php echo esc_url( rmdhost_url( $rmdhost_t['url'] ) ); ?>"><?php echo esc_html( $rmdhost_t['link'] ); ?><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<?php endif; ?>
						<?php if ( $rmdhost_a['social'] ) : ?>
							<div class="avatars"><span>R</span><span>M</span><span>D</span><span>H</span><b><?php echo esc_html( $rmdhost_a['social_num'] ); ?></b><small><?php echo esc_html( $rmdhost_a['social'] ); ?><br><?php echo esc_html( get_bloginfo( 'name' ) ); ?></small></div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( ! empty( $rmdhost_a['cards'] ) ) : ?>
			<h3 class="sub-h" data-reveal><?php echo esc_html( $rmdhost_a['sub_title'] ); ?></h3>
			<div class="duo">
				<?php foreach ( $rmdhost_a['cards'] as $rmdhost_c ) : ?>
					<a class="soft-card" href="<?php echo esc_url( rmdhost_url( $rmdhost_c['url'] ) ); ?>" data-reveal data-spot><?php echo rmdhost_icon( $rmdhost_c['icon'] ) . rmdhost_icon( 'arrowUpRight', 'corner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><strong><?php echo esc_html( $rmdhost_c['title'] ); ?></strong><span><?php echo esc_html( $rmdhost_c['text'] ); ?></span></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
