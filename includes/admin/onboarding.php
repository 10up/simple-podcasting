<?php
/**
 * Registers and renders the onboarding setup wizard.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\admin;

add_action( 'admin_menu', 'tenup_podcasting\admin\register_onoarding_page' );

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
}

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

