<?php
/**
 * Registers and renders the onboarding setup wizard.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\admin;

/**
 * Adds methods required for handling the onboarding wizard.
 */
class Onboarding {
	/**
	 * Indicates onboarding is in progress.
	 *
	 * @var string
	 */
	const STATUS_IN_PROGRESS = 'in-progress';

	/**
	 * Indicates onboarding is complete.
	 *
	 * @var string
	 */
	const STATUS_COMPLETED = 'completed';

	/**
	 * Holds the object for Create_Podcast.
	 *
	 * @var \tenup_podcasting\Create_Podcast
	 */
	protected $create_podcast;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->create_podcast = new \tenup_podcasting\Create_Podcast();

		add_action( 'admin_menu', array( $this, 'register_onoarding_page' ) );
		add_action( 'admin_init', array( $this, 'onboarding_action_handler' ) );
	}

	/**
	 * Registers a hidden sub menu page for the onboarding wizard.
	 */
	public function register_onoarding_page() {
		add_submenu_page(
			'admin.php',
			esc_html__( 'Simple Podcasting Onboarding' ),
			'',
			'manage_options',
			'simple-podcasting-onboarding',
			array( $this, 'render_page_contents' )
		);

		if ( 'no' === get_option( 'simple_podcasting_onboarding', '' ) ) {
			update_option( 'simple_podcasting_onboarding', self::STATUS_IN_PROGRESS );
			wp_safe_redirect( admin_url( 'admin.php?page=simple-podcasting-onboarding&step=1' ) );
			die();
		}
	}

	/**
	 * Renders the page content for the onboarding wizard.
	 */
	public function render_page_contents() {
		$step = filter_input( INPUT_GET, 'step', FILTER_VALIDATE_INT );

		if ( ! $step ) {
			$step = 1;
		}

		require_once 'views/onboarding-header.php';

		switch ( $step ) {
			case 1:
				require_once 'views/onboarding-page-one.php';
				break;

			case 2:
				require_once 'views/onboarding-page-two.php';
				break;

			default:
				break;
		}
	}

	/**
	 * Onboarding data saving handler.
	 */
	public function onboarding_action_handler() {
		if ( ! $this->create_podcast->verify_nonce() ) {
			return;
		}

		$this->create_podcast->sanitize_podcast_fields();

		$is_sanitized = $this->create_podcast->save_podcast_fields();

		if ( is_wp_error( $is_sanitized ) ) {
			$error_message = $is_sanitized->get_error_message();

			add_action(
				'admin_notices',
				function () use ( $error_message ) {
					if ( empty( $error_message ) ) {
						return;
					}
					?>
					<div class="notice notice-error is-dismissible">
						<p><?php echo wp_kses_post( $error_message ); ?></p>
					</div>
					<?php
				}
			);

			return;
		}

		if ( self::STATUS_IN_PROGRESS === get_option( 'simple_podcasting_onboarding', '' ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=simple-podcasting-onboarding&step=2' ) );
			die;
		}
	}
}

$onbarding_status = get_option( 'simple_podcasting_onboarding', '' );

if ( Onboarding::STATUS_COMPLETED !== $onbarding_status ) {
	new Onboarding();
}
