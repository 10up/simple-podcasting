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

require_once plugin_dir_path( __FILE__ ) . 'podcasting/datatypes.php';

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
		plugin_dir_url( __FILE__ ) . 'podcasting/assets/css/podcasting-edit-term.css'
	);

	wp_enqueue_script(
		'podcasting_edit_term_screen',
		plugin_dir_url( __FILE__ ) . 'podcasting/assets/js/podcasting-edit-term.js',
		array( 'jquery' ),
		true
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\podcasting_edit_term_enqueues' );


/**
 * Podcasting for WordPress.
 *
 */
$init = new Podcasting();


/**
 * The main podcasting class used to set up the plugin.
 */
class Podcasting {

	function __construct() {

		if ( podcasting_is_enabled() ) {

			if ( ! is_admin() ) {
				add_action( 'wp', array( __NAMESPACE__ . '\Podcasting', 'custom_feed' ) );
			}

			require_once plugin_dir_path( __FILE__ ) . 'podcasting/post-meta-box.php';
		}

	}

	/**
	 * Load the file containing iTunes specific feed hooks.
	 *
	 * @uses podcasting/customize-feed.php
	 */
	public static function custom_feed() {

		// Is this a feed for a term in the podcasting taxonomy?
		if ( is_feed() && is_tax( TAXONOMY_NAME ) ) {
			remove_action( 'rss2_head', 'rss2_blavatar' );
			remove_action( 'rss2_head', 'rss2_site_icon' );
			remove_filter( 'the_excerpt_rss', 'add_bug_to_feed', 100 );
			remove_action( 'rss2_head', 'rsscloud_add_rss_cloud_element' );
			add_filter( 'wp_feed_cache_transient_lifetime', function() {
				return HOUR_IN_SECONDS;
			} );
			require_once plugin_dir_path( __FILE__ ) . 'podcasting/customize-feed.php';
		}
	}

}
