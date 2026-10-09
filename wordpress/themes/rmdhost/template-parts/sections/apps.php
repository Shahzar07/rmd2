<?php
/**
 * One-click deployment: applications / operating systems / use cases tabs + marquee.
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'apps' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a     = $args;
$rmdhost_group = RMDHost\Data::group( $rmdhost_a['group'] );
if ( ! $rmdhost_group ) {
	return;
}
$rmdhost_uid   = wp_unique_id( 'os-' );
$rmdhost_stack = (array) ( $rmdhost_group['stack'] ?? array() );
$rmdhost_os    = (array) ( $rmdhost_group['os'] ?? array() );
$rmdhost_tabs  = array(
	array( __( 'Applications', 'rmdhost' ), $rmdhost_stack, false ),
	array( __( 'Operating systems', 'rmdhost' ), $rmdhost_os, false ),
	array( __( 'Use cases', 'rmdhost' ), (array) ( $rmdhost_group['useCases'] ?? array() ), true ),
);
?>
<section class="section pt-0"<?php echo $rmdhost_a['id'] ? ' id="' . esc_attr( $rmdhost_a['id'] ) . '"' : ''; ?>>
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
			<div class="tablist" role="tablist">
				<span class="tab-ind" aria-hidden="true"></span>
				<?php foreach ( $rmdhost_tabs as $rmdhost_i => $rmdhost_t ) : ?>
					<button role="tab" id="<?php echo esc_attr( "$rmdhost_uid-t$rmdhost_i" ); ?>" aria-controls="<?php echo esc_attr( "$rmdhost_uid-p$rmdhost_i" ); ?>" aria-selected="<?php echo $rmdhost_i ? 'false' : 'true'; ?>"<?php echo $rmdhost_i ? ' tabindex="-1"' : ''; ?>><?php echo esc_html( $rmdhost_t[0] ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $rmdhost_tabs as $rmdhost_i => $rmdhost_t ) : ?>
				<div class="tabpanel" role="tabpanel" id="<?php echo esc_attr( "$rmdhost_uid-p$rmdhost_i" ); ?>" aria-labelledby="<?php echo esc_attr( "$rmdhost_uid-t$rmdhost_i" ); ?>"<?php echo $rmdhost_i ? ' hidden' : ''; ?>>
					<div class="app-grid">
						<?php foreach ( $rmdhost_t[1] as $rmdhost_s ) : ?>
							<?php if ( $rmdhost_t[2] ) : ?>
								<span class="app"><b><?php echo rmdhost_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b><?php echo esc_html( $rmdhost_s ); ?></span>
							<?php else : ?>
								<span class="app"><b><?php echo esc_html( mb_substr( $rmdhost_s, 0, 1 ) ); ?></b><?php echo esc_html( $rmdhost_s ); ?><?php echo rmdhost_icon( 'arrowUpRight', 'corner' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="marquee" aria-hidden="true"><div class="marquee-track">
			<?php foreach ( array_merge( $rmdhost_os, $rmdhost_stack, $rmdhost_os, $rmdhost_stack ) as $rmdhost_s ) : ?>
				<span><?php echo esc_html( $rmdhost_s ); ?></span>
			<?php endforeach; ?>
		</div></div>
	</div>
</section>
