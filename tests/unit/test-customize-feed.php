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

	public function test_pre_get_posts_feed() {
		$query_mock = \Mockery::mock( '\WP_Query' );

		$query_mock->shouldReceive( 'is_feed' )->andReturn( true );

		\WP_Mock::onFilter( 'simple_podcasting_episodes_per_page' )
			->with( PODCASTING_ITEMS_PER_PAGE )
			->reply( 100 );

		$query_mock->shouldReceive( 'set' )->with( 'posts_per_rss', 100 );

		$this->assertNull(
			tenup_podcasting\pre_get_posts( $query_mock ),
			'tenup_podcasting\pre_get_posts() should update WP_Query->posts_per_rss with filtered value.'
		);
	}

	/**
	 * @test - The most basic of PHPUnit tests to make sure it's working.
	 * @dataProvider data_provider_for_test_feed_item
	 */
	public function test_feed_item( $talent_option, $post_data, $term, $post_author, $post_meta, $term_meta, $filters, $expected ) {
		global $post;
		$post = $post_data;

		\WP_Mock::userFunction(
			'get_queried_object',
			array(
				'times'  => 1,
				'return' => $term,
			)
		);

		\WP_Mock::userFunction( 'get_option' )
			->with( 'podcasting_talent_name' )
			->andReturn( $talent_option );

		\WP_Mock::userFunction( 'get_post_meta' )
			->andReturnUsing(
				function( $id, $key, $_ ) use ( $post_meta ) {
					return isset( $post_meta[ $key ] ) ? $post_meta[ $key ] : false;
				}
			);

		\WP_Mock::userFunction( 'get_term_meta' )
			->andReturnUsing(
				function( $id, $key, $_ ) use ( $term_meta ) {
					return isset( $term_meta[ $key ] ) ? $term_meta[ $key ] : false;
				}
			);

		\WP_Mock::userFunction( 'get_the_author' )
			->andReturn( $post_author );

		\WP_Mock::userFunction( 'has_post_thumbnail' )
			->andReturn( isset( $post->thumbnail ) );

		\WP_Mock::userFunction( 'wp_get_attachment_image_src' )
			->andReturn( isset( $post->thumbnail ) ? $post->thumbnail : false );

		\WP_Mock::passthruFunction( 'get_post_thumbnail_id' );
		\WP_Mock::passthruFunction( 'wp_strip_all_tags' );
		\WP_Mock::passthruFunction( 'wp_kses' );

		\WP_Mock::userFunction( 'has_excerpt' )
			->andReturn( isset( $post->excerpt ) );
		\WP_Mock::userFunction( 'get_the_excerpt' )
			->andReturn( isset( $post->excerpt ) ? $post->excerpt : '' );

		\WP_Mock::userFunction( 'wp_trim_words' )
			->andReturnUsing(
				function ( $text, $num_words, $more ) {
					return strlen( $text ) > 30 ? 'Short Excerpt' : $text;
				}
			);

		ob_start();
		$result = tenup_podcasting\feed_item();
		$output = ob_get_clean();

		if ( is_bool( $expected ) ) {
			$this->assertSame( $expected, $result );
		} elseif ( is_array( $expected ) ) {
			foreach ( $expected as $message => $regex_string ) {
				$this->assertMatchesRegularExpression( $regex_string, $output, $message );
			}
		}
	}

	public function test_rss_title_can_be_filtered() {
		$queried_object = (object) array(
			'term_id' => 42,
			'name' => 'Original Podcast Name'
		);
		\WP_Mock::userFunction( 'get_queried_object' )
			->andReturn( $queried_object );

		\WP_Mock::userFunction( 'get_bloginfo' )
			->with( 'name' )
			->andReturn( 'Blogname' );


		\WP_Mock::onFilter( 'simple_podcasting_feed_title' )
			->with( 'Podcast Title', $queried_object )
			->reply( 'Filtered Podcast Title' );

		$this->assertEquals(
			'Filtered Podcast Title',
			tenup_podcasting\bloginfo_rss_name( 'Podcast Title' ),
			'tenup_podcasting\bloginfo_rss_name() should return the filtered value.'
		);

	}

	public function data_provider_for_test_feed_item() {
		return array(
			'Term not found'              => array(
				'talent_option' => '',
				'post_data'     => (object) array(),
				'term'          => false,
				'post_author'   => '',
				'post_meta'     => array(),
				'term_meta'     => array(),
				'filters'       => false,
				'expected'      => false,
			),
			'Mixed Test 1'                => array(
				'talent_option' => 'Talent from settings',
				'post_data'     => (object) array(
					'ID'        => 42,
					'excerpt'   => 'Post Excerpt',
					'thumbnail' => 'http://example.com/image.jpg',
				),
				'term'          => (object) array( 'term_id' => 2 ),
				'post_author'   => 'Post Author',
				'post_meta'     => array(
					'podcast_explicit'       => '',
					'podcast_captioned'      => '',
					'podcast_duration'       => '',
					'podcast_season_number'  => '',
					'podcast_episode_number' => '',
					'podcast_episode_type'   => '',
				),
				'term_meta'     => array(
					'podcasting_explicit' => '',
					'podcasting_summary'  => '',
				),
				'filters'       => false,
				'expected'      => array(
					'Talent from settings'          => '/<itunes:author>Talent from settings<\/itunes:author>/',
					'No explicit if all defaults'   => '/<itunes:explicit>no<\/itunes:explicit>/',
					'Doesnt contain closed caption' => '/^((?!isClosedCaptioned).)*$/s',
					'Episode thumbnail'             => "/<itunes:image href='http:\/\/example\.com\/image\.jpg' \/>/",
					'Post excerpt as subtitle'      => '/<itunes:subtitle>Post Excerpt<\/itunes:subtitle>/',
					'Duration is empty'             => '/^((?!<itunes:duration>).)*$/s',
					'Doesnt contain season'         => '/^((?!season).)*$/s',
					'Doesnt contain episode'        => '/^((?!episode).)*$/s',
					'Doesnt contain episode type'   => '/^((?!episodeType).)*$/s',
				),
			),
			'Mixed Test 2'                => array(
				'talent_option' => '',
				'post_data'     => (object) array(
					'ID'      => 42,
					'excerpt' => 'Very Long Post Excerpt Very Long Post Excerpt Very Long Post Excerpt Very Long Post Excerpt',
				),
				'term'          => (object) array( 'term_id' => 2 ),
				'post_author'   => 'Post Author',
				'post_meta'     => array(
					'podcast_explicit'       => 'yes',
					'podcast_captioned'      => '1',
					'podcast_duration'       => '1:23',
					'podcast_season_number'  => '2',
					'podcast_episode_number' => '4',
					'podcast_episode_type'   => 'full',
				),
				'term_meta'     => array(
					'podcasting_explicit' => '',
					'podcasting_summary'  => '',
				),
				'filters'       => false,
				'expected'      => array(
					'Post Author'              => '/<itunes:author>Post Author<\/itunes:author>/',
					'Explicit from episode'    => '/<itunes:explicit>yes<\/itunes:explicit>/',
					'Contain Closed Captioned' => '/<itunes:isClosedCaptioned>Yes<\/itunes:isClosedCaptioned>/',
					'Doesnt contain thumbnail' => '/^((?!<itunes:image).)*$/s',
					'Short Subtitle'           => '/<itunes:subtitle>Short Excerpt<\/itunes:subtitle>/',
					'Duration'                 => '/<itunes:duration>1:23<\/itunes:duration>/',
					'Season'                   => '/<itunes:season>2<\/itunes:season>/',
					'Episode'                  => '/<itunes:episode>4<\/itunes:episode>/',
					'Episode type'             => '/<itunes:episodeType>full<\/itunes:episodeType>/',
				),
			),
			'Mixed Test 3'                => array(
				'talent_option' => '',
				'post_data'     => (object) array(
					'ID' => 42,
				),
				'term'          => (object) array( 'term_id' => 2 ),
				'post_author'   => 'Post Author',
				'post_meta'     => array(
					'podcast_explicit'  => '',
					'podcast_captioned' => '1',
					'podcast_duration'  => '1:23',
				),
				'term_meta'     => array(
					'podcasting_explicit' => 'clean',
					'podcasting_summary'  => 'Summary from plugin settings',
				),
				'filters'       => false,
				'expected'      => array(
					'Post Author'                   => '/<itunes:author>Post Author<\/itunes:author>/',
					'Explicit from plugin settings' => '/<itunes:explicit>clean<\/itunes:explicit>/',
					'Contain Closed Captioned'      => '/<itunes:isClosedCaptioned>Yes<\/itunes:isClosedCaptioned>/',
					'Doesnt contain thumbnail'      => '/^((?!<itunes:image).)*$/s',
					'Subtitle from plugin settings' => '/<itunes:subtitle>Summary from plugin settings<\/itunes:subtitle>/',
					'Duration'                      => '/<itunes:duration>1:23<\/itunes:duration>/',
				),
			),
			'Enclosure from podcast meta' => array(
				'talent_option' => '',
				'post_data'     => (object) array(
					'ID' => 42,
				),
				'term'          => (object) array( 'term_id' => 2 ),
				'post_author'   => 'Post Author',
				'post_meta'     => array(
					'enclosure'        => false,
					'podcast_url'      => 'http://example.com/media.mp3',
					'podcast_filesize' => '42000',
					'podcast_mime'     => 'audio/mpeg',
				),
				'term_meta'     => array(),
				'filters'       => false,
				'expected'      => array(
					'Enclosure' => '/<enclosure url=\'http:\/\/example.com\/media.mp3\' length=\'42000\' type=\'audio\/mpeg\' \/>/',
				),
			),
		);
	}
}
