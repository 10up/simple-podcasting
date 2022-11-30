<?php
/**
 * Registers and renders the onboarding setup wizard.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\admin;

/**
 * Registers a hidden sub menu page for the onboarding wizard.
 */
function register_onoarding_page() {
	add_submenu_page(
		null,
		esc_html__( 'Simple Podcasting Onboarding' ),
		'',
		'manage_options',
		'simple-podcasting-onboarding',
		'\tenup_podcasting\admin\render_page_contents'
	);

	if ( 'no' === get_option( 'simple_podcasting_onboarding', '' ) ) {
		update_option( 'simple_podcasting_onboarding', 'in-progress' );
		wp_safe_redirect( admin_url( 'admin.php?page=simple-podcasting-onboarding&step=1' ) );
		die();
	}
}
add_action( 'admin_menu', 'tenup_podcasting\admin\register_onoarding_page' );

/**
 * Renders the page content for the onboarding wizard.
 */
function render_page_contents() {
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
function onboarding_action_handler() {
	if ( ! isset( $_POST['simple-podcasting-onboarding-nonce'] )
	|| ! wp_verify_nonce( $_POST['simple-podcasting-onboarding-nonce'], 'simple-podcasting-create-show-action' )
	) {
		return;
	}

	$podcast_name        = isset( $_POST['podcast-name'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-name'] ) ) : null;
	$podcast_description = isset( $_POST['podcast-description'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-description'] ) ) : null;
	$podcast_category    = isset( $_POST['podcast-category'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-category'] ) ) : null;
	$podcast_cover_id    = isset( $_POST['podcast-cover-image-id'] ) ? absint( wp_unslash( $_POST['podcast-cover-image-id'] ) ) : null;

	if ( empty( $podcast_name ) || empty( $podcast_category ) ) {
		add_action(
			'admin_notices',
			function() use ( $podcast_name, $podcast_category ) {
				?>
			<div class="notice notice-error is-dismissible">
				<?php if ( '' === $podcast_name ) : ?>
					<p><?php esc_html_e( 'Show name is required.', 'simple-podcasting' ); ?></p>
				<?php endif; ?>

				<?php if ( '' === $podcast_category ) : ?>
					<p><?php esc_html_e( 'Podcast category is required.', 'simple-podcasting' ); ?></p>
				<?php endif; ?>
			</div>
				<?php
			}
		);

		return;
	}

	$result = wp_insert_term(
		$podcast_name,
		TAXONOMY_NAME
	);

	if ( is_wp_error( $result ) ) {
		add_action(
			'admin_notices',
			function() use ( $result ) {
				?>
			<div class="notice notice-error is-dismissible">
				<p><?php printf( esc_html__( 'Taxonomy error: %s', 'simple-podcasting' ), esc_html( $result->get_error_message() ) ); ?></p>
			</div>
				<?php
			}
		);
		return;
	}

	/** Add podcast summary. */
	if ( $podcast_description ) {
		update_term_meta( $result['term_id'], 'podcasting_summary', $podcast_description );
	}

	/** Add podcast category. */
	if ( $podcast_category ) {
		update_term_meta( $result['term_id'], 'podcasting_category_1', $podcast_category );
	}

	/** Add podcast cover ID and URL. */
	if ( $podcast_cover_id ) {
		$image_url = wp_get_attachment_url( (int) $podcast_cover_id );
		update_term_meta( $result['term_id'], 'podcasting_image', $podcast_cover_id );
		update_term_meta( $result['term_id'], 'podcasting_image_url', $image_url );
	}

	if ( 'in-progress' === get_option( 'simple_podcasting_onboarding', '' ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=simple-podcasting-onboarding&step=2' ) );
		die;
	}
}
add_action( 'admin_init', '\tenup_podcasting\admin\onboarding_action_handler' );
