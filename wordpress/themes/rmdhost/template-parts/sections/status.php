<?php
/**
 * Network status board.
 *
 * @package RMDHost
 * @var array $args title, maintenance, note.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_services = RMDHost\Data::status_services();
$rmdhost_groups   = array_unique( wp_list_pluck( $rmdhost_services, 'group' ) );
?>
<section class="section">
	<div class="container narrow">
		<div class="status-banner" data-reveal><span class="status-dot big"></span><div><b><?php echo esc_html( $args['title'] ?? __( 'All systems operational', 'rmdhost' ) ); ?></b><small><?php esc_html_e( 'Last checked', 'rmdhost' ); ?> <span data-now><?php esc_html_e( 'just now', 'rmdhost' ); ?></span></small></div></div>
		<?php foreach ( $rmdhost_groups as $rmdhost_g ) : ?>
			<div class="status-group" data-reveal>
				<h2 class="h4"><?php echo esc_html( $rmdhost_g ); ?></h2>
				<?php foreach ( $rmdhost_services as $rmdhost_s ) : ?>
					<?php
					if ( $rmdhost_s['group'] !== $rmdhost_g ) {
						continue;
					}
					?>
					<div class="status-row"><span><?php echo esc_html( $rmdhost_s['name'] ); ?></span><span class="uptime" aria-hidden="true"><?php echo str_repeat( '<i></i>', 45 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span class="status-dot"><?php echo esc_html( $rmdhost_s['status'] ?? __( 'Operational', 'rmdhost' ) ); ?></span></div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
		<div class="status-group" data-reveal>
			<h2 class="h4"><?php esc_html_e( 'Scheduled maintenance', 'rmdhost' ); ?></h2>
			<p class="muted"><?php echo esc_html( $args['maintenance'] ?? __( 'No maintenance is currently scheduled. Planned work is announced at least 72 hours in advance by email.', 'rmdhost' ) ); ?></p>
		</div>
	</div>
</section>
