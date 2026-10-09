<?php
/**
 * Page settings meta box (hero options, server product group).
 *
 * @package RMDHost
 */

namespace RMDHost;

defined( 'ABSPATH' ) || exit;

/**
 * Meta boxes.
 */
class Meta_Boxes {

	/**
	 * Hooks.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add' ) );
		add_action( 'save_post', array( __CLASS__, 'save' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'register_meta' ) );
	}

	/**
	 * Register meta for the REST API / block editor.
	 */
	public static function register_meta() {
		foreach ( array(
			'_rmd_hide_hero'          => 'boolean',
			'_rmd_transparent_header' => 'boolean',
			'_rmd_hero_title'         => 'string',
			'_rmd_hero_eyebrow'       => 'string',
			'_rmd_hero_lede'          => 'string',
			'_rmd_hero_visual'        => 'string',
			'_rmd_group'              => 'string',
		) as $key => $type ) {
			register_post_meta(
				'page',
				$key,
				array(
					'type'          => $type,
					'single'        => true,
					'show_in_rest'  => true,
					'auth_callback' => static function () {
						return current_user_can( 'edit_pages' );
					},
				)
			);
		}
	}

	/**
	 * Add the box.
	 */
	public static function add() {
		add_meta_box( 'rmdhost-page', __( 'RMDHost page settings', 'rmdhost' ), array( __CLASS__, 'render' ), 'page', 'side' );
	}

	/**
	 * Render.
	 *
	 * @param \WP_Post $post Post.
	 */
	public static function render( $post ) {
		wp_nonce_field( 'rmdhost_page_meta', 'rmdhost_page_meta_nonce' );
		$group = get_post_meta( $post->ID, '_rmd_group', true );
		?>
		<p><label><input type="checkbox" name="rmd_hide_hero" value="1" <?php checked( get_post_meta( $post->ID, '_rmd_hide_hero', true ) ); ?>> <?php esc_html_e( 'Hide the page hero (title banner)', 'rmdhost' ); ?></label></p>
		<p><label><input type="checkbox" name="rmd_transparent_header" value="1" <?php checked( get_post_meta( $post->ID, '_rmd_transparent_header', true ) ); ?>> <?php esc_html_e( 'Transparent header (Elementor pages that start with a dark section)', 'rmdhost' ); ?></label></p>
		<p><label for="rmd_hero_title"><?php esc_html_e( 'Hero title (defaults to the page title)', 'rmdhost' ); ?></label><br>
		<input type="text" class="widefat" id="rmd_hero_title" name="rmd_hero_title" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rmd_hero_title', true ) ); ?>"></p>
		<p><label for="rmd_hero_eyebrow"><?php esc_html_e( 'Hero eyebrow', 'rmdhost' ); ?></label><br>
		<input type="text" class="widefat" id="rmd_hero_eyebrow" name="rmd_hero_eyebrow" value="<?php echo esc_attr( get_post_meta( $post->ID, '_rmd_hero_eyebrow', true ) ); ?>"></p>
		<p><label for="rmd_hero_lede"><?php esc_html_e( 'Hero intro text (defaults to the excerpt)', 'rmdhost' ); ?></label><br>
		<textarea class="widefat" rows="3" id="rmd_hero_lede" name="rmd_hero_lede"><?php echo esc_textarea( get_post_meta( $post->ID, '_rmd_hero_lede', true ) ); ?></textarea></p>
		<p><label for="rmd_hero_visual"><?php esc_html_e( 'Hero visual (animated scene on the right)', 'rmdhost' ); ?></label><br>
		<select class="widefat" id="rmd_hero_visual" name="rmd_hero_visual">
			<option value=""><?php esc_html_e( '— None —', 'rmdhost' ); ?></option>
			<?php foreach ( rmdhost_scene_choices() as $rmd_scene => $rmd_scene_label ) : ?>
				<option value="<?php echo esc_attr( $rmd_scene ); ?>" <?php selected( get_post_meta( $post->ID, '_rmd_hero_visual', true ), $rmd_scene ); ?>><?php echo esc_html( $rmd_scene_label ); ?></option>
			<?php endforeach; ?>
		</select></p>
		<p><label for="rmd_group"><?php esc_html_e( 'Server product (for the "Server product" template)', 'rmdhost' ); ?></label><br>
		<select class="widefat" id="rmd_group" name="rmd_group">
			<option value=""><?php esc_html_e( '— Select —', 'rmdhost' ); ?></option>
			<?php foreach ( Data::group_choices() as $slug => $name ) : ?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $group, $slug ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select></p>
		<?php
	}

	/**
	 * Save.
	 *
	 * @param int      $post_id Post ID.
	 * @param \WP_Post $post    Post.
	 */
	public static function save( $post_id, $post ) {
		if ( 'page' !== $post->post_type || ! isset( $_POST['rmdhost_page_meta_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['rmdhost_page_meta_nonce'] ) ), 'rmdhost_page_meta' ) || ! current_user_can( 'edit_page', $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}
		update_post_meta( $post_id, '_rmd_hide_hero', ! empty( $_POST['rmd_hide_hero'] ) );
		update_post_meta( $post_id, '_rmd_transparent_header', ! empty( $_POST['rmd_transparent_header'] ) );
		update_post_meta(
			$post_id,
			'_rmd_hero_title',
			isset( $_POST['rmd_hero_title'] ) ? wp_kses(
				wp_unslash( $_POST['rmd_hero_title'] ),
				array(
					'br'   => array(),
					'span' => array( 'class' => true ),
				)
			) : ''
		); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- kses.
		update_post_meta( $post_id, '_rmd_hero_eyebrow', isset( $_POST['rmd_hero_eyebrow'] ) ? sanitize_text_field( wp_unslash( $_POST['rmd_hero_eyebrow'] ) ) : '' );
		update_post_meta( $post_id, '_rmd_hero_lede', isset( $_POST['rmd_hero_lede'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rmd_hero_lede'] ) ) : '' );
		update_post_meta( $post_id, '_rmd_hero_visual', isset( $_POST['rmd_hero_visual'] ) && array_key_exists( sanitize_key( wp_unslash( $_POST['rmd_hero_visual'] ) ), rmdhost_scene_choices() ) ? sanitize_key( wp_unslash( $_POST['rmd_hero_visual'] ) ) : '' );
		update_post_meta( $post_id, '_rmd_group', isset( $_POST['rmd_group'] ) ? sanitize_key( wp_unslash( $_POST['rmd_group'] ) ) : '' );
	}
}
