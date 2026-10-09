<?php
/**
 * Dark product hero with checklist, scene visual and sticky sub-navigation.
 *
 * @package RMDHost
 * @var array $args group, title, lede, eyebrow, subnav (bool).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a     = $args;
$rmdhost_group = RMDHost\Data::group( $rmdhost_a['group'] );
if ( ! $rmdhost_group ) {
	return;
}
$rmdhost_from  = RMDHost\Data::from_price( $rmdhost_a['group'] );
$rmdhost_title = $rmdhost_a['title'] ? $rmdhost_a['title'] : ( $rmdhost_group['h1'] ?? $rmdhost_group['name'] );
$rmdhost_lede  = $rmdhost_a['lede'] ? $rmdhost_a['lede'] : ( $rmdhost_group['lede'] ?? '' );
$rmdhost_eye   = $rmdhost_a['eyebrow'] ? $rmdhost_a['eyebrow'] : ( $rmdhost_group['eyebrow'] ?? '' );
$rmdhost_crumb = array( array( __( 'Home', 'rmdhost' ), home_url( '/' ) ), array( $rmdhost_group['name'], '' ) );
?>
<section class="hero hero-page hero-product dark">
	<div class="glow" aria-hidden="true"><i></i><i></i><i></i></div>
	<div class="grid-bg" aria-hidden="true"></div>
	<div class="container hero-split">
		<div class="hero-copy">
			<?php echo rmdhost_breadcrumbs( $rmdhost_crumb ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<p class="eyebrow eyebrow-hero" data-reveal><?php echo esc_html( $rmdhost_eye ); ?>
			<?php
			if ( null !== $rmdhost_from ) :
				?>
				· <?php esc_html_e( 'from', 'rmdhost' ); ?> <b><?php echo rmdhost_money( $rmdhost_from ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b><?php endif; ?>
				<?php
				if ( ! empty( $rmdhost_group['tag'] ) ) :
					?>
				<em class="pill pill-xs pill-invert"><?php echo esc_html( $rmdhost_group['tag'] ); ?></em><?php endif; ?></p>
			<h1 class="h1" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<p class="lead" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_lede ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<div data-reveal><?php echo rmdhost_checklist( $rmdhost_group['checklist'] ?? array(), 'checks-hero' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<div class="hero-cta left" data-reveal>
				<a class="btn btn-white btn-lg" href="#plans"><?php echo null !== $rmdhost_from ? esc_html__( 'Choose plan', 'rmdhost' ) : esc_html__( 'See specifications', 'rmdhost' ); ?></a>
				<?php if ( null === $rmdhost_from ) : ?>
					<a class="btn btn-ghost-light btn-lg" href="<?php echo esc_url( add_query_arg( 'topic', rawurlencode( $rmdhost_group['name'] ), home_url( '/support/' ) ) ); ?>"><?php esc_html_e( 'Contact sales', 'rmdhost' ); ?></a>
				<?php endif; ?>
			</div>
			<p class="hero-note left" data-reveal><?php echo rmdhost_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'DDoS protection included · 24/7 expert support', 'rmdhost' ); ?></p>
		</div>
		<div class="hero-visual" data-reveal><?php echo rmdhost_scene( $rmdhost_group['visual'] ?? 'dashboard' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static scene markup. ?></div>
	</div>
</section>
<?php if ( ! isset( $rmdhost_a['subnav'] ) || $rmdhost_a['subnav'] ) : ?>
<div class="subnav" data-subnav>
	<div class="container"><nav class="subnav-pill" aria-label="<?php esc_attr_e( 'On this page', 'rmdhost' ); ?>">
		<a href="#plans" class="on"><?php esc_html_e( 'Pricing', 'rmdhost' ); ?></a><a href="#features"><?php esc_html_e( 'Features', 'rmdhost' ); ?></a><a href="#os"><?php esc_html_e( 'OS &amp; apps', 'rmdhost' ); ?></a><a href="#locations"><?php esc_html_e( 'Locations', 'rmdhost' ); ?></a><a href="#faq"><?php esc_html_e( 'FAQ', 'rmdhost' ); ?></a>
	</nav></div>
</div>
<?php endif; ?>
