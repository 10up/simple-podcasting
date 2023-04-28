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

	public function test_wrap_unwrapped() {
		$test_string = "<time>0:00</time><br><cite>Person Doe</cite><br>Hello World<br><time>0:05</time><br><cite>Person Doe</cite><br>Lorem Ipsum";
		$this->assertSame(
			tenup_podcasting\transcripts\podcasting_wrap_unwrapped_text_in_paragraph( $test_string ),
			'<time>0:00</time><br><cite>Person Doe</cite><br><p>Hello World</p><br><time>0:05</time><br><cite>Person Doe</cite><br><p>Lorem Ipsum</p>'
		);
	}

	public function test_get_transcript_from_post() {
		$post              = Mockery::mock( 'WP_Post' );
		$post->post_name   = 'latest-podcast';
		$terms             = [ new stdClass() ];
		$terms[0]->term_id = 100;

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
