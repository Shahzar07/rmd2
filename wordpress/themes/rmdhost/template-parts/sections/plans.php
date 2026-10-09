<?php
/**
 * Plan grid for one product group (plus instant/dedicated tables when the group has them).
 *
 * @package RMDHost
 * @var array $args See rmdhost_section_defaults( 'plans' ).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a     = $args;
$rmdhost_group = RMDHost\Data::group( $rmdhost_a['group'] );
if ( ! $rmdhost_group ) {
	return;
}
$rmdhost_plans = RMDHost\Data::plans( $rmdhost_a['group'] );
/* translators: %s: short product name, e.g. "VPS". */
$rmdhost_title = $rmdhost_a['title'] ? $rmdhost_a['title'] : sprintf( __( 'Choose your %s plan', 'rmdhost' ), $rmdhost_group['short'] ?? $rmdhost_group['name'] );
$rmdhost_text  = $rmdhost_a['text'] ? $rmdhost_a['text'] : ( $rmdhost_group['comingSoon'] ?? '' );
?>
<section class="section"<?php echo $rmdhost_a['id'] ? ' id="' . esc_attr( $rmdhost_a['id'] ) . '"' : ''; ?>>
	<div class="container">
		<?php
		echo rmdhost_section_head(
			array(
				'title' => $rmdhost_title,
				'text'  => $rmdhost_text,
			)
		); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		?>
		<?php if ( ! empty( $rmdhost_group['instant'] ) && ! empty( $rmdhost_group['servers'] ) ) : ?>
			<?php get_template_part( 'template-parts/components/server-table', 'instant', array( 'group' => $rmdhost_group ) ); ?>
		<?php elseif ( $rmdhost_plans ) : ?>
			<div class="plans plans-<?php echo (int) count( $rmdhost_plans ); ?>">
				<?php
				foreach ( $rmdhost_plans as $rmdhost_plan ) {
					echo rmdhost_plan_card( $rmdhost_plan, array( 'product' => $rmdhost_group['name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $rmdhost_group['table']['rows'] ) ) : ?>
			<?php get_template_part( 'template-parts/components/server-table', 'dedicated', array( 'table' => $rmdhost_group['table'] ) ); ?>
		<?php endif; ?>
		<?php if ( $rmdhost_a['every'] && ! empty( $rmdhost_group['everyPlan'] ) ) : ?>
			<div class="every" data-reveal>
				<p class="every-title"><?php echo wp_kses( __( 'Every plan has <u>everything you need</u> and more', 'rmdhost' ), array( 'u' => array() ) ); ?></p>
				<?php echo rmdhost_checklist( $rmdhost_group['everyPlan'], 'checks-3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $rmdhost_group['review'] ) && current_user_can( 'edit_posts' ) ) : ?>
			<p class="to-confirm"><em class="pill pill-xs"><?php esc_html_e( 'To confirm', 'rmdhost' ); ?></em> <?php echo rmdhost_kses_inline( $rmdhost_group['review'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
		<?php if ( $rmdhost_a['fine'] ) : ?>
			<p class="fine"><?php echo esc_html( $rmdhost_a['fine'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
