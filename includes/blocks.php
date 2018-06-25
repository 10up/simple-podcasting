<?php
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
			'wp-i18n',
			'wp-element',
		),
		filemtime( PODCASTING_PATH . $block_js )
	);

	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$editor_css = 'assets/css/block-editor.css';
	} else {
		$editor_css = 'dist/css/block-editor.min.css';
	}
	wp_register_style(
		'podcasting-block-editor',
		PODCASTING_URL . $editor_css,
		array(
			'wp-blocks',
		),
		filemtime( PODCASTING_PATH . $editor_css )
	);

	register_block_type( 'podcasting/podcast', array(
		'editor_script' => 'podcasting-block-editor',
		'editor_style'  => 'podcasting-block-editor',
	) );
}
add_action( 'init', __NAMESPACE__ . '\init' );

/**
 * Register Gutenberg-specific strings.
 *
 * These need to be available in PHP for .pot creation but don't need to do anything.
 *
 * @return void
 */
function register_js_strings() {
	__( 'Insert a podcast episode into a post. To add it to a podcast feed, select a podcast in document settings.', 'simple-podcasting' );
	__( 'Podcast Settings', 'simple-podcasting' );
}
add_action( 'init', __NAMESPACE__ . '\register_js_strings' );

/**
 * Register and load translations for use in Gutenberg.
 *
 * In an ideal world, this would only load the translations absolutely necessary in a JS context.
 * Since this is a small plugin it's still okay for now.
 *
 * @return void
 */
function load_translations() {
	$data = wp_json_encode( gutenberg_get_jed_locale_data( 'simple-podcasting' ) );
	wp_add_inline_script(
		'wp-i18n',
		'wp.i18n.setLocaleData( ' . $data . ', "simple-podcasting" );'
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\load_translations' );
