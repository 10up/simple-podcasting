<?php
/**
 * Adds an endpoint for viewing transcripts.
 *
 * @package tenup_podcasting\transcripts;
 */

namespace tenup_podcasting\transcripts;
use DOMDocument;

/**
 * Wrap unwrapped text in a paragraph tag.
 *
 * @param string $text
 * @return string
 */
function podcasting_wrap_unwrapped_text_in_paragraph( $text ) {
	$doc = new DOMDocument;
	libxml_use_internal_errors(true);
	$doc->loadHTML( '<html><body>' . $text . '</body></html>' );
	$bodyNode = $doc->getElementsByTagName( 'body' )->item(0);
	$filtered_text = '';

	foreach ( $bodyNode->childNodes as $node ) {
		if ( XML_TEXT_NODE === $node->nodeType ) {
			$filtered_text .= '<p>' . $doc->saveHTML( $node ) . '</p>';
			continue;
		}
		$filtered_text .= $doc->saveHTML( $node );
	}

	return $filtered_text;
}
