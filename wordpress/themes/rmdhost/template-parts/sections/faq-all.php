<?php
/**
 * FAQ page: general questions followed by every product group's questions.
 *
 * @package RMDHost
 * @var array $args (none).
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section">
	<div class="container narrow">
		<h2 class="h3 faq-h"><?php esc_html_e( 'General', 'rmdhost' ); ?></h2>
		<?php echo rmdhost_faq_list( RMDHost\Data::faqs() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php foreach ( RMDHost\Data::groups() as $rmdhost_slug => $rmdhost_g ) : ?>
			<?php
			$rmdhost_faqs = RMDHost\Data::faqs( $rmdhost_slug );
			if ( ! $rmdhost_faqs ) {
				continue;
			}
			?>
			<h2 class="h3 faq-h" id="faq-<?php echo esc_attr( $rmdhost_slug ); ?>"><?php echo esc_html( $rmdhost_g['name'] ); ?></h2>
			<?php echo rmdhost_faq_list( $rmdhost_faqs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endforeach; ?>
	</div>
</section>
