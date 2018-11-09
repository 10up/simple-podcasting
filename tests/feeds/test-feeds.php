<?php
/**
 * Tests for the RSS feeds
 *
 * @package tenup_podcasting\Tests
 */

namespace tenup_podcasting\Tests;

/**
 * Class Feed_Tests
 *
 * @package tenup_podcasting\Tests
 */
class Feed_Tests extends \WP_UnitTestCase {

	static $podcast_term;
	static $post;
	
	public static function wpSetUpBeforeClass( $factory ) {
		self::$podcast_term = self::factory()->term->create_and_get(
			array(
				'taxonomy' => 'podcasting_podcasts',
				'name'     => 'My Podcast',
				'slug'     => 'my-podcast',
			)
		);

		add_term_meta( self::$podcast_term->term_id, 'podcasting_subtitle', 'Subtitle' );

		self::$post = self::factory()->post->create_and_get(
			array(
				'post_title'   => 'Episode One',
				'post_content' => 'Content for Episode One',
			)
		);
		// Add the podcast to the post.
		wp_set_object_terms( self::$post->ID, self::$podcast_term->slug, 'podcasting_podcasts' );
	}
	/**
	 * Setup function.
	 */
	public function setUp() {
		parent::setUp();
	}

	/**
	 * Shamelessly stolen from Core.
	 *
	 * @return false|string
	 * @throws \Exception Throws an exception when there is an issue loading the template.
	 */
	function do_rss2() {
		ob_start();
		// Nasty hack! In the future it would better to leverage do_feed( 'rss2' ).
		global $post;
		try {
			@require( ABSPATH . WPINC . '/feed-rss2.php' );
			$out = ob_get_clean();
		} catch ( Exception $e ) {
			$out = ob_get_clean();
			throw($e);
		}
		return $out;
	}

	/**
	 * Generates the feed as xml.
	 *
	 * @return array
	 * @throws \Exception Throws an exception when there is an issue loading the template.
	 */
	protected function feed_setup() {
		$this->go_to( '/?feed=rss2&podcasting_podcasts=my-podcast' );
		$feed = $this->do_rss2();
		$xml  = new \SimpleXMLElement( $feed );
		return $xml;
	}


	/**
	 * Tests the feed meta data
	 */
	public function test_feed_meta() {
		$xml    = $this->feed_setup();
		$itunes = $xml->xPath( '//item' );
		var_dump($itunes);
		
		//var_dump( $xml->channel );
		
	}
}
