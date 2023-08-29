<?php
/**
 * Register and enqueue all things block patterns.
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\block_patterns;

/**
 * Register block and its assets.
 */
function init() {
	$podcast_terms = get_terms(
		[
			'taxonomy' => PODCASTING_TAXONOMY_NAME,
			'fields'   => 'ids',
		]
	);

	if ( empty( $podcast_terms ) ) {
		return;
	}

	register_block_pattern(
		'podcasting/podcast-grid',
		array(
			'title'       => __( 'Podcast Grid', 'simple-podcasting' ),
			'description' => _x( 'Podcast Grid', 'This block pattern is used to display podcast in a grid structure.', 'simple-podcasting' ),
			'categories'  => [ 'query' ],
			'content'     => '<!-- wp:query {"query":{"perPage":3,"taxQuery":{"podcasting_podcasts":[' . implode( ',', $podcast_terms ) . ']},"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
				<div class="wp-block-query">
					<!-- wp:post-template -->
						<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"className":"alignfull"} -->
							<div class="wp-block-cover alignfull">
								<span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span>
								<div class="wp-block-cover__inner-container">
									<!-- wp:post-title {"isLink":true,"style":{"typography":{"fontSize":"1.6rem"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->
									<!-- wp:post-terms {"term":"' . esc_js( PODCASTING_TAXONOMY_NAME ) . '","style":{"typography":{"fontSize":"2rem"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->
									<!-- wp:post-date {"displayType":"modified","style":{"typography":{"fontSize":"0.8rem"},"spacing":{"margin":{"top":"0px","right":"0px","bottom":"0px","left":"0px"}},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} /-->
								</div>
							</div>
						<!-- /wp:cover -->
					<!-- /wp:post-template -->
				</div>
			<!-- /wp:query -->',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\init' );
