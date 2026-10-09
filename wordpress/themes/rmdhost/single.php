<?php
/**
 * Single post.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	if ( rmdhost_is_elementor() ) {
		the_content();
		continue;
	}
	$rmdhost_cats = get_the_category();
	rmdhost_singular_hero(
		array(
			'eyebrow' => $rmdhost_cats ? $rmdhost_cats[0]->name : '',
			'title'   => get_the_title(),
			'small'   => true,
		)
	);
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'section' ); ?>>
		<div class="container narrow prose entry-content">
			<p class="muted post-byline"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time> · <?php the_author(); ?></p>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="page-photo post-thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links">',
					'after'  => '</nav>',
				)
			);
			?>
			<?php if ( has_tag() ) : ?>
				<p class="post-tags"><?php the_tags( '', ' ' ); ?></p>
			<?php endif; ?>
			<?php
			the_post_navigation(
				array(
					'prev_text' => rmdhost_icon( 'chevronLeft' ) . ' %title',
					'next_text' => '%title ' . rmdhost_icon( 'chevronRight' ),
				)
			);
			?>
			<?php $rmdhost_blog = (int) get_option( 'page_for_posts' ); ?>
			<p><a class="row-link inline" href="<?php echo esc_url( $rmdhost_blog ? get_permalink( $rmdhost_blog ) : home_url( '/' ) ); ?>"><?php echo rmdhost_icon( 'chevronLeft' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Back to the blog', 'rmdhost' ); ?></a></p>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
	rmdhost_section( 'cta' );
endwhile;

get_footer();
