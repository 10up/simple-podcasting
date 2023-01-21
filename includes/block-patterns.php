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
	register_block_pattern(
		'podcasting/podcast-grid',
		array(
			'title'       => __( 'Podcast Grid', 'simple-podcasting' ),
			'description' => _x( 'This is podcast grid.', 'Block pattern description', 'simple-podcasting' ),
			'content'     => '<!-- wp:query {"queryId":14,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
			<div class="wp-block-query"><!-- wp:post-template -->
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}},"color":{"background":"#ededed"}},"layout":{"inherit":false}} -->
			<div class="wp-block-group has-background" style="background-color:#ededed;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:post-title {"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"700","fontSize":"1.6rem"}}} /-->
			
			<!-- wp:post-date {"displayType":"modified","style":{"typography":{"fontSize":"0.8rem"},"spacing":{"margin":{"top":"10px","right":"0px","bottom":"0px","left":"0px"}}}} /--></div>
			<!-- /wp:group -->
			<!-- /wp:post-template --></div>
			<!-- /wp:query -->',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\init' );
