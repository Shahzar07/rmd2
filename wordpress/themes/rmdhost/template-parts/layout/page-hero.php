<?php
/**
 * Dark page hero (inner pages).
 *
 * @package RMDHost
 * @var array $args eyebrow, title, lede, actions, visual, crumbs, small.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_args = wp_parse_args(
	$args,
	array(
		'eyebrow' => '',
		'title'   => '',
		'lede'    => '',
		'actions' => '',
		'visual'  => '',
		'crumbs'  => rmdhost_current_trail(),
		'small'   => false,
		'class'   => '',
	)
);
?>
<section class="hero hero-page <?php echo $rmdhost_args['small'] ? 'hero-sm ' : ''; ?><?php echo esc_attr( $rmdhost_args['class'] ); ?> dark">
	<div class="glow" aria-hidden="true"><i></i><i></i><i></i></div>
	<div class="grid-bg" aria-hidden="true"></div>
	<div class="container <?php echo $rmdhost_args['visual'] ? 'hero-split' : 'hero-center'; ?>">
		<div class="hero-copy">
			<?php echo rmdhost_breadcrumbs( $rmdhost_args['crumbs'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
			<?php if ( $rmdhost_args['eyebrow'] ) : ?>
				<p class="eyebrow eyebrow-hero" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_args['eyebrow'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<h1 class="h1" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_args['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php if ( $rmdhost_args['lede'] ) : ?>
				<p class="lead" data-reveal><?php echo rmdhost_kses_inline( $rmdhost_args['lede'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<?php echo $rmdhost_args['actions']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built by templates from escaped parts. ?>
		</div>
		<?php if ( $rmdhost_args['visual'] ) : ?>
			<div class="hero-visual" data-reveal><?php echo $rmdhost_args['visual']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static scene markup. ?></div>
		<?php endif; ?>
	</div>
</section>
