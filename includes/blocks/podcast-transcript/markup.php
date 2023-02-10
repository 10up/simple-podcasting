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

use tenup_podcasting\transcripts;

if ( 'none' !== $attributes['display'] ) : ?>
<div <?php echo get_block_wrapper_attributes(); // phpcs:ignore ?>>
	<?php
	switch( $attributes['display'] ) {
		case 'post':
			echo transcripts\podcasting_wrap_unwrapped_text_in_paragraph(
				wp_kses(
					get_post_meta( get_the_ID(), 'podcast_transcript', true ),
					array(
						'cite' => array(),
						'time' => array(),
						'p'    => array(),
					)
				)
			);
			break;
		case 'link':
			printf(
				'<p><a href="%s">%s</a></p>',
				esc_attr( '' ),
				esc_html( $attributes['linkText'] )
			);
			break;
	}
	?>
</div>
<?php endif; ?>
