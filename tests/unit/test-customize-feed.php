<?php

use PHPUnit\Framework\TestCase;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class CustomizeFeedTests extends TestCase {
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

	public function test_get_the_term() {
		$empty_object          = new stdClass();
		$empty_object->term_id = false;

		$queried_object          = new stdClass();
		$queried_object->term_id = 42;

		\WP_Mock::userFunction(
			'get_queried_object',
			array(
				'times'           => 3,
				'return_in_order' => array( null, $empty_object, $queried_object ),
			)
		);

		$this->assertFalse(
			tenup_podcasting\get_the_term(),
			'tenup_podcasting\get_the_term() should return false if no queried object.'
		);

		$this->assertFalse(
			tenup_podcasting\get_the_term(),
			'tenup_podcasting\get_the_term() should return false if queried object is not a term.'
		);

		$this->assertEquals(
			tenup_podcasting\get_the_term(),
			$queried_object,
			'tenup_podcasting\get_the_term() should return queried object.'
		);
	}

	public function test_empty_rss_excerpt() {
		\WP_Mock::userFunction(
			'get_the_excerpt',
			array(
				'times'           => 2,
				'return_in_order' => array( '', 'excerpt' ),
			)
		);

		$this->assertEmpty(
			tenup_podcasting\empty_rss_excerpt( 'Non-empty' ),
			'tenup_podcasting\empty_rss_excerpt() should return empty string if the excerpt is empty.'
		);

		$this->assertEquals(
			tenup_podcasting\empty_rss_excerpt( 'Non-empty' ),
			'Non-empty',
			'tenup_podcasting\empty_rss_excerpt() should return original content if the excerpt is not empty.'
		);
	}

	public function test_pre_get_posts_no_feed() {
		$query_mock = \Mockery::mock( '\WP_Query' );
		$query_mock->shouldReceive( 'is_feed' )->andReturn( false );
		$query_mock->shouldNotReceive( 'set' );

		$this->assertNull(
			tenup_podcasting\pre_get_posts( $query_mock ),
			'tenup_podcasting\pre_get_posts() should not make updates to WP_Query if not in a feed loop.'
		);
	}

	public function test_pre_get_posts_feed()
	{
		$query_mock = \Mockery::mock('\WP_Query');

		$query_mock->shouldReceive('is_feed')->andReturn( true );

		\WP_Mock::onFilter( 'simple_podcasting_episodes_per_page' )
			->with( PODCASTING_ITEMS_PER_PAGE )
			->reply( 100 );

		$query_mock->shouldReceive('set')->with( 'posts_per_rss', 100 );

		$this->assertNull(
			tenup_podcasting\pre_get_posts( $query_mock ),
			'tenup_podcasting\pre_get_posts() should update WP_Query->posts_per_rss with filtered value.'
		);
	}
}
