<?php
/**
 * Customize the feed for a specific podcast. Insert the podcast data stored in term meta.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting;

use function tenup_podcasting\transcripts\get_transcript_link_from_post;

/**
 * Add an itunes podcasting header.
 */
function xmlns() {
	echo "\n\t" . 'xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"' . "\n";
}
add_action( 'rss2_ns', __NAMESPACE__ . '\xmlns' );

/**
 * Get the current term, verifying that is has a term_id.
 *
 * @return object WP_Term or false if not a term feed.
 */
function get_the_term() {
	$queried_object = get_queried_object();
	if ( ! $queried_object || ! $queried_object->term_id ) {
		return false;
	}
	return $queried_object;
}

/**
 * Adjust the title for podcasting feeds.
 *
 * @param  string $output The feed title.
 *
 * @return string         The adjusted feed title.
 */
function bloginfo_rss_name( $output ) {
	$term = get_the_term();
	if ( ! $term ) {
		return $output;
	}
	$title = get_term_meta( $term->term_id, 'podcasting_title', true );
	if ( empty( $title ) ) {
		$title = get_bloginfo( 'name' );
		$output = "$title &#187; {$term->name}";
	} else {
		$output = $title;
	}

	return apply_filters( 'simple_podcasting_feed_title', $output, $term );

}
add_filter( 'wp_title_rss', __NAMESPACE__ . '\bloginfo_rss_name' );

// Don't show audio widgets in the feed.
add_filter( 'wp_audio_shortcode', '__return_empty_string', 999 );

/**
 * Sets the podcast language and description in the feed to the values in the term edit screen.
 *
 * @param string $output    The value being displayed.
 * @param string $requested The item that was requested.
 *
 * @return mixed
 */
function bloginfo_rss( $output, $requested ) {
	$term = get_the_term();
	if ( ! $term ) {
		return $output;
	}

	if ( 'language' === $requested ) {
		$lang = get_term_meta( $term->term_id, 'podcasting_language', true );
		if ( $lang ) {
			$lang   = str_replace( '_', '-', $lang );
			$output = $lang;
		}
	}
	if ( 'description' === $requested ) {
		$summary = get_term_meta( $term->term_id, 'podcasting_summary', true );

		if ( empty( $summary ) ) {
			$summary = get_bloginfo( 'description' );
		}

		if ( ! empty( $summary ) ) {
			$output = '<![CDATA[' . $summary . ']]>';
		}
	}
	return $output;
}
add_filter( 'bloginfo_rss', __NAMESPACE__ . '\bloginfo_rss', 10, 2 );

/**
 * Add podcasting details to the feed header.
 */
function feed_head() {
	$term = get_the_term();
	if ( ! $term ) {
		return;
	}
	$subtitle = get_term_meta( $term->term_id, 'podcasting_subtitle', true );

	if ( empty( $subtitle ) ) {
		$subtitle = get_bloginfo( 'description' );
	}

	if ( ! empty( $subtitle ) ) {
		echo '<itunes:subtitle>' . esc_html( wp_strip_all_tags( $subtitle ) ) . "</itunes:subtitle>\n";
	}

	$author = get_term_meta( $term->term_id, 'podcasting_talent_name', true );
	if ( ! empty( $author ) ) {
		echo '<itunes:author>' . esc_html( wp_strip_all_tags( $author ) ) . "</itunes:author>\n";
	}

	echo '<itunes:owner>';

	if ( ! empty( $author ) ) {
		echo '<itunes:name>' . esc_html( wp_strip_all_tags( $author ) ) . "</itunes:name>\n";
	}

	$podcasting_email = get_term_meta( $term->term_id, 'podcasting_email', true );
	$email            = ! empty( $podcasting_email ) ? $podcasting_email : get_bloginfo( 'admin_email' );
	if ( ! empty( $email ) ) {
		echo '<itunes:email>' . esc_html( wp_strip_all_tags( $email ) ) . "</itunes:email>\n";
	}

	echo '</itunes:owner>';

	$copyright = get_term_meta( $term->term_id, 'podcasting_copyright', true );

	if ( ! empty( $copyright ) ) {
		echo '<copyright>' . esc_html( wp_strip_all_tags( $copyright ) ) . "</copyright>\n";
	}

	$explicit = get_term_meta( $term->term_id, 'podcasting_explicit', true );

	echo '<itunes:explicit>';

	if ( empty( $explicit ) ) {
		echo 'no';
	} else {
		echo esc_html( $explicit );
	}

	echo "</itunes:explicit>\n";

	$image = get_term_meta( $term->term_id, 'podcasting_image', true );

	if ( ! empty( $image ) ) {
		echo "<itunes:image href='" . esc_url( wp_get_attachment_url( $image ) ) . "' />\n";
	}

	$keywords = get_term_meta( $term->term_id, 'podcasting_keywords', true );

	if ( ! empty( $keywords ) ) {
		echo '<itunes:keywords>' . esc_html( $keywords ) . "</itunes:keywords>\n";
	}

	$type_of_show = get_term_meta( $term->term_id, 'podcasting_type_of_show', true );

	if ( $type_of_show && '0' !== $type_of_show ) {
		echo '<itunes:type>' . esc_html( $type_of_show ) . "</itunes:type>\n";
	}

	generate_categories();
}
add_action( 'rss2_head', __NAMESPACE__ . '\feed_head' );

/**
 * Output the feed for a single podcast.
 */
function feed_item() {
	global $post;
	$term = get_the_term();
	if ( ! $term ) {
		return false;
	}

	$feed_item = array(
		'author'      => get_option( 'podcasting_talent_name' ),
		'explicit'    => get_post_meta( $post->ID, 'podcast_explicit', true ),
		'captioned'   => get_post_meta( $post->ID, 'podcast_captioned', true ),
		'keywords'    => '',
		'image'       => '',
		'summary'     => '',
		'subtitle'    => '',
		'duration'    => get_post_meta( $post->ID, 'podcast_duration', true ),
		'season'      => get_post_meta( $post->ID, 'podcast_season_number', true ),
		'episode'     => get_post_meta( $post->ID, 'podcast_episode_number', true ),
		'episodeType' => get_post_meta( $post->ID, 'podcast_episode_type', true ),
		'transcript'  => get_post_meta( $post->ID, 'podcast_transcript', true ),
	);

	if ( empty( $feed_item['author'] ) ) {
		$feed_item['author'] = get_the_author();
	}

	// fall back to the podcast setting.
	if ( empty( $feed_item['explicit'] ) ) {
		$feed_item['explicit'] = get_term_meta( $term->term_id, 'podcasting_explicit', true );
	}

	// "no" explicit by default
	if ( empty( $feed_item['explicit'] ) ) {
		$feed_item['explicit'] = 'no';
	}

	// Add the featured image if available.
	if ( has_post_thumbnail( $post->ID ) ) {
		$feed_item['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
		if ( ! empty( $feed_item['image'] ) && is_array( $feed_item['image'] ) ) {
			$feed_item['image'] = $feed_item['image'][0];
		}
	}

	if ( has_excerpt() ) {
		$feed_item['summary'] = get_the_excerpt();
	} else {
		$feed_item['summary'] = get_term_meta( $term->term_id, 'podcasting_summary', true );
	}
	$feed_item['summary'] = apply_filters( 'the_excerpt_rss', $feed_item['summary'] );

	$feed_item['subtitle'] = wp_trim_words( $feed_item['summary'], 10, '&#8230;' );

	/**
	 * Filter podcasting feed item data
	 *
	 * @since 1.3.0
	 *
	 * @param array $feed_item {
	 *     Item data to filter.
	 *
	 *     @type string $author      Podcast author.
	 *     @type string $explicit    Explicit content (yes|no|clean).
	 *     @type string $captioned   Closed Captioned ("1"|"0"). Optional.
	 *     @type string $keywords    Episode keywords. Optional.
	 *     @type string $image       Episode image. Optional.
	 *     @type string $summary     Episode summary.
	 *     @type string $subtitle    Episode subtitle.
	 *     @type string $duration    Episode duration (HH:MM). Optional.
	 *     @type string $season      Season number Optional.
	 *     @type string $episode     Episode number Optional.
	 *     @type string $episodeType Episode type Optional.
	 * }
	 * @param int $post->ID Podcast episode post ID.
	 * @param int $term->term_id Podcast term ID.
	 */
	$feed_item = apply_filters( 'simple_podcasting_feed_item', $feed_item, $post->ID, $term->term_id );

	// Output enclosure if it's not present in the post
	$enclosure = get_post_meta( $post->ID, 'enclosure', true );
	if ( empty( $enclosure ) ) {
		display_rss_enclosure( $post );
	}

	// Output all custom RSS tags.
	echo '<itunes:author>' . esc_html( $feed_item['author'] ) . "</itunes:author>\n";
	echo '<itunes:explicit>' . esc_html( $feed_item['explicit'] ) . "</itunes:explicit>\n";
	if ( $feed_item['captioned'] ) {
		echo "<itunes:isClosedCaptioned>Yes</itunes:isClosedCaptioned>\n";
	}
	if ( ! empty( $feed_item['image'] ) ) {
		echo "<itunes:image href='" . esc_url( $feed_item['image'] ) . "' />\n";
	}
	if ( ! empty( $feed_item['keywords'] ) ) {
		echo '<itunes:keywords>' . esc_html( $feed_item['keywords'] ) . "</itunes:keywords>\n";
	}
	echo '<itunes:subtitle>' . esc_html( $feed_item['subtitle'] ) . "</itunes:subtitle>\n";
	if ( ! empty( $feed_item['duration'] ) ) {
		echo '<itunes:duration>' . esc_html( $feed_item['duration'] ) . "</itunes:duration>\n";
	}
	if ( ! empty( $feed_item['season'] ) ) {
		echo '<itunes:season>' . esc_html( $feed_item['season'] ) . "</itunes:season>\n";
	}
	if ( ! empty( $feed_item['episode'] ) ) {
		echo '<itunes:episode>' . esc_html( $feed_item['episode'] ) . "</itunes:episode>\n";
	}
	if ( ! empty( $feed_item['episodeType'] ) && 'none' !== $feed_item['episodeType'] ) {
		echo '<itunes:episodeType>' . esc_html( $feed_item['episodeType'] ) . "</itunes:episodeType>\n";
	}
	if ( ! empty( $feed_item['transcript'] ) && '' !== $feed_item['transcript'] ) {
		echo '<podcast:transcript>' . esc_url( get_transcript_link_from_post( $post ) ) . "</podcast:transcript>\n";
	}
}
add_action( 'rss2_item', __NAMESPACE__ . '\feed_item' );

/**
 * Displays the enclosure feed for podcasts.
 *
 * @param  WP_Post $post The post object.
 *
 * @return void
 */
function display_rss_enclosure( $post ) {
	$podcast_url      = get_post_meta( $post->ID, 'podcast_url', true );
	$podcast_filesize = get_post_meta( $post->ID, 'podcast_filesize', true );
	$podcast_mime     = get_post_meta( $post->ID, 'podcast_mime', true );

	if ( ! empty( $podcast_url ) ) {
		$enclosure = "<enclosure url='" .
		esc_url( str_replace( 'https://', 'http://', $podcast_url ) ) .
		"' length='" .
		esc_attr( $podcast_filesize ) .
		"' type='" .
		esc_attr( $podcast_mime ) .
		"' />\n";

		echo wp_kses(
			$enclosure,
			array(
				'enclosure' => array(
					'url'    => array(),
					'length' => array(),
					'type'   => array(),
				),
			)
		);
	}
}

/**
 * Generate the category elements from the given option (e.g. podcasting_category_1).
 */
function generate_categories() {
	$term = get_the_term();
	if ( ! $term ) {
		return false;
	}

	$categories[] = get_term_meta( $term->term_id, 'podcasting_category_1', true );
	$categories[] = get_term_meta( $term->term_id, 'podcasting_category_2', true );
	$categories[] = get_term_meta( $term->term_id, 'podcasting_category_3', true );

	$categories = array_filter( $categories );

	$reduced_categories = array();

	foreach ( $categories as $category ) {
		$category = explode( ':', $category );

		if ( ! isset( $reduced_categories[ $category[0] ] ) ) {
			$reduced_categories[ $category[0] ] = array();
		}

		if ( ! empty( $category[1] ) ) {
			$reduced_categories[ $category[0] ][] = $category[1];
		}
	}

	$categories = get_podcasting_categories();

	foreach ( $reduced_categories as $parent => $subs ) {
		if ( ! isset( $categories[ $parent ] ) ) {
			continue;
		}

		if ( empty( $subs ) ) {
			echo '<itunes:category text="' . esc_html( $categories[ $parent ]['name'] ) . "\" />\n";
		} else {
			echo '<itunes:category text="' . esc_html( $categories[ $parent ]['name'] ) . "\">\n";

			foreach ( $subs as $sub ) {
				if ( ! isset( $categories[ $parent ]['subcategories'][ $sub ] ) ) {
					continue;
				}

				echo "\t<itunes:category text=\"" . esc_html( $categories[ $parent ]['subcategories'][ $sub ] ) . "\" />\n";
			}

			echo "</itunes:category>\n";
		}
	}
}

/**
 * Ensure the excerpt is actually used for the excerpt.
 *
 * @param  string $output The excerpt.
 *
 * @return string         The filtered excerpt.
 */
function empty_rss_excerpt( $output ) {
	$excerpt = get_the_excerpt();

	if ( empty( $excerpt ) ) {
		return '';
	}

	return $output;
}
// Run it super late after any other filters may have inserted something.
add_filter( 'the_excerpt_rss', __NAMESPACE__ . '\empty_rss_excerpt', 1000 );

/**
 * Filter the feed query.
 * - Default items listed on the feed to 250.
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function pre_get_posts( $query ) {
	// do nothing if not the feed query.
	if ( ! $query->is_feed() ) {
		return;
	}

	$per_page = apply_filters( 'simple_podcasting_episodes_per_page', PODCASTING_ITEMS_PER_PAGE );

	$query->set( 'posts_per_rss', $per_page );
}

// Filter the feed query.
add_action( 'pre_get_posts', __NAMESPACE__ . '\pre_get_posts', 10, 1 );
