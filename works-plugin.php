<?php
/**
 * Plugin Name:       Works Plugin
 * Description:       Works Plugin provides an interface for creating custom post types and custom fields.
 * Version:           1.0.0
 * Author:            Kayo
 *
 * @package Works_plugin
 */

/**
 * Custom post types.
 */
function create_post_type() {
	register_post_type(
		'works',
		array(
			'label'         => 'Works',
			'public'        => true,
			'menu_position' => 5,
			'has_archive'   => true,
			'supports'      => array(
				'title',
				'editor',
				'thumbnail',
			),
		)
	);
}
add_action( 'init', 'create_post_type' );

/**
 * Custom_fields.
 */
/** Custom fields meta box. */
function add_custom_fields_meta_box() {
	add_meta_box(
		'works_meta_box',
		'Works Custom fields',
		'works_custom_fields',
		'works',
		'normal'
	);
}
add_action( 'add_meta_boxes', 'add_custom_fields_meta_box' );

/** Insert custom fields. */
function works_custom_fields() {
	global $post;
	$category     = get_post_meta( $post->ID, 'category', true );
	$description  = get_post_meta( $post->ID, 'description', true );
	$point        = get_post_meta( $post->ID, 'point', true );
	$url          = get_post_meta( $post->ID, 'url', true );
	$github       = get_post_meta( $post->ID, 'github', true );
	$githubplugin = get_post_meta( $post->ID, 'githubplugin', true );
	$tools        = get_post_meta( $post->ID, 'tools', true );
	$media        = get_post_meta( $post->ID, 'media', true );
	?>

	<input type="hidden" name="meta_box_nonce" value="<?php echo esc_attr( wp_create_nonce( basename( __FILE__ ) ) ); ?>">
	<p>
		<label for="category">Category</label>
		<br>
		<input type="text" name="category" id="category" class="regular-text" value="<?php echo esc_attr( $category ); ?>">
	</p>
	<p>
		<label for="description">Description</label>
		<br>
		<textarea name="description" id="description" rows="5" cols="30" style="width:500px;"><?php echo esc_textarea( $description ); ?></textarea>
	</p>
	<p>
		<label for="point">Point</label>
		<br>
		<textarea name="point" id="point" rows="5" cols="30" style="width:500px;"><?php echo esc_textarea( $point ); ?></textarea>
	</p>
	<p>
		<label for="url">URL</label>
		<br>
		<input type="text" name="url" id="url" class="regular-text" value="<?php echo esc_url( $url ); ?>">
	</p>
	<p>
		<label for="github">GitHub</label>
		<br>
		<input type="text" name="github" id="github" class="regular-text" value="<?php echo esc_url( $github ); ?>">
	</p>
	<p>
		<label for="githubplugin">GitHub (Plugin)</label>
		<br>
		<input type="text" name="githubplugin" id="githubplugin" class="regular-text" value="<?php echo esc_url( $githubplugin ); ?>">
	</p>
	<p>
		<label for="tools">Tools</label>
		<br>
		<input type="text" name="tools" id="tools" class="regular-text" value="<?php echo esc_attr( $tools ); ?>">
	</p>
	<div>
		<label for="media">Image Upload</label>
		<br>
		<input type="text" name="media" id="media" style="width:370px;" onkeydown="event.preventDefault();" readonly value="<?php echo esc_url( $media ); ?>"/>
		<input type="button" class="media-upload" value="Upload">
		<input type="button" class="media-remove" value="Remove">
		<br><br>
		<?php if ( $media ) : ?>
		<div class="image-preview"><img src="<?php echo esc_url( $media ); ?>" style="max-width: 300px;"></div>
		<?php else : ?>
		<div class="image-preview"></div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Save fields meta.
 *
 * @param int $post_id The ID of the post being saved.
 */
function save_fields_meta( $post_id ) {
	$allowed_html = array(
		'br' => array(),
	);

	if ( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {
		return $post_id;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	if ( 'page' === isset( $_POST['post_type'] ) ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	if ( isset( $_POST['category'] ) ) {
		$sanitize_category = sanitize_text_field( wp_unslash( $_POST['category'] ) );
		update_post_meta( $post_id, 'category', $sanitize_category );
	} else {
		delete_post_meta( $post_id, 'category' );
	}

	if ( isset( $_POST['description'] ) ) {
		$kses_description = wp_kses( wp_unslash( $_POST['description'] ), $allowed_html );
		update_post_meta( $post_id, 'description', $kses_description );
	} else {
		delete_post_meta( $post_id, 'description' );
	}
	if ( isset( $_POST['point'] ) ) {
		$sanitize_point = sanitize_text_field( wp_unslash( $_POST['point'] ) );
		update_post_meta( $post_id, 'point', $sanitize_point );
	} else {
		delete_post_meta( $post_id, 'point' );
	}
	if ( isset( $_POST['url'] ) ) {
		$sanitize_url = sanitize_text_field( wp_unslash( $_POST['url'] ) );
		update_post_meta( $post_id, 'url', $sanitize_url );
	} else {
		delete_post_meta( $post_id, 'url' );
	}
	if ( isset( $_POST['github'] ) ) {
		$sanitize_github = sanitize_text_field( wp_unslash( $_POST['github'] ) );
		update_post_meta( $post_id, 'github', $sanitize_github );
	} else {
		delete_post_meta( $post_id, 'github' );
	}
	if ( isset( $_POST['githubplugin'] ) ) {
		$sanitize_githubplugin = sanitize_text_field( wp_unslash( $_POST['githubplugin'] ) );
		update_post_meta( $post_id, 'githubplugin', $sanitize_githubplugin );
	} else {
		delete_post_meta( $post_id, 'githubplugin' );
	}
	if ( isset( $_POST['tools'] ) ) {
		$sanitize_tools = sanitize_text_field( wp_unslash( $_POST['tools'] ) );
		update_post_meta( $post_id, 'tools', $sanitize_tools );
	} else {
		delete_post_meta( $post_id, 'tools' );
	}
	if ( isset( $_POST['media'] ) ) {
		$sanitize_media = sanitize_text_field( wp_unslash( $_POST['media'] ) );
		update_post_meta( $post_id, 'media', $sanitize_media );
	} else {
		delete_post_meta( $post_id, 'media' );
	}
}
add_action( 'save_post', 'save_fields_meta' );

/**
 * Loads the image management JavaScript.
 */
function add_api() {
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'add_api' );

/**
 * Registers and enqueues the required JavaScript.
 */
function add_script() {
	wp_enqueue_script( 'my_admin_script', plugins_url() . '/works-plugin/js/metaBoxImage.js', array(), '1.0.0', true );
}
add_action( 'admin_footer', 'add_script' );
