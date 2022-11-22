<?php
/**
 * Register and enqueue all things block-related.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\block;

/**
 * Register block and its assets.
 */
function init() {
	$block_asset = require PODCASTING_PATH . 'dist/blocks.asset.php';
	wp_register_script(
		'podcasting-block-editor',
		PODCASTING_URL . 'dist/blocks.js',
		$block_asset['dependencies'],
		$block_asset['version'],
		true
	);

	wp_register_style(
		'podcasting-block-editor',
		PODCASTING_URL . 'dist/blocks.css',
		array(),
		$block_asset['version'],
		'all'
	);

	register_block_type(
		'podcasting/podcast',
		array(
			'editor_script' => 'podcasting-block-editor',
			'editor_style'  => 'podcasting-block-editor',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Registers block for Podcast Platforms.
 */
function register_podcast_platforms_block() {
	if ( ! file_exists( PODCASTING_PATH . 'dist/podcast-platforms-block.asset.php' ) ) {
		return;
	}

	$block_asset = require PODCASTING_PATH . 'dist/podcast-platforms-block.asset.php';

	wp_register_script(
		'podcast-platforms-block-editor',
		PODCASTING_URL . 'dist/podcast-platforms-block.js',
		$block_asset['dependencies'],
		$block_asset['version'],
		true
	);

	wp_localize_script(
		'podcast-platforms-block-editor',
		'podcastingPlatformVars',
		array(
			'podcastingUrl' => PODCASTING_URL,
		)
	);

	wp_register_style(
		'podcast-platforms-block-editor',
		PODCASTING_URL . 'dist/podcast-platforms-block.css',
		array(),
		$block_asset['version'],
		'all'
	);

	register_block_type(
		'podcasting/podcast-platforms',
		array(
			'editor_script' => 'podcast-platforms-block-editor',
			'editor_style'  => 'podcast-platforms-block-editor',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_podcast_platforms_block' );

/**
 * Register JS block-specific strings.
 *
 * These need to be available in PHP for .pot creation but don't need to do anything.
 *
 * @return void
 */
function register_js_strings() {
	__( 'Insert a podcast episode into a post. To add it to a podcast feed, select a podcast in document settings.', 'simple-podcasting' );
	__( 'Podcast Settings', 'simple-podcasting' );
	__( 'Length (MM:SS)', 'simple-podcasting' );
	__( 'a podcast episode', 'simple-podcasting' );
	__( 'Season Number', 'simple-podcasting' );
	__( 'Episode Number', 'simple-podcasting' );
	__( 'Episode Type', 'simple-podcasting' );
}
add_action( 'init', __NAMESPACE__ . '\register_js_strings' );

/**
 * Register and load block editor translations.
 *
 * In an ideal world, this would only load the translations absolutely necessary in a JS context.
 * Since this is a small plugin it's still okay for now.
 *
 * @return void
 */
function load_translations() {
	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'podcasting-block-editor', 'simple-podcasting' );
	} elseif ( function_exists( 'gutenberg_get_jed_locale_data' ) ) {
		$data = wp_json_encode( gutenberg_get_jed_locale_data( 'simple-podcasting' ) );
		wp_add_inline_script(
			'wp-i18n',
			'wp.i18n.setLocaleData( ' . $data . ', "simple-podcasting" );'
		);
	}
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\load_translations' );

/**
 * Delete left over post meta after deleting podcast block.
 *
 * @param WP_Post         $post     Inserted or updated post object.
 * @param WP_REST_Request $request  Request object.
 * @param bool            $creating True when creating a post, false when updating.
 * @return void
 */
function block_editor_meta_cleanup( $post, $request, $creating ) {
	if ( $creating ) {
		return;
	}

	if ( has_block( 'podcasting/podcast', $post->ID ) ) {
		return;
	}

	\tenup_podcasting\helpers\delete_all_podcast_meta( $post->ID );
}
add_action( 'rest_after_insert_post', __NAMESPACE__ . '\block_editor_meta_cleanup', 10, 3 );

/**
 * Returns podcast platforms meta.
 */
function ajax_get_podcast_platforms() {
	$term_id = filter_input( INPUT_GET, 'show_id', FILTER_VALIDATE_INT );

	if ( ! $term_id ) {
		wp_send_json_error( esc_html__( 'Term ID not valid', 'simple-podcasting' ) );
	}

	$platforms = get_term_meta( $term_id, 'podcasting_platforms', true );

	if ( ! is_array( $platforms ) ) {
		wp_send_json_error( esc_html__( 'No shows found', 'simple-podcasting' ) );
	}

	$platforms = array_filter(
		$platforms,
		function( $platform ) {
			return ! empty( $platform );
		}
	);

	$result = array(
		'platforms' => $platforms,
		'theme'     => '',
	);

	wp_send_json_success( $result );
}
add_action( 'wp_ajax_get_podcast_platforms', __NAMESPACE__ . '\ajax_get_podcast_platforms' );
