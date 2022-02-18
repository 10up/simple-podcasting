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
		\WP_Mock::userFunction( 'metadata_exists', array(
			'return_in_order' => array( false, true )
		) );

		$this->assertNull(tenup_podcasting\helpers\delete_all_podcast_meta( 42 ));

		$meta_keys = array('podcast_url', 'podcast_filesize', 'podcast_duration', 'podcast_mime', 'podcast_captioned', 'podcast_explicit');
		foreach ( $meta_keys as $meta_key ) {
			\WP_Mock::userFunction( 'delete_post_meta' )->with( 42, $meta_key );
		}

		$this->assertNull(tenup_podcasting\helpers\delete_all_podcast_meta( 42 ));
	}
}
