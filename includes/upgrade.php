<?php
/**
 * Upgrade routines using options.
 *
 * @package tenup_podcasting\upgrade;
 */

namespace tenup_podcasting\upgrade;

/**
 * Flush rewrite rules on version 2.0
 *
 * @return void
 */
function maybe_flush_rewrite() {
	$version = get_option( 'simple_podcasting_db_version' );
	if ( ! $version || version_compare( '2.0', $version, '>=' ) ) {
		flush_rewrite_rules();
		update_option( 'simple_podcasting_db_version', PODCASTING_VERSION );
	}
}

add_filter( 'admin_init', __NAMESPACE__ . '\\maybe_flush_rewrite', 10, 1 );
