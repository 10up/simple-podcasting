<?php
/**
 * Defines class to handle creation of a podcast
 * from with the Post editor.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\admin;

/**
 * Adds methods to create a podcast using the Gutenberg inspector component.
 */
class Create_Podcast_Component {
	/**
	 * Constructor method.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Admin enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( ! ( $screen && 'post' === $screen->id ) ) {
			return;
		}

		wp_enqueue_script(
			'podcasting_create_podcast_show_plugin',
			PODCASTING_URL . 'dist/create-podcast-show.js',
			array(),
			PODCASTING_VERSION,
			true
		);

		wp_localize_script(
			'podcasting_create_podcast_show_plugin',
			'podcastingShowPluginVars',
			array(
				'categories' => \tenup_podcasting\get_podcasting_categories_options(),
			)
		);
	}
}

new Create_Podcast_Component();
