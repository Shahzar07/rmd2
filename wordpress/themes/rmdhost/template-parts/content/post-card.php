<?php
/**
 * Post card used by the blog, archives and search.
 *
 * @package RMDHost
 * @var array $args lead (bool), index (int).
 */

defined( 'ABSPATH' ) || exit;

$rmdhost_cats = get_the_category();
$rmdhost_tag  = $rmdhost_cats ? $rmdhost_cats[0]->name : get_post_type_object( get_post_type() )->labels->singular_name;
$rmdhost_icon = array( 'rack', 'lock', 'spark' )[ (int) ( $args['index'] ?? 0 ) % 3 ];
?>
<a <?php post_class( 'post' . ( ! empty( $args['lead'] ) ? ' post-lead' : '' ) ); ?> href="<?php the_permalink(); ?>" data-reveal data-spot>
	<span class="post-art<?php echo has_post_thumbnail() ? ' has-thumb' : ''; ?>" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'rmdhost-card', array( 'alt' => '' ) );
		} else {
			echo rmdhost_icon( $rmdhost_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</span>
	<span class="post-meta"><em class="pill pill-xs"><?php echo esc_html( $rmdhost_tag ); ?></em><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
	<strong><?php the_title(); ?></strong>
	<span class="muted"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></span>
	<span class="link-arrow"><?php esc_html_e( 'Read article', 'rmdhost' ); ?> <?php echo rmdhost_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
</a>
