<?php
/**
 * Post grid + pagination, or an empty state.
 *
 * @package RMDHost
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="section">
	<div class="container<?php echo is_active_sidebar( 'sidebar-1' ) ? ' with-sidebar' : ''; ?>">
		<div class="content-col">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php
					$rmdhost_i = 0;
					while ( have_posts() ) {
						the_post();
						get_template_part(
							'template-parts/content/post-card',
							null,
							array(
								'lead'  => 0 === $rmdhost_i && ! is_paged() && ! is_search(),
								'index' => $rmdhost_i,
							)
						);
						++$rmdhost_i;
					}
					?>
				</div>
				<?php rmdhost_pagination(); ?>
			<?php else : ?>
				<div class="empty-state center">
					<h2 class="h3"><?php esc_html_e( 'Nothing found', 'rmdhost' ); ?></h2>
					<p class="muted"><?php is_search() ? esc_html_e( 'No results matched your search. Try different keywords.', 'rmdhost' ) : esc_html_e( 'There’s nothing here yet.', 'rmdhost' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php get_sidebar(); ?>
	</div>
</section>
