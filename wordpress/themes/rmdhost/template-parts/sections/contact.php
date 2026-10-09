<?php
/**
 * Contact routes + contact form. The form posts to admin-ajax via the RMDHost Core
 * plugin; without the plugin it falls back to a mailto: form.
 *
 * @package RMDHost
 * @var array $args photo, title, text.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_email = RMDHost\Data::setting( 'email' );
$rmdhost_sales = RMDHost\Data::setting( 'sales_email' );
$rmdhost_ajax  = defined( 'RMDHOST_CORE_VERSION' );
$rmdhost_topic = isset( $_GET['topic'] ) ? sanitize_text_field( wp_unslash( $_GET['topic'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only preselect.
$rmdhost_opts  = array_merge( array( __( 'Sales question', 'rmdhost' ), __( 'Technical support', 'rmdhost' ), __( 'Billing', 'rmdhost' ) ), array_values( RMDHost\Data::group_choices() ) );
?>
<section class="section">
	<div class="container">
		<?php if ( ! empty( $args['photo'] ) ) : ?>
			<figure class="page-photo" data-reveal><?php echo rmdhost_image( $args['photo'], __( 'Support engineer wearing a headset', 'rmdhost' ), array( 'sizes' => '100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><figcaption><?php esc_html_e( 'Every ticket answered by an engineer – 24/7/365.', 'rmdhost' ); ?></figcaption></figure>
		<?php endif; ?>
		<div class="three">
			<a class="soft-card" href="<?php echo esc_url( RMDHost\Data::setting( 'ticket_url' ) ); ?>" data-reveal data-spot><?php echo rmdhost_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><strong><?php esc_html_e( 'Open a ticket', 'rmdhost' ); ?></strong><span><?php esc_html_e( 'Fastest route for technical issues. Average first reply in minutes.', 'rmdhost' ); ?></span></a>
			<a class="soft-card" href="<?php echo esc_url( 'mailto:' . $rmdhost_email ); ?>" data-reveal data-spot><?php echo rmdhost_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><strong><?php echo esc_html( $rmdhost_email ); ?></strong><span><?php esc_html_e( 'Technical support and account questions.', 'rmdhost' ); ?></span></a>
			<a class="soft-card" href="<?php echo esc_url( 'mailto:' . $rmdhost_sales ); ?>" data-reveal data-spot><?php echo rmdhost_icon( 'card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><strong><?php echo esc_html( $rmdhost_sales ); ?></strong><span><?php esc_html_e( 'Custom builds, AI and macOS servers, volume pricing.', 'rmdhost' ); ?></span></a>
		</div>
		<div class="contact" data-reveal>
			<div>
				<h2 class="h3"><?php echo esc_html( $args['title'] ?? __( 'Send us a message', 'rmdhost' ) ); ?></h2>
				<p class="muted"><?php echo esc_html( $args['text'] ?? __( 'Tell us what you need and we’ll get back to you quickly. Existing customers: please use the client area so we can verify your account.', 'rmdhost' ) ); ?></p>
				<?php echo rmdhost_checklist( array( __( '24/7/365 availability', 'rmdhost' ), __( 'Free migration assistance', 'rmdhost' ), __( 'Custom hardware quotes', 'rmdhost' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php if ( $rmdhost_ajax ) : ?>
			<form class="form" data-contact data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="rmdhost_contact">
				<?php wp_nonce_field( 'rmdhost_contact', 'rmd_nonce' ); ?>
				<p class="hp" aria-hidden="true"><label><?php esc_html_e( 'Leave empty', 'rmdhost' ); ?><input name="website" tabindex="-1" autocomplete="off"></label></p>
			<?php else : ?>
			<form class="form" data-contact action="<?php echo esc_url( 'mailto:' . $rmdhost_sales ); ?>" method="post" enctype="text/plain">
			<?php endif; ?>
				<?php $rmdhost_fid = wp_unique_id( 'c-' ); ?>
				<div class="field"><label for="<?php echo esc_attr( $rmdhost_fid ); ?>-name"><?php esc_html_e( 'Name', 'rmdhost' ); ?></label><input id="<?php echo esc_attr( $rmdhost_fid ); ?>-name" name="name" required autocomplete="name"></div>
				<div class="field"><label for="<?php echo esc_attr( $rmdhost_fid ); ?>-email"><?php esc_html_e( 'Email', 'rmdhost' ); ?></label><input id="<?php echo esc_attr( $rmdhost_fid ); ?>-email" name="email" type="email" required autocomplete="email"></div>
				<div class="field"><label for="<?php echo esc_attr( $rmdhost_fid ); ?>-topic"><?php esc_html_e( 'Topic', 'rmdhost' ); ?></label><select id="<?php echo esc_attr( $rmdhost_fid ); ?>-topic" name="topic" data-topic>
					<?php foreach ( $rmdhost_opts as $rmdhost_o ) : ?>
						<option<?php selected( $rmdhost_topic, $rmdhost_o ); ?>><?php echo esc_html( $rmdhost_o ); ?></option>
					<?php endforeach; ?>
					<?php if ( $rmdhost_topic && ! in_array( $rmdhost_topic, $rmdhost_opts, true ) ) : ?>
						<option selected><?php echo esc_html( $rmdhost_topic ); ?></option>
					<?php endif; ?>
				</select></div>
				<div class="field"><label for="<?php echo esc_attr( $rmdhost_fid ); ?>-msg"><?php esc_html_e( 'Message', 'rmdhost' ); ?></label><textarea id="<?php echo esc_attr( $rmdhost_fid ); ?>-msg" name="message" rows="5" required></textarea></div>
				<button class="btn btn-primary" type="submit"><?php esc_html_e( 'Send message', 'rmdhost' ); ?> <?php echo rmdhost_icon( 'send' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				<p class="form-status" role="status" aria-live="polite" hidden></p>
				<p class="fine left">
					<?php
					$rmdhost_privacy = get_privacy_policy_url();
					/* translators: %s: privacy policy link. */
					printf( esc_html__( 'By sending this form you agree to our %s.', 'rmdhost' ), $rmdhost_privacy ? '<a href="' . esc_url( $rmdhost_privacy ) . '">' . esc_html__( 'Privacy policy', 'rmdhost' ) . '</a>' : esc_html__( 'Privacy policy', 'rmdhost' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</p>
			</form>
		</div>
	</div>
</section>
