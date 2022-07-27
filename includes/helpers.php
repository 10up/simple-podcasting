<?php
/**
 * Common helper functions
 *
 * @package tenup_podcasting\helpers
 */

namespace tenup_podcasting\helpers;

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
		delete_post_meta( $post_id, 'podcast_url' );
		delete_post_meta( $post_id, 'podcast_filesize' );
		delete_post_meta( $post_id, 'podcast_duration' );
		delete_post_meta( $post_id, 'podcast_mime' );
		delete_post_meta( $post_id, 'podcast_captioned' );
		delete_post_meta( $post_id, 'podcast_explicit' );
		delete_post_meta( $post_id, 'enclosure' );
		delete_post_meta( $post_id, 'podcast_season_number' );
		delete_post_meta( $post_id, 'podcast_episode_number' );
		delete_post_meta( $post_id, 'podcast_episode_type' );
	}
}
