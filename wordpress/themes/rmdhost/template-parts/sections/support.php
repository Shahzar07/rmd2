<?php
/**
 * Dark support section with expanding cards.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'support' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a      = $args;
$rmdhost_active = min( 2, max( 0, count( (array) $rmdhost_a['cards'] ) - 1 ) );
$rmdhost_icons  = array( 'wrench', 'backup', 'globe' );
?>
<section class="section dark support-sec">
	<div class="glow" aria-hidden="true"><i></i><i></i></div>
	<div class="container">
		<div class="sup-head" data-reveal>
			<div>
				<p class="award"><?php echo rmdhost_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <span><?php echo rmdhost_kses_inline( $rmdhost_a['award'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></p>
				<h2 class="h2"><?php echo rmdhost_kses_inline( $rmdhost_a['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			</div>
			<div>
				<p class="muted"><?php echo esc_html( $rmdhost_a['text'] ); ?></p>
				<?php if ( $rmdhost_a['button'] ) : ?>
					<a class="chip-btn chip-outline" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['button_url'] ) ); ?>"><?php echo rmdhost_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $rmdhost_a['button'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<div class="xcards" data-xcards data-reveal>
			<?php foreach ( (array) $rmdhost_a['cards'] as $rmdhost_i => $rmdhost_c ) : ?>
				<button class="xcard <?php echo $rmdhost_i === $rmdhost_active ? 'active' : ''; ?>" type="button" aria-expanded="<?php echo $rmdhost_i === $rmdhost_active ? 'true' : 'false'; ?>">
					<span class="x-title"><?php echo esc_html( $rmdhost_c['title'] ); ?></span>
					<span class="x-body">
						<span class="x-text"><?php echo esc_html( $rmdhost_c['text'] ); ?></span>
						<span class="x-chat"><span class="bubble me"><?php echo rmdhost_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $rmdhost_c['q'] ); ?></span><span class="bubble them"><?php echo rmdhost_icon( 'headset' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $rmdhost_c['a'] ); ?></span></span>
					</span>
				</button>
			<?php endforeach; ?>
		</div>
		<div class="sup-more" data-reveal>
			<div>
				<h3 class="h3"><?php echo esc_html( $rmdhost_a['more_title'] ); ?></h3>
				<p class="muted"><?php echo esc_html( $rmdhost_a['more_text'] ); ?></p>
				<?php if ( $rmdhost_a['more_link'] ) : ?>
					<a class="row-link row-link-light" href="<?php echo esc_url( rmdhost_url( $rmdhost_a['more_url'] ) ); ?>"><?php echo esc_html( $rmdhost_a['more_link'] ); ?><?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				<?php endif; ?>
			</div>
			<div class="stack-cards">
				<?php if ( $rmdhost_a['image'] ) : ?>
					<span class="stack-photo"><?php echo rmdhost_image( $rmdhost_a['image'], __( 'Support engineer wearing a headset', 'rmdhost' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php endif; ?>
				<?php foreach ( array_values( (array) $rmdhost_a['chips'] ) as $rmdhost_i => $rmdhost_chip ) : ?>
					<span class="sc sc<?php echo (int) $rmdhost_i + 1; ?>"><?php echo rmdhost_icon( $rmdhost_icons[ $rmdhost_i % 3 ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $rmdhost_chip ); ?>
						<?php if ( 2 === $rmdhost_i ) : ?>
							<span class="sc-bar">
							<?php
							foreach ( array( __( 'Tickets', 'rmdhost' ), __( 'Chat', 'rmdhost' ), __( 'Status', 'rmdhost' ), __( 'Docs', 'rmdhost' ), __( 'Billing', 'rmdhost' ) ) as $rmdhost_j => $rmdhost_x ) :
								?>
								<i class="<?php echo 1 === $rmdhost_j ? 'on' : ''; ?>"><?php echo esc_html( $rmdhost_x ); ?></i><?php endforeach; ?></span>
						<?php endif; ?>
					</span>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
