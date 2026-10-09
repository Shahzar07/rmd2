<?php
/**
 * Knowledge-base category grid with client-side search (search box lives in the hero).
 *
 * @package RMDHost
 * @var array $args (none).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_base = trailingslashit( RMDHost\Data::setting( 'client_area' ) ) . 'knowledgebase.php';
?>
<section class="section">
	<div class="container">
		<div class="kb-grid">
			<?php foreach ( RMDHost\Data::kb() as $rmdhost_c ) : ?>
				<div class="kb-cat" data-reveal>
					<h2 class="h4"><?php echo esc_html( $rmdhost_c['cat'] ); ?></h2>
					<ul>
						<?php foreach ( (array) $rmdhost_c['items'] as $rmdhost_item ) : ?>
							<?php
							$rmdhost_label = is_array( $rmdhost_item ) ? $rmdhost_item['title'] : $rmdhost_item;
							$rmdhost_href  = is_array( $rmdhost_item ) && ! empty( $rmdhost_item['url'] ) ? $rmdhost_item['url'] : add_query_arg( 'search', rawurlencode( $rmdhost_label ), $rmdhost_base );
							?>
							<li data-kb-item><a href="<?php echo esc_url( $rmdhost_href ); ?>"><?php echo rmdhost_icon( 'book' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $rmdhost_label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="center muted kb-empty" data-kb-empty hidden><?php esc_html_e( 'No articles match your search.', 'rmdhost' ); ?> <a href="<?php echo esc_url( home_url( '/support/' ) ); ?>"><?php esc_html_e( 'Ask our engineers', 'rmdhost' ); ?></a>.</p>
	</div>
</section>
