<?php
/**
 * Add a meta box to the post edit screen, plus handlers for saving.
 *
 * @package tenup_podcasting;
 */

namespace tenup_podcasting;

/**
 * Add a Podcasting metabox to the post edit screen.
 */
function add_podcasting_meta_box() {
	add_meta_box(
		'podcasting',
		__( 'Podcasting', 'simple-podcasting' ),
		__NAMESPACE__ . '\meta_box_html',
		'post',
		'advanced',
		'default',
		array(
			'__back_compat_meta_box' => true,
		)
	);
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\add_podcasting_meta_box' );

/**
 * Output the Podcasting meta box.
 *
 * @param  object WP_Post $post The current post.
 */
function meta_box_html( $post ) {
	$podcast_url = get_post_meta( $post->ID, 'podcast_url', true );
	$podcast_duration = get_post_meta( $post->ID, 'podcast_duration', true );
	$podcast_mime = get_post_meta( $post->ID, 'podcast_mime', true );
	$podcast_explicit = get_post_meta( $post->ID, 'podcast_explicit', true );
	$podcast_captioned = get_post_meta( $post->ID, 'podcast_captioned', true );

	wp_nonce_field( plugin_basename( __FILE__ ), 'simple-podcasting' );
	?>
	<p>
		<label for="podcast_closed_captioned">
			<?php esc_html_e( 'Closed Captioned', 'simple-podcasting' ); ?>
			<input type="checkbox" id="podcast_closed_captioned" name="podcast_closed_captioned" <?php checked( $podcast_captioned ); ?> />
		</label>
	</p>

	<p>
		<label for="podcast_explicit_content">
			<?php esc_html_e( 'Explicit Content', 'simple-podcasting' ); ?>
			<select id="podcast_explicit_content" name="podcast_explicit_content">
				<option value="no"<?php selected( $podcast_explicit, 'no' ); ?>><?php esc_html_e( 'No' ); ?></option>
				<option value="yes"<?php selected( $podcast_explicit, 'yes' ); ?>><?php esc_html_e( 'Yes' ); ?></option>
				<option value="clean"<?php selected( $podcast_explicit, 'clean' ); ?>><?php esc_html_e( 'Clean', 'simple-podcasting' ); ?></option>
			</select>
		</label>
	</p>

	<p>
		<label for="podcasting-enclosure-url"><?php esc_html_e( 'Enclosure', 'simple-podcasting' ); ?></label>
		<input type="text" id="podcasting-enclosure-url" name="podcast_enclosure_url" value="<?php echo esc_url( $podcast_url ); ?>" size="35" />
		<input type="button" id="podcasting-enclosure-button" value="<?php esc_attr_e( 'Choose File', 'simple-podcasting' ); ?>" class="button"data-modal-title="<?php esc_attr_e( 'Podcast Enclosure', 'simple-podcasting' ); ?>" data-modal-button="<?php esc_attr_e( 'Select this file' ); ?>" />
	</p>

	<p class="howto"><?php esc_html_e( 'Optional: Use this field if you have more than one audio/video file in your post.', 'simple-podcasting' ); ?></p>

	<?php
}

/**
 * Handle the post save event, saving any data from the meta box.
 *
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function save_meta_box( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( empty( $_POST['simple-podcasting'] ) || ! wp_verify_nonce( $_POST['simple-podcasting'], plugin_basename( __FILE__ ) ) ) {
		return;
	}

	$_post = wp_unslash( $_POST );

	$url              = false;
	$podcast_captioned = 0;
	$podcast_explicit = 'no';

	if ( isset( $_post['podcast_closed_captioned'] ) && 'on' === $_post['podcast_closed_captioned'] ) {
		$podcast_captioned = 1;
	}

	if ( isset( $_post['podcast_explicit_content'] ) && in_array( $_post['podcast_explicit_content'], array( 'yes', 'no', 'clean' ), true ) ) {
		$podcast_explicit = sanitize_text_field( $_post['podcast_explicit_content'] );
	}

	if ( isset( $_post['podcast_enclosure_url'] ) && ! empty( $_post['podcast_enclosure_url'] ) ) {
		$url = sanitize_text_field( $_post['podcast_enclosure_url'] );
	} else {
		// Search for an audio shortcode to determine the audio enclosure url.
		$pattern = get_shortcode_regex();
		$post = get_post( $post_id );

		if (
			preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches )
			&& array_key_exists( 2, $matches )
			&& in_array( 'audio', $matches[2], true )
		) {
			preg_match( '/.*mp3=\\"(.*)\\".*/', $matches[0][0], $matches2 );
			if ( isset( $matches2[1] ) ) {
				$url = $matches2[1];
			}
		}
	}

	/**
	 * Retrieve the enclosure and store its metadata in post meta.
	 *
	 * @todo only retrieve enclosure metadata when a podcasting term id is selected and the url has changed.
	 */
	if ( $url ) {
		$podcast_meta = \tenup_podcasting\helpers\get_podcast_meta_from_url( $url );

		if ( ! empty( $podcast_meta ) ) {
			update_post_meta( $post_id, 'podcast_url', $podcast_meta['url'] );
			update_post_meta( $post_id, 'podcast_filesize', $podcast_meta['filesize'] );
			update_post_meta( $post_id, 'podcast_duration', $podcast_meta['duration'] );
			update_post_meta( $post_id, 'podcast_mime', $podcast_meta['podcast_mime'] );
		}
	}

	update_post_meta( $post_id, 'podcast_explicit', $podcast_explicit );
	update_post_meta( $post_id, 'podcast_captioned', $podcast_captioned );

}
add_action( 'save_post_post', __NAMESPACE__ . '\save_meta_box' );

/**
 * Enqueue helper script for the post edit and new post screens.
 *
 * @param  string $hook_suffix The current admin page.
 */
function edit_post_enqueues( $hook_suffix ) {
	$screens = array(
		'post.php',
		'post-new.php',
	);

	if ( ! in_array( $hook_suffix, $screens, true ) ) {
		return;
	}

	wp_enqueue_script(
		'podcasting_edit_post_screen',
		PODCASTING_URL . 'dist/podcasting-edit-post.js',
		array( 'jquery' ),
		PODCASTING_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\edit_post_enqueues' );
