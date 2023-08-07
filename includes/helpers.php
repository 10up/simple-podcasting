<?php
/**
 * Common helper functions
 *
 * @package tenup_podcasting\helpers
 */

namespace tenup_podcasting\helpers;

const PODCAST_META_KEYS = array(
	'podcast_url',
	'podcast_filesize',
	'podcast_duration',
	'podcast_mime',
	'podcast_captioned',
	'podcast_explicit',
	'enclosure',
	'podcast_season_number',
	'podcast_episode_number',
	'podcast_episode_type',
);

/**
 * Retrieve the enclosure and return the meta
 *
 * @param string $url The podcast url.
 *
 * @return array
 */
function get_podcast_meta_from_url( $url ) {

	// Is the required when calling this from outside of the admin.
	if ( ! is_admin() ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
	}
	// Modeled after WordPress do_enclose().
	$podcast_meta = array();
	$headers      = \wp_get_http_headers( $url );
	if ( $headers ) {
		if ( ! empty( $headers['location'] ) ) {
			$headers = \wp_get_http_headers( $headers['location'] );
		}

		// Grab a temporary copy of the file to determine the audio duration.
		$temp_file = \download_url( $url, 30 );
		$meta_data = \wp_read_audio_metadata( $temp_file );
		$duration  = isset( $meta_data['length_formatted'] ) ? $meta_data['length_formatted'] : false;

		$len           = isset( $headers['content-length'] ) ? (int) $headers['content-length'] : 0;
		$type          = isset( $headers['content-type'] ) ? $headers['content-type'] : '';
		$allowed_types = array( 'video', 'audio' );

		// Check to see if we can figure out the mime type from the extension.
		$url_parts = \wp_parse_url( $url );
		if ( false !== $url_parts ) {
			$extension = \pathinfo( $url_parts['path'], PATHINFO_EXTENSION );
			if ( ! empty( $extension ) ) {
				foreach ( \wp_get_mime_types() as $exts => $mime ) {
					if ( preg_match( '!^(' . $exts . ')$!i', $extension ) ) {
						$type = $mime;
						break;
					}
				}
			}
		}

		if ( in_array( substr( $type, 0, strpos( $type, '/' ) ), $allowed_types, true ) ) {
			$podcast_meta['url']      = esc_url_raw( $url );
			$podcast_meta['mime']     = $type;
			$podcast_meta['duration'] = $duration;
			$podcast_meta['filesize'] = $len;
		}

		return $podcast_meta;
	}
}

/**
 * Delete all podcast meta for a post.
 *
 * @param int $post_id Post ID.
 */
function delete_all_podcast_meta( $post_id ) {
	if ( metadata_exists( 'post', $post_id, 'podcast_url' ) ) {
		foreach ( PODCAST_META_KEYS as $key ) {
			delete_post_meta( $post_id, $key );
		}
	}
}

/**
 * Get all the meta of a podcast
 *
 * @param int $post_id The post ID tha contains the podcast
 * @return array
 */
function get_all_podcast_meta( $post_id ) {
	$meta = array();
	foreach ( PODCAST_META_KEYS as $key ) {
		$meta[ $key ] = get_post_meta( $post_id, $key, true );
	}

	$terms = wp_get_post_terms( $post_id, 'podcasting_podcasts' );
	$terms = is_array( $terms ) ? array_column( $terms, 'name' ) : array();

	// Add some extra data for convenience
	$meta['post_id']       = $post_id;
	$meta['thumbnail_url'] = get_the_post_thumbnail_url( $post_id );
	$meta['podcast_title'] = get_the_title( $post_id );
	$meta['podcast_terms'] = $terms;

	return $meta;
}

/**
 * Get all episodes in a season
 *
 * @param int    $season_number The season number
 * @param string $post_type     The post type
 * @return array
 */
function get_episodes_in_season( $season_number, $post_type ) {
	if ( ! is_numeric( $season_number ) && empty( $season_number ) ) {
		return array();
	}

	$posts = get_posts(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => 'podcast_season_number',
					'value'   => $season_number,
					'compare' => '=',
				),
			),
		)
	);

	$podcasts = array();
	foreach ( $posts as $post ) {
		$podcasts[] = get_all_podcast_meta( $post->ID );
	}

	// Sort episodes based on episode number
	$indexes = array_column( $podcasts, 'podcast_episode_number' );
	natcasesort( $indexes );

	// Rebuild array as per sorted episodes
	$episodes = array();
	foreach ( $indexes as $index ) {
		foreach ( $podcasts as $podcast ) {
			if ( $index === $podcast['podcast_episode_number'] ) {
				$episodes[] = $podcast;
			}
		}
	}

	return $episodes;
}

/**
 * Get playlist by episode
 *
 * @param int $post_id The post that contains the podcast
 * @return array
 */
function get_playlist_by_episode( $post_id ) {
	$post_type = get_post_type( $post_id );
	$podcast   = get_all_podcast_meta( $post_id );
	$episodes  = get_episodes_in_season( $podcast['podcast_season_number'] ?? null, $post_type );
	$episodes  = empty( $episodes ) ? array( $podcast ) : $episodes;
	$current   = 0;

	// Determine the current index in the playlist
	foreach ( $episodes as $index => $episode ) {
		if ( $episode['post_id'] == $post_id ) {
			$current = $index;
			break;
		}
	}

	return array(
		'playlist' => $episodes,
		'current'  => $current,
	);
}
