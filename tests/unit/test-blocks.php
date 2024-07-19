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
			'wp_register_style',
			array(
				'times' => 1,
				'args'  => array(
					'podcasting-block-editor',
					PODCASTING_URL . 'dist/blocks.css',
					array(),
					$block_asset['version'],
					'all',
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
						'editor_style'  => 'podcasting-block-editor',
						'render_callback' => 'tenup_podcasting\block\render',
					),
				),
			)
		);

		$result = tenup_podcasting\block\init();

		$this->assertNull( $result );
	}

	public function test_register_js_strings() {
		\WP_Mock::userFunction( '__', array( 'times' => 7 ) );

		$result = tenup_podcasting\block\register_js_strings();
		$this->assertNull( $result );
	}

	/**
	 * @dataProvider data_provider_for_test_block_editor_meta_cleanup
	 */
	public function test_block_editor_meta_cleanup( $creating, $has_block, $metadata_exists, $expected ) {
		$post     = new stdClass();
		$post->ID = 42;

		\WP_Mock::userFunction( 'has_block' )
			->with( 'podcasting/podcast', $post->ID )
			->andReturn( $has_block );

		\WP_Mock::userFunction( 'metadata_exists' )
			->with( 'post', $post->ID, 'podcast_url' )
			->andReturn( $metadata_exists );

		if ( is_array( $expected ) ) {
			foreach ( $expected as $meta_key ) {
				\WP_Mock::userFunction( 'delete_post_meta' )->once()->with( 42, $meta_key );
			}
		}

		$this->assertNull( tenup_podcasting\block\block_editor_meta_cleanup( $post, null, $creating ) );
	}

	public function data_provider_for_test_block_editor_meta_cleanup() {
		return array(
			'Don\'t delete meta if creating post' => array(
				'creating'        => true,
				'has_block'       => false,
				'metadata_exists' => false,
				'expected'        => null,
			),
			'Don\'t delete meta if has block'     => array(
				'creating'        => false,
				'has_block'       => true,
				'metadata_exists' => false,
				'expected'        => null,
			),
			'Don\'t delete meta if no metadata'   => array(
				'creating'        => false,
				'has_block'       => false,
				'metadata_exists' => false,
				'expected'        => null,
			),
			'Delete 10 metas'                      => array(
				'creating'        => false,
				'has_block'       => false,
				'metadata_exists' => true,
				'expected'        => array( 'podcast_url', 'podcast_filesize', 'podcast_duration', 'podcast_mime', 'podcast_captioned', 'podcast_explicit', 'enclosure', 'podcast_season_number', 'podcast_episode_number', 'podcast_episode_type' ),
			),
		);
	}
}
