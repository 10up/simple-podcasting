<?php
/**
 * Plugin Name:       Simple Podcasting
 * Plugin URI:        https://github.com/10up/simple-podcasting
 * Description:       Easily set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block for the new WordPress editor.
 * Version:           1.5.0
 * Author:            10up
 * Author URI:        http://10up.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-podcasting
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting;

define( 'PODCASTING_VERSION', '1.5.0' );
define( 'PODCASTING_PATH', dirname( __FILE__ ) . '/' );
define( 'PODCASTING_URL', plugin_dir_url( __FILE__ ) );
define( 'TAXONOMY_NAME', 'podcasting_podcasts' );
define( 'PODCASTING_ITEMS_PER_PAGE', 250 );

require_once PODCASTING_PATH . 'includes/admin/onboarding.php';
require_once PODCASTING_PATH . 'includes/datatypes.php';
require_once PODCASTING_PATH . 'includes/helpers.php';
require_once PODCASTING_PATH . 'includes/rest-external-url.php';
require_once PODCASTING_PATH . 'includes/transcripts.php';

// Init the endpoint.
endpoints\externalurl\setup();

/**
 * Flush rewrite rules on plugin activation.
 *
 * `flush_rewrite_rules()` cannot just be hooked on directly
 * because the taxonomy is not yet registered at that point.
 * So we call the taxonomy registration function ourselves first.
 *
 * @return void
 */
function activate_plugin() {
	create_podcasts_taxonomy();
	\flush_rewrite_rules();

	$terms = get_terms(
		array(
			'taxonomy'   => 'podcasting_podcasts',
			'hide_empty' => false,
		)
	);

	$has_podcast = is_array( $terms ) && ! empty( $terms );

	if ( $has_podcast ) {
		update_option( 'simple_podcasting_onboarding', 'completed' );
	}

	if ( '' === get_option( 'simple_podcasting_onboarding', '' ) ) {
		update_option( 'simple_podcasting_onboarding', 'no' );
	}
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_plugin' );

// Block editor support.
if ( function_exists( 'register_block_type' ) ) {
	require_once PODCASTING_PATH . 'includes/blocks.php';
}

if ( function_exists( 'register_block_pattern' ) ) {
	require_once PODCASTING_PATH . 'includes/block-patterns.php';
}

/**
 * Is podcasting enabled?
 *
 * If there are any podcast terms set up, podcasting is enabled.
 *
 * @return bool
 */
function podcasting_is_enabled() {
	$podcasting_terms = get_terms(
		array(
			'taxonomy'      => TAXONOMY_NAME,
			'hide_empty'    => false,
			'fields'        => 'ids',
			'no_found_rows' => true,
		)
	);

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
		'term.php',
	);

	if ( in_array( $hook_suffix, $screens, true ) ) {
		wp_enqueue_style(
			'podcasting_edit_term_screen',
			PODCASTING_URL . 'dist/podcasting-edit-term.css',
			array(),
			PODCASTING_VERSION
		);

		wp_enqueue_script(
			'podcasting_edit_term_screen',
			PODCASTING_URL . 'dist/podcasting-edit-term.js',
			array( 'jquery' ),
			PODCASTING_VERSION,
			true
		);
	}

	if ( 'admin_page_simple-podcasting-onboarding' === $hook_suffix ) {
		wp_enqueue_media();
		wp_enqueue_script(
			'podcasting_onboarding_screen_script',
			PODCASTING_URL . 'dist/podcasting-onboarding.js',
			array( 'jquery' ),
			PODCASTING_VERSION,
			true
		);

		wp_enqueue_style(
			'podcasting_onboarding_screen_style',
			PODCASTING_URL . 'dist/podcasting-onboarding.css',
			array(),
			PODCASTING_VERSION
		);

		wp_enqueue_style( 'podcasting_onboarding_fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap' ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\podcasting_edit_term_enqueues' );

/**
 * Load the file containing iTunes specific feed hooks.
 *
 * @param \WP_Query $query The query being parsed.
 *
 * @uses includes/customize-feed.php
 */
function custom_feed( \WP_Query $query ) {
	if ( is_admin() || ! podcasting_is_enabled() ) {
		return;
	}

	// Is this a feed for a term in the podcasting taxonomy?
	if ( $query->is_feed() && $query->is_tax( TAXONOMY_NAME ) ) {
		remove_action( 'rss2_head', 'rss2_blavatar' );
		remove_action( 'rss2_head', 'rss2_site_icon' );
		remove_filter( 'the_excerpt_rss', 'add_bug_to_feed', 100 );
		remove_action( 'rss2_head', 'rsscloud_add_rss_cloud_element' );
		add_filter(
			'wp_feed_cache_transient_lifetime',
			function () {
				return HOUR_IN_SECONDS;
			}
		);
		require_once PODCASTING_PATH . 'includes/customize-feed.php';
	}
}
add_action( 'parse_query', __NAMESPACE__ . '\custom_feed', 10, 1 );


/**
 * Initialize the edit screen if podcasting is enabled.
 *
 * @uses includes/post-meta-box.php
 */
function setup_edit_screen() {
	if ( podcasting_is_enabled() ) {
		require_once PODCASTING_PATH . 'includes/post-meta-box.php';
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\setup_edit_screen' );
