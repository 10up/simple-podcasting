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
	$block_js = 'dist/js/blocks.min.js';
	wp_register_script(
		'podcasting-block-editor',
		PODCASTING_URL . $block_js,
		array(
			'wp-blocks',
			'wp-editor',
			'wp-i18n',
			'wp-element',
		),
		PODCASTING_VERSION
	);

	register_block_type(
		'podcasting/podcast',
		array(
			'editor_script' => 'podcasting-block-editor',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\init' );

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
