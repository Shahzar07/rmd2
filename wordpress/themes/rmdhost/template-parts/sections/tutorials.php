<?php
/**
 * Filterable tutorial cards.
 *
 * @package RMDHost
 * @var array $args (none).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_tuts = RMDHost\Data::tutorials();
$rmdhost_tags = array_values( array_unique( wp_list_pluck( $rmdhost_tuts, 'tag' ) ) );
$rmdhost_base = trailingslashit( RMDHost\Data::setting( 'client_area' ) ) . 'knowledgebase.php';
?>
<section class="section">
	<div class="container">
		<div class="filters" data-filter-group data-target=".tut" role="group" aria-label="<?php esc_attr_e( 'Filter tutorials', 'rmdhost' ); ?>" data-reveal>
			<button type="button" class="chip on" data-filter="*" aria-pressed="true"><?php esc_html_e( 'All', 'rmdhost' ); ?></button>
			<?php foreach ( $rmdhost_tags as $rmdhost_t ) : ?>
				<button type="button" class="chip" data-filter="<?php echo esc_attr( $rmdhost_t ); ?>" aria-pressed="false"><?php echo esc_html( $rmdhost_t ); ?></button>
			<?php endforeach; ?>
		</div>
		<div class="tut-grid">
			<?php foreach ( $rmdhost_tuts as $rmdhost_t ) : ?>
				<a class="tut" data-family="<?php echo esc_attr( $rmdhost_t['tag'] ); ?>" href="<?php echo esc_url( ! empty( $rmdhost_t['url'] ) ? $rmdhost_t['url'] : add_query_arg( 'search', rawurlencode( $rmdhost_t['title'] ), $rmdhost_base ) ); ?>" data-reveal data-spot>
					<span class="tut-top"><em class="pill pill-xs"><?php echo esc_html( $rmdhost_t['tag'] ); ?></em><small><?php echo esc_html( $rmdhost_t['level'] . ' · ' . $rmdhost_t['time'] ); ?></small></span>
					<strong><?php echo esc_html( $rmdhost_t['title'] ); ?></strong>
					<span class="link-arrow"><?php esc_html_e( 'Start tutorial', 'rmdhost' ); ?> <?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
