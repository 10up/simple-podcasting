<?php
/**
 * Register block patterns
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\block_patterns;

/**
 * Helper to get block patterns template
 *
 * @param string $template_name The name of template
 */
function get_pattern_content( $template_name ) {
	$path = PODCASTING_PATH . "includes/block-patterns/{$template_name}.php";
	if ( file_exists( $path ) ) {
		ob_start();
		require $path;
		return ob_get_clean();
	}
	return '';
}

/**
 * Register block patterns for podcasting posts
 */
function register_poscasting_block_patterns() {

	register_block_pattern(
		'podcasting/latest-podcast',
		array(
			'title'   => __( 'Latest Podcasts', 'simple-podcasting' ),
			'content' => get_pattern_content( 'latest-podcasts' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_poscasting_block_patterns' );
