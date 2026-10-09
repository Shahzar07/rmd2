<?php
/**
 * Default page template.
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
	rmdhost_singular_hero( array( 'small' => true ) );
	if ( has_block( 'rmdhost/section' ) ) {
		// Section blocks are full-width layouts: render the content edge to edge.
		echo '<div class="entry-full">';
		the_content();
		echo '</div>';
		continue;
	}
	?>
	<section class="section">
		<div class="<?php echo esc_attr( rmdhost_content_container_class() ); ?>">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links">',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</section>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<section class="section pt-0"><div class="container narrow">';
		comments_template();
		echo '</div></section>';
	}
endwhile;

get_footer();
