<?php
/**
 * Podcast Transcript markup
 *
 * @package tenup_podcasting
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 * @var array    $context    Block context.
 */

use function tenup_podcasting\transcripts\get_transcript_link_from_post;

if ( 'none' !== $attributes['display'] ) : ?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php
	switch ( $attributes['display'] ) {
		case 'post':
			echo wp_kses_post(
				do_blocks(
					get_post_meta( get_the_ID(), 'podcast_transcript', true )
				)
			);
			break;
		case 'link':
			printf(
				'<p><a href="%s">%s</a></p>',
				esc_url( get_transcript_link_from_post( get_post() ) ),
				esc_html( $attributes['linkText'] )
			);
			break;
	}
	?>
</div>
<?php endif; ?>
