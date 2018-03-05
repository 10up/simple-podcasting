<?php
/**
 * Customize the feed for a specific podcast. Insert the podcast data stored in term meta.
 */
namespace tenup_podcasting;

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
function get_the_term(){
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
		$title = "$title &#187; {$term->name}";
	} else {
		$output = $title;
	}

	return $output;
}
add_filter( 'wp_title_rss', __NAMESPACE__ . '\bloginfo_rss_name' );

// Don't show audio widgets in the feed.
add_filter( 'wp_audio_shortcode', '__return_empty_string', 999 );

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

	$summary = get_term_meta( $term->term_id, 'podcasting_summary', true );

	if ( empty( $summary ) ) {
		$summary = get_bloginfo( 'description' );
	}

	if ( ! empty( $summary ) ) {
		echo '<itunes:summary>' . esc_html( wp_strip_all_tags( $summary ) ) . "</itunes:summary>\n";
	}

	$author = get_term_meta( $term->term_id, 'podcasting_talent_name', true );

	if ( ! empty( $author ) ) {
		echo '<itunes:author>' . esc_html( wp_strip_all_tags( $author ) ) . "</itunes:author>\n";
	}

	$copyright = get_term_meta( $term->term_id, 'podcasting_copyright', true );

	if ( !empty( $copyright ) ) {
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

	generate_category( 'podcasting_category_1' );
	generate_category( 'podcasting_category_2' );
	generate_category( 'podcasting_category_3' );
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

	$post_meta = get_post_meta( $post->ID, 'podcast_episode', true );

	$author = get_option( 'podcasting_talent_name' );
	if ( empty( $author ) ) {
		$author = get_the_author();
	}

	echo "<itunes:author>" . esc_html( $author ) . "</itunes:author>\n";

	$explicit = get_term_meta( $term->term_id, 'podcasting_explicit', true );

	echo "<itunes:explicit>";

	if ( empty( $explicit ) ) {
		echo 'no';
	} else {
		echo esc_html( $explicit );
	}

	echo "</itunes:explicit>\n";

	// Add the featured image if available.
	if ( has_post_thumbnail( $post->ID ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'post-thumbnail' );
		if ( ! empty( $image ) ) {
			if ( is_array( $image ) ) {
				$image = $image[0];
			}
			// iTunes barfs on https images, so force http here.
			$image = str_replace( 'https://', 'http://', $image );
			echo "<itunes:image href='" . esc_url( $image ) . "' />\n";
		}
	}

	// @todo add a filter here
	$keywords = '';
	if ( ! empty( $keywords ) ) {
		echo '<itunes:keywords>' . esc_html( $keywords ) . "</itunes:keywords>\n";
	}

	if ( has_excerpt() ) {
		$excerpt = get_the_excerpt();
	} else {
		$excerpt = get_term_meta( $term->term_id, 'podcasting_summary', true );
	}
	$excerpt = apply_filters( 'the_excerpt_rss', $excerpt );

	echo "<itunes:summary>" . esc_html( wp_strip_all_tags( $excerpt ) ) . "</itunes:summary>\n";

	$subtitle = wp_trim_words( $excerpt, 10, '&#8230;' );

	echo "<itunes:subtitle>" . esc_html( $subtitle ) . "</itunes:subtitle>\n";

	if ( ! empty( $post_meta['enclosure'] ) ) {
		echo "<enclosure url='" .
		esc_url( str_replace( 'https://', 'http://', $post_meta['enclosure']['url'] ) ) .
		"' length='" .
		esc_attr( $post_meta['enclosure']['length'] ) .
		"' type='" .
		esc_attr( $post_meta['enclosure']['mime'] ) .
		"' />\n";
	}

	// Add an enclosure duration if available.
	if ( isset( $post_meta['enclosure']['duration'] ) && ! empty( $post_meta['enclosure']['duration'] ) ) {
		echo '<itunes:duration>' . esc_html( $post_meta['enclosure']['duration'] ) . "</itunes:duration>\n";
	}
}
add_action( 'rss2_item', __NAMESPACE__ . '\feed_item' );

/**
 * Adjust the enclosure feed for podcasts.
 *
 * @param  string $enclosure The enclosure (media url).
 *
 * @return string            The adjusted enclosure.
 */
function rss_enclosure( $enclosure ) {
	global $post;

	$post_meta = get_post_meta( $post->ID, 'podcast_episode', true );

	if ( empty( $post_meta['enclosure'] ) ) {
		return $enclosure;
	}

	return '';
}
add_filter( 'rss_enclosure', __NAMESPACE__ . '\rss_enclosure' );

/**
 * Generate the category elements from the given option (e.g. podcasting_category_1).
 *
 * @param  string $option option to retrieve via get_term_meta
 */
function generate_category( $option ) {
	$term = get_the_term();
	if ( ! $term ) {
		return false;
	}

	$category = get_term_meta( $term->term_id, $option, true );
	switch ( $category ) {
		case 'Education,Education':
			$category = 'Education';
			break;
		case 'Education,Education Technology':
			$category = 'Education, Educational Technology';
			break;
		case 'Tech News':
			$category = 'Technology,Tech News';
			break;
		case 'Sports &amp; Recreation,Technology':
			$category = 'Technology';
			break;
		case 'Sports &amp; Recreation,Gadgets':
			$category = 'Technology,Gadgets';
			break;
	}

	if ( ! empty( $category ) ) {
		$splits = explode( ',', $category );

		if ( 2 === count( $splits ) ) {
			echo "<itunes:category text=\"" . esc_attr( $splits[0] ) . "\">\n";
			echo "\t<itunes:category text=\"" . esc_attr( $splits[1] ) . "\" />\n";
			echo "</itunes:category>\n";
		} else {
			echo "<itunes:category text=\"" . esc_attr( $category ) . "\" />\n";
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
