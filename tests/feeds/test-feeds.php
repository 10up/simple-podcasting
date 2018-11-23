<?php
/**
 * Tests for the RSS feeds
 *
 * @package tenup_podcasting\Tests
 */

namespace tenup_podcasting\Tests;

use function tenup_podcasting\get_podcasting_categories;

/**
 * Class Feed_Tests
 *
 * @package tenup_podcasting\Tests
 */
class Feed_Tests extends \WP_UnitTestCase {

	/**
	 * Podcast term for testing
	 *
	 * @var \WP_Term $podcast_term The term
	 */
	protected $podcast_term;

	/**
	 * Post for testing
	 *
	 * @var \WP_Post $post The testing post
	 */
	protected $post;

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

		$this->post = $this->factory()->post->create_and_get(
			array(
				'post_title'   => 'Episode One',
				'post_content' => 'Content for Episode One',
			)
		);
		// Add the podcast to the post.
		wp_set_object_terms( $this->post->ID, $this->podcast_term->slug, 'podcasting_podcasts' );
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
		} catch ( Exception $e ) {
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
	 * Test the channel meta
	 */
	public function test_feed_channel_meta() {
		$xml        = $this->feed_setup();
		$channel    = $xml->channel;
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
		$this->assertSame( 1, $channel->item->count() );
	}
}
