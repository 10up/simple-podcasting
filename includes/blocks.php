<?php
namespace tenup_podcasting;

/**
 * Register block and its assets.
 */
function block_init() {
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

	$editor_css = 'assets/css/block-editor.css';
	wp_register_style(
		'podcasting-block-editor',
		PODCASTING_URL . $editor_css,
		array(
			'wp-blocks',
		),
		filemtime( PODCASTING_PATH . $editor_css )
	);

/**
	$style_css = 'assets/css/block-display.css';
	wp_register_style(
		'podcasting-block',
		PODCASTING_URL . $style_css,
		array(
			'wp-blocks',
		),
		filemtime( "$dir/$style_css" )
	);
**/
	register_block_type( 'podcasting/podcast', array(
		'editor_script' => 'podcasting-block-editor',
		'editor_style'  => 'podcasting-block-editor',
		// 'style'         => 'podcasting-block',
	) );
}
add_action( 'init', __NAMESPACE__ . '\block_init' );
