<?php
/**
 * Defines class to handle the validation, sanitization
 * and creation of a podcast.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting;

/**
 * Provides methods to create a podcast.
 */
class Create_Podcast {
	/**
	 * Name of the podcast.
	 *
	 * @var string
	 */
	protected $podcast_name = '';

	/**
	 * Name of the podcast artist.
	 *
	 * @var string
	 */
	protected $podcast_talent_name = '';

	/**
	 * Summary of the podcast.
	 *
	 * @var string
	 */
	protected $podcast_description = '';

	/**
	 * Podcast's primary category.
	 *
	 * @var string
	 */
	protected $podcast_category = '';

	/**
	 * ID of the podcast cover.
	 *
	 * @var int
	 */
	protected $podcast_cover_id = 0;

	/**
	 * Verifies nonce needed for the creation of a podcast.
	 *
	 * @return boolean|WP_Error
	 */
	public function verify_nonce() {
		$is_nonce_set = isset( $_POST['simple-podcasting-create-show-nonce-field'] );

		if ( ! $is_nonce_set ) {
			return false;
		}

		$is_nonce_verified = wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['simple-podcasting-create-show-nonce-field'] ) ), 'simple-podcasting-create-show-action' );

		if ( ! $is_nonce_verified ) {
			return new \WP_Error(
				'simple_podcasting_nonce_verification_failed',
				esc_html__( 'Nonce verification failed.' )
			);
		}

		return true;
	}

	/**
	 * Sanitizes the podcast fields.
	 */
	public function sanitize_podcast_fields() {
		$this->podcast_name        = isset( $_POST['podcast-name'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-name'] ) ) : null;
		$this->podcast_talent_name = isset( $_POST['podcast-artist'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-artist'] ) ) : null;
		$this->podcast_description = isset( $_POST['podcast-description'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-description'] ) ) : null;
		$this->podcast_category    = isset( $_POST['podcast-category'] ) ? sanitize_text_field( wp_unslash( $_POST['podcast-category'] ) ) : null;
		$this->podcast_cover_id    = isset( $_POST['podcast-cover-image-id'] ) ? absint( wp_unslash( $_POST['podcast-cover-image-id'] ) ) : null;
	}

	/**
	 * Create a podcast and saves its corressponding meta.
	 *
	 * @return boolean|WP_Error
	 */
	public function save_podcast_fields() {
		if ( empty( $this->podcast_name ) ) {
			return new \WP_Error(
				'simple_podcasting_podcast_name_empty',
				esc_html__( 'A podcast name is required.' )
			);
		}

		if ( empty( $this->podcast_talent_name ) ) {
			return new \WP_Error(
				'simple_podcasting_podcast_artist_name_empty',
				esc_html__( 'A podcast artist name is required.' )
			);
		}

		if ( empty( $this->podcast_description ) ) {
			return new \WP_Error(
				'simple_podcasting_podcast_summary_empty',
				esc_html__( 'A podcast summary name is required.' )
			);
		}

		if ( empty( $this->podcast_category ) ) {
			return new \WP_Error(
				'simple_podcasting_podcast_category_empty',
				esc_html__( 'A podcast category is required.' )
			);
		}

		if ( empty( $this->podcast_cover_id ) ) {
			return new \WP_Error(
				'simple_podcasting_podcast_cover_image_empty',
				esc_html__( 'A podcast cover image is required.' )
			);
		}

		$result = wp_insert_term(
			$this->podcast_name,
			PODCASTING_TAXONOMY_NAME
		);

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		/** Add podcast talent name. */
		if ( $this->podcast_talent_name ) {
			update_term_meta( $result['term_id'], 'podcasting_talent_name', $this->podcast_talent_name );
		}

		/** Add podcast summary. */
		if ( $this->podcast_description ) {
			update_term_meta( $result['term_id'], 'podcasting_summary', $this->podcast_description );
		}

		/** Add podcast category. */
		if ( $this->podcast_category ) {
			update_term_meta( $result['term_id'], 'podcasting_category_1', $this->podcast_category );
		}

		/** Add podcast cover ID and URL. */
		if ( $this->podcast_cover_id ) {
			$image_url = wp_get_attachment_url( (int) $this->podcast_cover_id );
			update_term_meta( $result['term_id'], 'podcasting_image', $this->podcast_cover_id );
			update_term_meta( $result['term_id'], 'podcasting_image_url', $image_url );
		}

		return true;
	}
}
