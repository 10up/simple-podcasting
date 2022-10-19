<?php

use PHPUnit\Framework\TestCase;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class HelpersTests extends TestCase {
	/**
	 * Set up our mocked WP functions. Rather than setting up a database we can mock the returns of core WordPress functions.
	 *
	 * @return void
	 */
	public function setUp() : void {
		\WP_Mock::setUp();
	}
	/**
	 * Tear down WP Mock.
	 *
	 * @return void
	 */
	public function tearDown() : void {
		\WP_Mock::tearDown();
	}

	public function test_delete_all_podcast_meta() {
		\WP_Mock::userFunction(
			'metadata_exists',
			array(
				'return_in_order' => array( false, true ),
			)
		);

		$this->assertNull( tenup_podcasting\helpers\delete_all_podcast_meta( 42 ) );

		$meta_keys = array( 'podcast_url', 'podcast_filesize', 'podcast_duration', 'podcast_mime', 'podcast_captioned', 'podcast_explicit', 'enclosure', 'podcast_season_number', 'podcast_episode_number', 'podcast_episode_type' );
		foreach ( $meta_keys as $meta_key ) {
			\WP_Mock::userFunction( 'delete_post_meta' )->with( 42, $meta_key );
		}

		$this->assertNull( tenup_podcasting\helpers\delete_all_podcast_meta( 42 ) );
	}

	/**
	 * @dataProvider data_provider_for_test_get_podcast_meta_from_url
	 * @covers tenup_podcasting\helpers\get_podcast_meta_from_url
	 */
	public function test_get_podcast_meta_from_url( $url, $redirect, $headers, $audio_metadata, $expected ) {
		\WP_Mock::userFunction( 'is_admin' )->andReturn( true );

		if ( $redirect ) {
			\WP_Mock::userFunction( 'wp_get_http_headers' )->with( $url )->andReturn( array( 'location' => $redirect ) );
			\WP_Mock::userFunction( 'wp_get_http_headers' )->with( $redirect )->andReturn( $headers );
		} else {
			\WP_Mock::userFunction( 'wp_get_http_headers' )->with( $url )->andReturn( $headers );
		}

		\WP_Mock::userFunction( 'download_url' )->with( $url, 30 )->andReturn( 'downloaded_file_blob' );
		\WP_Mock::userFunction( 'wp_read_audio_metadata' )->with( 'downloaded_file_blob' )->andReturn( $audio_metadata );
		\WP_Mock::userFunction( 'wp_parse_url' )->andReturnUsing(
			function( $url ) {
				return parse_url( $url );
			}
		);
		\WP_Mock::userFunction( 'wp_get_mime_types' )->andReturn( array( 'mp3|mp4' => 'audio/mpeg' ) );

		$meta = tenup_podcasting\helpers\get_podcast_meta_from_url( $url );
		$this->assertSame( $expected, $meta );
	}

	public function data_provider_for_test_get_podcast_meta_from_url() {
		return array(
			'Should follow redirect' => array(
				'url'            => 'https://simple-podcasting.test/correct.mp3',
				'redirect'       => 'https://simple-podcasting.test/redirected.mp3',
				'headers'        => array(
					'content-length' => 42000,
					'content-type'   => 'audio/mpeg',
				),
				'audio_metadata' => array( 'length_formatted' => '0:42' ),
				'expected' => array(
					'url' => 'https://simple-podcasting.test/correct.mp3',
					'mime' => 'audio/mpeg',
					'duration' => '0:42',
					'filesize' => 42000
				),
			),
			'Should extract metadata' => array(
				'url'            => 'https://simple-podcasting.test/correct.mp3',
				'redirect'       => false,
				'headers'        => array(
					'content-length' => 42000,
					'content-type'   => 'audio/mpeg',
				),
				'audio_metadata' => array( 'length_formatted' => '0:42' ),
				'expected' => array(
					'url' => 'https://simple-podcasting.test/correct.mp3',
					'mime' => 'audio/mpeg',
					'duration' => '0:42',
					'filesize' => 42000
				),
			),
		);
	}
}
