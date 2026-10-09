<?php
/**
 * Feature bento grid for a product group.
 *
 * @package RMDHost
 * @var array $args group, title, photo, features (optional explicit list), id, soft.
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_a      = $args;
$rmdhost_group  = RMDHost\Data::group( $rmdhost_a['group'] );
$rmdhost_custom = ! empty( $rmdhost_a['features'] );
$rmdhost_feats  = $rmdhost_custom ? $rmdhost_a['features'] : (array) ( $rmdhost_group['features'] ?? array() );
if ( ! $rmdhost_feats ) {
	return;
}
$rmdhost_photos = array(
	'vps'                           => 'server-rack',
	'windows-vps'                   => 'windows-trader',
	'ssd-vps'                       => 'typing-hands',
	'cloud-vps'                     => 'datacentre-aisle',
	'owncloud-storage'              => 'photographer',
	'ai-vps'                        => 'founder',
	'macos-vps'                     => 'dev-laptop',
	'dedicated-servers'             => 'server-rack',
	'instant-dedicated-servers-usa' => 'datacentre-building',
	'macos-dedicated-servers'       => 'dev-laptop',
	'game-servers'                  => 'gamer',
);
$rmdhost_photo  = $rmdhost_a['photo'] ? $rmdhost_a['photo'] : ( $rmdhost_custom ? '' : ( $rmdhost_photos[ $rmdhost_a['group'] ] ?? '' ) );
/* translators: %s: product name. */
$rmdhost_title = $rmdhost_a['title'] ? $rmdhost_a['title'] : ( $rmdhost_custom ? '' : sprintf( __( 'Secure, speedy, reliable %s', 'rmdhost' ), $rmdhost_group['name'] ?? '' ) );
$rmdhost_count = count( $rmdhost_feats );
?>
<section class="section <?php echo ( ! isset( $rmdhost_a['soft'] ) || $rmdhost_a['soft'] ) ? 'section-soft' : ''; ?>"<?php echo $rmdhost_a['id'] ? ' id="' . esc_attr( $rmdhost_a['id'] ) . '"' : ''; ?>>
	<div class="container">
		<?php if ( ! empty( $rmdhost_a['banner'] ) ) : ?>
			<figure class="page-photo" data-reveal><?php echo rmdhost_image( $rmdhost_a['banner'], $rmdhost_a['banner_alt'] ?? '', array( 'sizes' => '100vw' ) ); ?>
			<?php
			if ( ! empty( $rmdhost_a['banner_caption'] ) ) :
				?>
				<figcaption><?php echo esc_html( $rmdhost_a['banner_caption'] ); ?></figcaption><?php endif; ?></figure>
		<?php endif; ?>
		<?php echo rmdhost_section_head( array( 'title' => $rmdhost_title ) ); ?>
		<div class="bento">
			<?php foreach ( array_values( $rmdhost_feats ) as $rmdhost_i => $rmdhost_f ) : ?>
				<?php
				$rmdhost_cls = array( 'bento-card' );
				if ( 0 === $rmdhost_i || ( 5 === $rmdhost_i && 6 === $rmdhost_count ) ) {
					$rmdhost_cls[] = 'wide';
				}
				if ( 0 === $rmdhost_i && $rmdhost_photo ) {
					$rmdhost_cls[] = 'has-photo';
				}
				?>
				<div class="<?php echo esc_attr( implode( ' ', $rmdhost_cls ) ); ?>" data-reveal data-spot>
					<?php if ( 0 === $rmdhost_i && $rmdhost_photo ) : ?>
						<span class="bento-photo"><?php echo rmdhost_image( $rmdhost_photo, '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<?php endif; ?>
					<span class="b-ico"><?php echo rmdhost_icon( $rmdhost_f['icon'] ?? 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<h3><?php echo esc_html( $rmdhost_f['title'] ); ?></h3>
					<p><?php echo esc_html( $rmdhost_f['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
