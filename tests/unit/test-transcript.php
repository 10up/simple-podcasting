<?php

use PHPUnit\Framework\TestCase;

/**
 * The TranscriptTests class tests the functions associated with a transcript.
 */
class TranscriptTests extends TestCase {
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

	public function test_get_transcript_from_post() {
		$post              = Mockery::mock( 'WP_Post' );
		$post->post_name   = 'latest-podcast';
		$terms             = [ new stdClass() ];
		$terms[0]->term_id = 100;

		\WP_Mock::userFunction( 'get_post' )->andReturn( $post );
		\WP_Mock::userFunction( 'get_the_terms' )->andReturn( $terms );
		\WP_Mock::userFunction( 'get_term_link' )->andReturn( 'https://simple-podcasting.test/podcasts/test-podcast/' );
		\WP_Mock::userFunction( 'trailingslashit' )->andReturnUsing( function( $url ) {
			return rtrim( $url, '/\\' ) . '/';
		} );

		$this->assertSame(
			tenup_podcasting\transcripts\get_transcript_link_from_post( $post ),
			'https://simple-podcasting.test/podcasts/test-podcast/latest-podcast/transcript/'
		);
	}
}
