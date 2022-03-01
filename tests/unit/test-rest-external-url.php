<?php

use PHPUnit\Framework\TestCase;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class RestExternalUrlTests extends TestCase {
	/**
	 * Set up our mocked WP functions. Rather than setting up a database we can mock the returns of core WordPress functions.
	 *
	 * @return void
	 */
	public function setUp() {
		\WP_Mock::setUp();
	}
	/**
	 * Tear down WP Mock.
	 *
	 * @return void
	 */
	public function tearDown() {
		\WP_Mock::tearDown();
	}

	public function test_handle_request_cached() {
		$url = 'https://example.com';

		$mock_request = \Mockery::mock( '\WP_REST_Request, ArrayAccess' );

		$mock_request->shouldReceive( 'offsetGet' )
			->with( 'url' )
			->andReturn( $url );

		$mock_request->shouldReceive( 'offsetExists' )
			->with( 'url' )
			->andReturn( true );

		$cache_key = 'spc_external_url_' . $url;

		\WP_Mock::userFunction( 'get_transient' )
			->with( $cache_key )
			->andReturn( 'Podcast Meta' );

		\WP_Mock::passthruFunction( 'rest_ensure_response' );

		$response = array(
			'success' => true,
			'data'    => 'Podcast Meta',
		);

		$this->assertSame(
			$response,
			tenup_podcasting\endpoints\externalurl\handle_request( $mock_request ),
			'handle_request() should return cached result'
		);
	}
}
