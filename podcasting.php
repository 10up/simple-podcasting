<?php
/**
 * Plugin Name: Podcasting for WordPress
 * Plugin URI: http://wordpress.org/
 * Description: Podcasting, enhanced by 10up.
 * Author: 10up, original code from Automattic
 * Version: 1.0.0
 * Author URI: http://10up.com/
 */
namespace tenup_podcasting;

const TAXONOMY_NAME = 'podcasting_podcasts';

require_once plugin_dir_path( __FILE__ ) . 'includes/datatypes.php';

register_activation_hook( __FILE__, 'flush_rewrite_rules' );

/**
 * Is podcasting enabled?
 *
 * If there are any podcast terms set up, podcasting is enabled.
 *
 * @return bool
 */
function podcasting_is_enabled() {
	$podcasting_terms = get_terms( array(
		'taxonomy'      => TAXONOMY_NAME,
		'hide_empty'    => false,
		'fields'        => 'ids',
		'no_found_rows' => true,
	) );

	return ! empty( $podcasting_terms );
}

/**
 * Enqueue admin scripts and styles on the term and term edit screens.
 *
 * @param  string $hook_suffix The $hook_suffix for the current admin page.
 */
function podcasting_edit_term_enqueues( $hook_suffix ) {
	$screens = array(
		'edit-tags.php',
		'term.php'
	);

	if ( ! in_array( $hook_suffix, $screens, true ) ) {
		return;
	}

	wp_enqueue_style(
		'podcasting_edit_term_screen',
		plugin_dir_url( __FILE__ ) . 'assets/css/podcasting-edit-term.css'
	);

	wp_enqueue_script(
		'podcasting_edit_term_screen',
		plugin_dir_url( __FILE__ ) . 'assets/js/podcasting-edit-term.js',
		array( 'jquery' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\podcasting_edit_term_enqueues' );

/**
 * Load the file containing iTunes specific feed hooks.
 *
 * @uses includes/customize-feed.php
 */
function custom_feed() {
	if ( is_admin() || ! podcasting_is_enabled() ) {
		return;
	}

	// Is this a feed for a term in the podcasting taxonomy?
	if ( is_feed() && is_tax( TAXONOMY_NAME ) ) {
		remove_action( 'rss2_head', 'rss2_blavatar' );
		remove_action( 'rss2_head', 'rss2_site_icon' );
		remove_filter( 'the_excerpt_rss', 'add_bug_to_feed', 100 );
		remove_action( 'rss2_head', 'rsscloud_add_rss_cloud_element' );
		add_filter( 'wp_feed_cache_transient_lifetime', function() {
			return HOUR_IN_SECONDS;
		} );
		require_once plugin_dir_path( __FILE__ ) . 'includes/customize-feed.php';
	}
}
add_action( 'wp', __NAMESPACE__ . '\custom_feed' );

function setup_edit_screen() {
	if ( podcasting_is_enabled() ) {
		require_once plugin_dir_path( __FILE__ ) . 'includes/post-meta-box.php';
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\setup_edit_screen' );

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */
function block_init() {
	$dir = dirname( __FILE__ );

	$block_js = 'dist/js/blocks.min.js';
	wp_register_script(
		'podcasting-block-editor',
		plugins_url( $block_js, __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
		),
		filemtime( "$dir/$block_js" )
	);

	$editor_css = 'assets/css/block-editor.css';
	wp_register_style(
		'podcasting-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(
			'wp-blocks',
		),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'assets/css/block-display.css';
	wp_register_style(
		'podcasting-block',
		plugins_url( $style_css, __FILE__ ),
		array(
			'wp-blocks',
		),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'podcasting/podcast', array(
		'editor_script' => 'podcasting-block-editor',
		'editor_style'  => 'podcasting-block-editor',
		'style'         => 'podcasting-block',
	) );
}
add_action( 'init', __NAMESPACE__ . '\block_init' );

