<?php
/**
 * Tests for the RSS feeds
 *
 * @package tenup_podcasting\Tests
 */

namespace tenup_podcasting\Tests;

use function tenup_podcasting\get_podcasting_categories;
use function tenup_podcasting\podcasting_is_enabled;

/**
 * Class Feed_Tests
 *
 * @package tenup_podcasting\Tests
 */
class Feed_Tests extends \WP_UnitTestCase {

	/**
	 * Let's keep the feed for all tests.
	 *
	 * @var \SimpleXMLElement $feed The RSS feed.
	 */
	protected static $feed = null;

	/**
	 * Podcast term for testing
	 *
	 * @var \WP_Term $podcast_term The term
	 */
	protected $podcast_term;

	/**
	 * Post for testing - no post meta assigned
	 *
	 * @var \WP_Post $post_one The testing post
	 */
	protected $post_one;

	/**
	 * Post for testing assigned post meta.
	 *
	 * @var \WP_Post $post_one The testing post
	 */
	protected $post_two;

	/**
	 * Known term meta with default values.
	 *
	 * @var array known term meta
	 */
	protected $podcast_term_meta = array(
		'podcasting_title'       => 'Podcast Title',
		'podcasting_subtitle'    => 'Podcast Subtitle',
		'podcasting_summary'     => 'Podcast Summary',
		'podcasting_talent_name' => 'Jake Goldman',
		'podcasting_email'       => 'test@email.come',
		'podcasting_copyright'   => 'Copyright Data',
		'podcasting_explicit'    => 'yes',
		'podcasting_image'       => 'yes',
		'podcasting_keywords'    => 'Comma, Separated, Keywords',
		'podcasting_category_1'  => 'sports-recreation:amateur',
		'podcasting_category_2'  => 'society-culture:history',
		'podcasting_category_3'  => 'technology:podcasting',
	);

	/**
	 * Known podcast mete key, value pairs
	 *
	 * @var array known podcast meta
	 */
	protected $podcast_episode_meta = array(
		'default' => array(
			'podcast_explicit'  => 'yes',
		),
		'custom'  => array(
			'podcast_explicit'  => 'no',
			'podcast_captioned' => '1',
			'podcast_duration'  => '2:30',
		),
	);

	/**
	 * Setup function.
	 */
	public function setUp() {
		parent::setUp();
		$this->podcast_term = $this->factory()->term->create_and_get(
			array(
				'taxonomy' => 'podcasting_podcasts',
				'name'     => 'My Podcast',
				'slug'     => 'my-podcast',
			)
		);

		// Add the term meta.
		foreach ( $this->podcast_term_meta as $key => $value ) {
			update_term_meta( $this->podcast_term->term_id, $key, $value );
		}

		$this->post_one = $this->factory()->post->create_and_get(
			array(
				'post_title'   => 'Episode One',
				'post_content' => 'Content for Episode One',
			)
		);

		$this->post_two = $this->factory()->post->create_and_get(
			array(
				'post_title'   => 'Episode Two',
				'post_content' => 'Content for Episode Two',
			)
		);

		// Assign the post meta to Post Two.
		foreach ( $this->podcast_episode_meta['custom'] as $key => $value ) {
			add_post_meta( $this->post_two->ID, $key, $value );
		}

		// Add the podcast to the posts.
		wp_set_object_terms( $this->post_one->ID, $this->podcast_term->slug, 'podcasting_podcasts' );
		wp_set_object_terms( $this->post_two->ID, $this->podcast_term->slug, 'podcasting_podcasts' );

		// Get the feed once.
		if ( ! self::$feed ) {
			self::$feed = $this->feed_setup();
		}
	}

	/**
	 * Shamelessly stolen from Core.
	 *
	 * @return false|string
	 * @throws \Exception Throws an exception when there is an issue loading the template.
	 */
	public function do_rss2() {
		ob_start();
		// Nasty hack! In the future it would better to leverage do_feed( 'rss2' ).
		global $post;
		try {
			@require( ABSPATH . WPINC . '/feed-rss2.php' );// @codingStandardsIgnoreLine.
			$out = ob_get_clean();
		} catch ( \Exception $e ) {
			$out = ob_get_clean();
			throw($e);
		}
		return $out;
	}

	/**
	 * Generates the feed as xml.
	 *
	 * @return \SimpleXMLElement
	 * @throws \Exception Throws an exception when there is an issue loading the template.
	 */
	protected function feed_setup() {
		$this->go_to( '/?feed=rss2&podcasting_podcasts=my-podcast' );
		$feed = $this->do_rss2();
		$xml  = new \SimpleXMLElement( $feed );
		return $xml;
	}


	/**
	 * Testing the podcasting is enabled
	 */
	public function test_podcasting_is_enabled() {
		$this->assertTrue( podcasting_is_enabled() );
	}

	/**
	 * Test the channel meta
	 */
	public function test_feed_channel_meta() {
		$channel    = self::$feed->channel;
		$namespaces = $channel->getNameSpaces( true );
		$itunes     = $channel->children( $namespaces['itunes'] );

		// Tests for the non-itunes items in the channel.
		$this->assertSame( $this->podcast_term_meta['podcasting_title'], $channel->title->__toString() );
		// Test the copyright.
		$this->assertSame( $this->podcast_term_meta['podcasting_copyright'], $channel->copyright->__toString() );
		// Tests for the itunes namespace items.
		$this->assertSame( $this->podcast_term_meta['podcasting_subtitle'], $itunes->subtitle->__toString() );
		$this->assertSame( $this->podcast_term_meta['podcasting_summary'], $itunes->summary->__toString() );
		$this->assertSame( $this->podcast_term_meta['podcasting_talent_name'], $itunes->author->__toString() );
		$this->assertSame( $this->podcast_term_meta['podcasting_talent_name'], $itunes->owner->name->__toString() );
		$this->assertSame( $this->podcast_term_meta['podcasting_email'], $itunes->owner->email->__toString() );
		$this->assertSame( $this->podcast_term_meta['podcasting_explicit'], $itunes->explicit->__toString() );
		$this->assertSame( $this->podcast_term_meta['podcasting_keywords'], $itunes->keywords->__toString() );

		// Test the categories.
		$counter    = 1;
		$categories = get_podcasting_categories();
		foreach ( $itunes->category as $category ) {
			$meta        = get_term_meta( $this->podcast_term->term_id, "podcasting_category_{$counter}", true );
			$split       = explode( ':', $meta );
			$parent_cat  = $split[0];
			$child_cat   = $split[1];
			$parent_atts = $category->attributes();
			$this->assertSame( $categories[ $parent_cat ]['name'], $parent_atts[0]->__toString() );
			foreach ( $category as $sub_cat ) {
				$sub_cat_atts = $sub_cat->attributes();
				$this->assertSame( $categories[ $parent_cat ]['subcategories'][ $child_cat ], $sub_cat_atts[0]->__toString() );
			}
			$counter ++;
		}

		// Test that there is a single item.
		$this->assertSame( 2, $channel->item->count() );
	}

	/**
	 * Testing item output.
	 */
	public function test_single_item() {
		$channel    = self::$feed->channel;
		$namespaces = $channel->getNameSpaces( true );

		foreach ( $channel->item as $item ) {
			$itunes = $item->children( $namespaces['itunes'] );

			if ( 'Episode One' === $item->title->__toString() ) {
				// This is set by the term meta.
				$this->assertSame( $this->podcast_episode_meta['default']['podcast_explicit'], $itunes->explicit->__toString() );
			} else {
				// These override the term meta.
				$this->assertSame( $this->podcast_episode_meta['custom']['podcast_explicit'], $itunes->explicit->__toString() );

				$this->assertSame( 'Yes', $itunes->isClosedCaptioned->__toString() ); //@codingStandardsIgnoreLine We can't control the name here.
				$this->assertSame( $this->podcast_episode_meta['custom']['podcast_duration'], $itunes->duration->__toString() );
			}
		}
	}

}
