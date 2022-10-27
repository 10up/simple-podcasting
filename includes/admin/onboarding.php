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
	$terms = get_terms( 'podcasting_podcasts' );

	/** Return if 1 or more podcast(s) already exist. */
	if ( is_wp_error( $terms ) || ( is_array( $terms ) && ! empty( $terms ) ) || ! empty( $terms ) ) {
		return;
	}

	add_submenu_page(
		null,
		esc_html__( 'Simple Podcasting Onboarding' ),
		'',
		'manage_options',
		'simple-podcasting-onboarding',
		'\tenup_podcasting\admin\render_page_contents'
	);
}
add_action( 'admin_menu', 'tenup_podcasting\admin\register_onoarding_page' );

/**
 * Renders the page content for the onboarding wizard.
 */
function render_page_contents() {
	$page = filter_input( INPUT_GET, 'step', FILTER_VALIDATE_INT );

	if ( ! $page ) {
		$page = 1;
	}

	require_once 'views/onboarding-header.php';

	switch ( $page ) {
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
	if ( ! isset( $_POST['simple-podcasting-action'] ) ) {
		return;
	}

	$podcast_name        = isset( $_POST['podcast-name'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-name'] ) ) : null;
	$podcast_description = isset( $_POST['podcast-description'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-description'] ) ) : null;
	$podcast_category    = isset( $_POST['podcast-category'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-category'] ) ) : null;
	$podcast_cover_id    = isset( $_POST['podcast-cover-image-id'] ) ? absint( wp_unslash( $_POST['podcast-cover-image-id'] ) ) : null;

	if ( empty( $podcast_name ) || empty( $podcast_category ) ) {
		return;
	}

	$result = wp_insert_term(
		$podcast_name,
		TAXONOMY_NAME
	);

	if ( is_wp_error( $result ) ) {
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
}
add_action( 'admin_init', '\tenup_podcasting\admin\onboarding_action_handler' );
