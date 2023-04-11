<?php
/**
 * Defines class to handle creation of a podcast
 * from with the Post editor.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\admin;

use tenup_podcasting\Create_Podcast;

/**
 * Adds methods to create a podcast using the Gutenberg inspector component.
 */
class Create_Podcast_Component {

	/**
	 * Holds the object for Create_Podcast.
	 *
	 * @var \tenup_podcasting\Create_Podcast
	 */
	protected $create_podcast = null;

	/**
	 * Constructor method.
	 */
	public function __construct() {
		$this->create_podcast = new Create_Podcast();

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_simple_podcasting_create_podcast', array( $this, 'create_podcast' ) );
	}

	/**
	 * Admin enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
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
				'nonce'      => wp_create_nonce( 'simple-podcasting-create-show-action' ),
			)
		);
	}

	/**
	 * AJAX callback to create a podcast.
	 */
	public function create_podcast() {
		$is_nonce_verified = $this->create_podcast->verify_nonce();

		if ( is_wp_error( $is_nonce_verified ) ) {
			wp_send_json_error( $is_nonce_verified->get_error_message() );
		} elseif ( false === $is_nonce_verified ) {
			wp_send_json_error(
				esc_html__( 'Nonce is missing', 'simple-podcasting' )
			);
		}

		// Sanitize the podcast field values.
		$this->create_podcast->sanitize_podcast_fields();

		$is_sanitized = $this->create_podcast->save_podcast_fields();

		if ( is_wp_error( $is_sanitized ) ) {
			wp_send_json_error( $is_sanitized->get_error_message() );
		}

		wp_send_json_success();
	}
}

new Create_Podcast_Component();
