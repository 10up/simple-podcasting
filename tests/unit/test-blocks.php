<?php

use PHPUnit\Framework\TestCase;

/**
 * The AddressTests class tests the functions associated with an address associated with an invoice.
 */
class BlockTests extends TestCase {
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

	public function test_init() {
		$block_asset = require PODCASTING_PATH . 'dist/blocks.asset.php';

		\WP_Mock::userFunction(
			'wp_register_script',
			array(
				'times' => 1,
				'args'  => array(
					'podcasting-block-editor',
					PODCASTING_URL . 'dist/blocks.js',
					$block_asset['dependencies'],
					$block_asset['version'],
					true,
				),
			)
		);

		\WP_Mock::userFunction(
			'register_block_type',
			array(
				'times' => 1,
				'args'  => array(
					'podcasting/podcast',
					array(
						'editor_script' => 'podcasting-block-editor',
					),
				),
			)
		);

		$result = tenup_podcasting\block\init();

		$this->assertNull( $result );
	}

	public function test_register_js_strings() {
		\WP_Mock::userFunction( '__', array( 'times' => 4 ) );

		$result = tenup_podcasting\block\register_js_strings();
		$this->assertNull( $result );
	}

	public function test_block_editor_meta_cleanup() {
		// $post     = new stdClass();
		// $post->ID = 42;

		// $request = null;

		// WP_Mock::userFunction(
		// 	'has_block',
		// 	array(
		// 		'times'           => 2,
		// 		'args'            => array( 'podcasting/podcast', $post->ID ),
		// 		'return_in_order' => array( true, false ),
		// 	)
		// );

		// tenup_podcasting\block\block_editor_meta_cleanup( $post, $request, true );
		// tenup_podcasting\block\block_editor_meta_cleanup( $post, $request, false );
		// tenup_podcasting\block\block_editor_meta_cleanup( $post, $request, false );

		$this->assertNull( null );
	}
}
