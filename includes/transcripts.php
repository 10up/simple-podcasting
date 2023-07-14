<?php
/**
 * Adds an endpoint for viewing transcripts.
 *
 * @package tenup_podcasting\transcripts;
 */

namespace tenup_podcasting\transcripts;

use DOMDocument;
use WP_Post;

/**
 * Wrap unwrapped text in a paragraph tag.
 *
 * @param string $text The incoming markup.
 *
 * @return string
 */
function podcasting_wrap_unwrapped_text_in_paragraph( $text ) {
	$doc = new DOMDocument();
	libxml_use_internal_errors( true );
	$doc->loadHTML( '<html><body>' . $text . '</body></html>' );
	$body_node     = $doc->getElementsByTagName( 'body' )->item( 0 );
	$filtered_text = '';

	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	foreach ( $body_node->childNodes as $node ) {
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( XML_TEXT_NODE === $node->nodeType ) {
			$filtered_text .= '<p>' . $doc->saveHTML( $node ) . '</p>';
			continue;
		}
		$filtered_text .= $doc->saveHTML( $node );
	}

	return $filtered_text;
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	// phpcs:enable WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
}

/**
 * Adds transcript query var.
 * Used to indicate that the transcript template should be rendered.
 *
 * @param array $vars Array of existing query vars.
 *
 * @return array
 */
function query_vars( $vars ) {
	$vars[] = 'podcast-transcript';
	$vars[] = 'podcasting-episode';
	return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\\query_vars', 10, 1 );

/**
 * Renders transcript template.
 *
 * @param string $template Template path.
 *
 * @return string
 */
function template( $template ) {
	if ( ! get_query_var( 'podcast-transcript' ) || ! get_query_var( 'podcasting_podcasts' ) ) {
		return $template;
	}
	return PODCASTING_PATH . 'templates/transcript.php';
}
add_filter( 'taxonomy_template', __NAMESPACE__ . '\\template', 10, 1 );

/**
 * Adds rewrite rule to podcasts.
 *
 * @param array $rules Array of redirect rules.
 *
 * @return array
 */
function rewrite_rules( $rules ) {
	$rules['^podcasts/([^/]+)/([^/]+)/transcript'] = 'index.php?podcast-transcript=1&podcasting_podcasts=$matches[1]&podcasting-episode=$matches[2]';
	return $rules;
}
add_filter( 'podcasting_podcasts_rewrite_rules', __NAMESPACE__ . '\\rewrite_rules', 10, 1 );

/**
 * Get the transcript link from a post object
 *
 * @param WP_Post $post Post object.
 *
 * @return string url
 */
function get_transcript_link_from_post( WP_Post $post ) {
	$podcast = get_the_terms( $post, TAXONOMY_NAME );
	if ( ! $podcast ) {
		return '';
	}
	return trailingslashit( get_term_link( $podcast[0]->term_id ) ) . $post->post_name . '/transcript/';
}
