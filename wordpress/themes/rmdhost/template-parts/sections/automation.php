<?php
/**
 * Automation flow + "More power" product carousel.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'automation' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a = $args;
?>
<section class="section dark auto-sec">
	<div class="glow glow-bottom" aria-hidden="true"><i></i><i></i></div>
	<div class="container">
		<?php
		echo rmdhost_section_head(
			array(
				'title' => $rmdhost_a['title'],
				'text'  => $rmdhost_a['text'],
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		?>
		<div class="auto" data-reveal>
			<div class="flow" aria-hidden="true">
				<div class="flow-grid"></div>
				<span class="flow-node n-a"><?php echo rmdhost_icon( 'refresh' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="flow-node n-b"><?php echo rmdhost_icon( 'globe' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="flow-node n-c"><?php echo rmdhost_icon( 'server' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="flow-node n-ai"><?php echo rmdhost_icon( 'spark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'AI Agent', 'rmdhost' ); ?></span>
				<span class="flow-node n-d"><?php echo rmdhost_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="flow-node n-e"><?php echo rmdhost_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<svg class="flow-lines" viewBox="0 0 400 300" preserveAspectRatio="none"><path d="M120 80 H190 M250 80 H300 M200 110 V170 M200 210 V240 H140 M200 240 H270"/></svg>
				<span class="flow-cursor"><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
			<div class="auto-copy">
				<h3 class="h3"><?php echo esc_html( $rmdhost_a['flow_title'] ); ?></h3>
				<p class="muted"><?php echo esc_html( $rmdhost_a['flow_text'] ); ?></p>
				<?php foreach ( (array) $rmdhost_a['links'] as $rmdhost_l ) : ?>
					<a class="row-link row-link-light" href="<?php echo esc_url( rmdhost_url( $rmdhost_l['url'] ) ); ?>"><?php echo esc_html( $rmdhost_l['label'] ); ?><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		get_template_part(
			'template-parts/components/product-carousel',
			null,
			array(
				'title' => $rmdhost_a['carousel_title'],
				'dark'  => true,
			)
		);
		?>
	</div>
</section>
