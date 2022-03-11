<?php

use PHPUnit\Framework\TestCase;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class DatatypesTests extends TestCase {
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

	/**
	 * @dataProvider data_provider_for_test_filter_parent_file
	 */
	public function test_filter_parent_file( $screen, $original_file, $expected_file )
	{
		\WP_Mock::userFunction( 'get_current_screen', array(
			'return' => $screen
		));

		$this->assertSame( $expected_file, tenup_podcasting\filter_parent_file( $original_file ) );
	}

	public function data_provider_for_test_filter_parent_file()
	{
		return array(
			'Edit tags screen for podcasting' => array(
				'screen' => (object) array(
					'base' => 'edit-tags',
					'taxonomy' => 'podcasting_podcasts',
				),
				'original_file' => 'original.php',
				'expected_file' => 'edit-tags.php?taxonomy=podcasting_podcasts&amp;podcasts=true',
			),
			'Term screen for podcasting' => array(
				'screen' => (object) array(
					'base' => 'term',
					'taxonomy' => 'podcasting_podcasts',
				),
				'original_file' => 'original.php',
				'expected_file' => 'edit-tags.php?taxonomy=podcasting_podcasts&amp;podcasts=true',
			),
			'Other screen for podcasting' => array(
				'screen' => (object) array(
					'base' => 'other',
					'taxonomy' => 'podcasting_podcasts',
				),
				'original_file' => 'original.php',
				'expected_file' => 'original.php',
			),
			'Edit tags screen for other taxonomy' => array(
				'screen' => (object) array(
					'base' => 'edit-tags',
					'taxonomy' => 'other',
				),
				'original_file' => 'original.php',
				'expected_file' => 'original.php',
			),
			'Other screen for other taxonomy' => array(
				'screen' => (object) array(
					'base' => 'other',
					'taxonomy' => 'other',
				),
				'original_file' => 'original.php',
				'expected_file' => 'original.php',
			),
		);
	}
}
