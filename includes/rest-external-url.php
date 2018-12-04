<?php
/**
 * Endpoint definitions.
 *
 * @package tenup_podcasting\endpoints
 */

namespace tenup_podcasting\endpoints\externalurl;

/**
 * Hook into admin_init action
 *
 * @since 0.1.0
 *
 * @uses add_action()
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'rest_api_init', $n( 'define_endpoint_for_external_files_meta_check' ) );
}

/**
 * Define the endpoint being used to retrieve and fill files ize, mime, and duration for external URLs
 */
function define_endpoint_for_external_files_meta_check() {

	register_rest_route(
		'simple-podcasting/v1',
		'external-url',
		array(
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => __NAMESPACE__ . '\handle_request',
			'permission_callback' => function () {
				return true;
			},
			'args'                => array(
				'url' => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}

/**
 * Callbakc for the external-url endpoint.
 *
 * @param \WP_REST_Request $request The API request
 *
 * @return mixed|\WP_REST_Response
 */
function handle_request( \WP_REST_Request $request ) {

	$url          = $request['url'];
	$cache_key    = 'spc_external_url_' . $url;
	$podcast_meta = get_transient( $cache_key );
	if ( false === $podcast_meta ) {
		if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
			$podcast_meta = \tenup_podcasting\helpers\get_podcast_meta_from_url( $url );
			if ( $podcast_meta ) {
				$response = array(
					'success' => true,
					'data'    => $podcast_meta,
				);
				set_transient( $cache_key, $podcast_meta, MONTH_IN_SECONDS ); // We add the long expiry so we don't autoload the option in a non-object-cached env.
			}
		} else {
			$response = array(
				'success' => false,
				'message' => 'Invalid URL parameter passed',
			);
		}
	} else {
		$response = array(
			'success' => true,
			'data'    => $podcast_meta,
		);
	}
	return rest_ensure_response( $response );
}
