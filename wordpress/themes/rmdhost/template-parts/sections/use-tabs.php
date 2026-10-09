<?php
/**
 * "What you can run on X" tabs (only for groups that define them).
 *
 * @package RMDHost
 * @var array $args group.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_group = RMDHost\Data::group( $args['group'] ?? '' );
if ( empty( $rmdhost_group['tabs'] ) ) {
	return;
}
$rmdhost_uid = wp_unique_id( 'ut-' );
?>
<section class="section">
	<div class="container">
		<?php /* translators: %s: product name. */ echo rmdhost_section_head( array( 'title' => sprintf( __( 'What you can run on %s', 'rmdhost' ), $rmdhost_group['name'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="tabs" data-tabs data-reveal>
			<div class="tablist tablist-center" role="tablist">
				<span class="tab-ind" aria-hidden="true"></span>
				<?php foreach ( array_values( $rmdhost_group['tabs'] ) as $rmdhost_i => $rmdhost_t ) : ?>
					<button role="tab" id="<?php echo esc_attr( "$rmdhost_uid-t$rmdhost_i" ); ?>" aria-controls="<?php echo esc_attr( "$rmdhost_uid-p$rmdhost_i" ); ?>" aria-selected="<?php echo $rmdhost_i ? 'false' : 'true'; ?>" tabindex="<?php echo $rmdhost_i ? '-1' : '0'; ?>"><?php echo esc_html( $rmdhost_t['label'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( array_values( $rmdhost_group['tabs'] ) as $rmdhost_i => $rmdhost_t ) : ?>
				<div class="tabpanel use-panel" role="tabpanel" id="<?php echo esc_attr( "$rmdhost_uid-p$rmdhost_i" ); ?>" aria-labelledby="<?php echo esc_attr( "$rmdhost_uid-t$rmdhost_i" ); ?>"<?php echo $rmdhost_i ? ' hidden' : ''; ?>><p class="lead"><?php echo esc_html( $rmdhost_t['text'] ); ?></p></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
