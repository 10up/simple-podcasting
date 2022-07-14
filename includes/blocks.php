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

	register_block_type(
		'podcasting/podcast-meta',
		array(
			'attributes'      => array(
				'postId'      => array(
					'type' => 'integer',
				),
				'metaName'    => array(
					'type' => 'string',
				),
				'headingSize' => array(
					'type'    => 'string',
					'default' => 'h2'
				),
			),
			'editor_script'   => 'podcasting-block-editor',
			'render_callback' => __NAMESPACE__ . '\podcast_meta_block_render',
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
 * Podcast meta block renderer
 *
 * @param array  $attributes attributes of the block
 * @param string $content content of the block
 * @param object $block block object
 */
function podcast_meta_block_render( $attributes, $content, $block ) {

	if ( ! isset( $attributes['postId'] ) ) {
		return '';
	}

	$post_id       = $attributes['postId'];
	$allowed_metas = array(
		'podcast_season_number'  => get_post_meta( $post_id, 'podcast_season_number', true ),
		'podcast_episode_number' => get_post_meta( $post_id, 'podcast_episode_number', true ),
		'podcast_duration'       => \tenup_podcasting\helpers\get_podcast_duration( $post_id ),
	);

	if ( empty( $attributes['metaName'] ) || ! isset( $allowed_metas[ $attributes['metaName'] ] ) ) {
		return '';
	}

	if ( empty( $attributes['headingSize'] ) ) {
		$attributes['headingSize'] = 'h2';
	}

	$element_map = array(
		'podcast_season_number'  => $attributes['headingSize'],
		'podcast_episode_number' => $attributes['headingSize'],
		'podcast_duration'       => 'span'
	);

	$classnames = array( str_replace( '_', '-', $attributes['metaName'] ) );

	if ( ! empty( $attributes['className'] ) ) {
		$classnames[] = $attributes['className'];
	}

	return sprintf(
		'<%1$s class="%2$s">%3$s</%1$s>',
		$element_map[ $attributes['metaName'] ],
		esc_attr( join( ' ', $classnames ) ),
		$allowed_metas[ $attributes['metaName'] ]
	);
}
