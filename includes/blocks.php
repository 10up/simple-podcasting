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
 * Register block and its assets.
 */
function init_transcript() {
	$podcast_transcript_block_asset = require PODCASTING_PATH . 'dist/podcasting-transcript.asset.php';

	wp_register_script(
		'podcasting-transcript',
		PODCASTING_URL . 'dist/podcasting-transcript.js',
		$podcast_transcript_block_asset['dependencies'],
		$podcast_transcript_block_asset['version'],
		true
	);

	wp_register_style(
		'podcasting-transcript',
		PODCASTING_URL . 'dist/podcasting-transcript.css',
		array(),
		$podcast_transcript_block_asset['version'],
		'all'
	);

	$transcript_block_args = array(
		'editor_script' => 'podcasting-transcript',
		'style_handles' => array( 'podcasting-transcript' ),
		'title'         => __( 'Podcast Transcript', 'simple-podcasting' ),
		'description'   => '',
		'textdomain'    => 'simple-podcasting',
		'name'          => 'podcasting/podcast-transcript',
		'icon'          => 'format-quote',
		'api_version'   => 2,
		'category'      => 'common',
		'attributes'    => array(
			'transcript' => array(
				'type' => 'string',
			),
			'display'    => array(
				'type'    => 'string',
				'default' => 'post',
			),
			'linkText'   => array(
				'type'    => 'string',
				'default' => __( 'Transcript Link', 'simple-podcastin' ),
			),
		),
		'example'       => array(),
		'supports'      => array(
			'multiple' => false,
			'inserter' => false,
		),
	);

	$transcript_block_args['render_callback'] = function( $attributes, $content, $block ) {
		ob_start();
		include PODCASTING_PATH . 'includes/blocks/podcast-transcript/markup.php';
		return ob_get_clean();
	};

	register_block_type(
		'podcasting/podcast-transcript',
		$transcript_block_args
	);

	/**
	 * Simple cite block.
	 */
	register_block_type(
		'podcasting/podcast-transcript-cite',
		array(
			'editor_script' => 'podcasting-transcript',
			'title'         => __( 'Cite', 'simple-podcasting' ),
			'description'   => '',
			'textdomain'    => 'simple-podcasting',
			'name'          => 'podcasting/podcast-transcript-cite',
			'icon'          => 'admin-users',
			'api_version'   => 2,
			'category'      => 'text',
			'attributes'    => array(
				'text' => array(
					'type' => 'string',
				),
			),
			'supports'      => array(
				'html'     => false,
				'reusable' => false,
			),
			'parent'        => [ 'podcasting/podcast-transcript' ],
		)
	);

	/**
	 * Simple time block.
	 */
	register_block_type(
		'podcasting/podcast-transcript-time',
		array(
			'editor_script' => 'podcasting-transcript',
			'title'         => __( 'Time', 'simple-podcasting' ),
			'description'   => '',
			'textdomain'    => 'simple-podcasting',
			'name'          => 'podcasting/podcast-transcript-time',
			'icon'          => 'clock',
			'api_version'   => 2,
			'category'      => 'text',
			'attributes'    => array(
				'text' => array(
					'type' => 'string',
				),
			),
			'supports'      => array(
				'html'     => false,
				'reusable' => false,
			),
			'parent'        => [ 'podcasting/podcast-transcript' ],
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\init_transcript' );

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
			'editor_script'   => 'podcast-platforms-block-editor',
			'editor_style'    => 'podcast-platforms-block-editor',
			'style'           => 'podcast-platforms-block-editor',
			'render_callback' => __NAMESPACE__ . '\render_podcasting_platforms',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_podcast_platforms_block' );

/**
 * Renders the block `podcasting/podcast-platforms`.
 *
 * @param array $attrs Block attributes.
 * @return string
 */
function render_podcasting_platforms( $attrs ) {
	if ( ! ( is_array( $attrs ) && isset( $attrs['showId'] ) ) ) {
		return '';
	}

	$show_id   = isset( $attrs['showId'] ) ? $attrs['showId'] : 0;
	$icon_size = isset( $attrs['iconSize'] ) ? $attrs['iconSize'] : 48;
	$align     = isset( $attrs['align'] ) ? $attrs['align'] : 'center';

	if ( 0 === $show_id ) {
		return '';
	}

	$supported_platforms = \tenup_podcasting\get_supported_platforms();
	$platforms           = get_term_meta( $show_id, 'podcasting_platforms', true );
	$theme               = get_term_meta( $show_id, 'podcasting_icon_theme', true );
	$theme               = empty( $theme ) ? 'color' : $theme;

	if ( ! is_array( $platforms ) || empty( $platforms ) ) {
		return '';
	}

	ob_start();

	?>

	<div class="simple-podcasting__podcast-platforms">
		<div class='simple-podcasting__podcasting-platform-list <?php echo esc_attr( 'simple-podcasting__podcasting-platform-list--' . $align ); ?>'>
			<?php foreach ( $platforms as $slug => $url ) : ?>
				<?php
				if ( empty( $url ) ) {
					continue;
				}

				$podcast_title = $supported_platforms[ $slug ]['title'];
				?>

				<span class='simple-podcasting__podcasting-platform-list-item'>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" title="<?php echo esc_attr( $podcast_title ); ?>" aria-label="<?php echo esc_attr( $podcast_title ); ?>">
						<img
							class="simple-pocasting__icon-size--<?php echo esc_attr( $icon_size ); ?>"
							src="<?php printf( '%sdist/images/icons/%s/%s-100.png', esc_url( PODCASTING_URL ), esc_attr( $slug ), esc_attr( $theme ) ); ?>"
						/>
					</a>
				</span>
			<?php endforeach; ?>
		</div>
	</div>

	<?php

	return ob_get_clean();
}

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

	$theme = get_term_meta( $term_id, 'podcasting_icon_theme', true );

	if ( empty( $theme ) ) {
		$theme = 'color';
	}

	$result = array(
		'platforms' => $platforms,
		'theme'     => $theme,
	);

	wp_send_json_success( $result );
}
add_action( 'wp_ajax_get_podcast_platforms', __NAMESPACE__ . '\ajax_get_podcast_platforms' );

/**
 * Latest podcast query for front-end.
 *
 * @param Object $query query object.
 */
function latest_episode_query_loop( $query ) {

	// update query to only return posts that have a podcast selected
	return [
		'post_type'      => 'post',
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => [
			[
				'taxonomy' => 'podcasting_podcasts',
				'field'    => 'term_id',
				'operator' => 'EXISTS',
			],
		],
	];
}

/**
 * Latest podcast check.
 *
 * @param String $pre_render   pre render object.
 * @param Array  $parsed_block parsed block object.
 */
function latest_episode_check( $pre_render, $parsed_block ) {

	if ( isset( $parsed_block['attrs']['namespace'] ) && 'podcasting/latest-episode' === $parsed_block['attrs']['namespace'] ) {
		add_action( 'query_loop_block_query_vars', __NAMESPACE__ . '\latest_episode_query_loop' );
	}
}
add_filter( 'pre_render_block', __NAMESPACE__ . '\latest_episode_check', 10, 2 );

/**
 * Latest podcast query in editor.
 *
 * @param Array $args    query args.
 * @param Array $request request object.
 */
function latest_episode_query_api( $args, $request ) {

	$podcasting_podcasts = $request->get_param( 'podcastingQuery' );

	if ( 'not_empty' === $podcasting_podcasts ) {
		$args = [
			'post_type'      => 'post',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => [
				[
					'taxonomy' => 'podcasting_podcasts',
					'field'    => 'term_id',
					'operator' => 'EXISTS',
				],
			],
		];
	}

	return $args;
}
add_filter( 'rest_post_query', __NAMESPACE__ . '\latest_episode_query_api', 10, 2 );
