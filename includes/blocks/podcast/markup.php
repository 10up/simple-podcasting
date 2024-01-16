<?php
/**
 * Podcast markup
 *
 * @package tenup_podcasting
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 * @var array    $context    Block context.
 */

$attributes = wp_parse_args(
	$attributes ?? [],
	[
		'id'                   => null,
		'caption'              => '',
		'displayDuration'      => false,
		'displayShowTitle'     => false,
		'displayEpisodeTitle'  => false,
		'displayArt'           => false,
		'displayExplicitBadge' => false,
		'displaySeasonNumber'  => false,
		'displayEpisodeNumber' => false,
		'displayEpisodeType'   => false,
	]
);

if ( ! $attributes['id'] ) {
	return;
}

$post_id        = get_the_id();
$podcast_shows  = get_the_terms( get_the_ID(), 'podcasting_podcasts' );
$show_name      = count( $podcast_shows ) > 0 ? $podcast_shows[0]->name : '';
$src            = get_post_meta( $post_id, 'src', true );
$duration       = get_post_meta( $post_id, 'podcast_duration', true );
$explicit       = get_post_meta( $post_id, 'podcast_explicit', true );
$episode_type   = get_post_meta( $post_id, 'podcast_episode_type', true );
$episode_number = get_post_meta( $post_id, 'podcast_episode_number', true );
$season_number  = get_post_meta( $post_id, 'podcast_season_number', true );

?>
<figure class="wp-block-podcasting-podcast">
	<div class="wp-block-podcasting-podcast__container">
		<?php if ( has_post_thumbnail() && $attributes['displayArt'] ) : ?>
			<div class="wp-block-podcasting-podcast__show-art">
				<?php the_post_thumbnail( 'medium' ); ?>
			</div>
		<?php endif; ?>
		
		<div class="wp-block-podcasting-podcast__details">
			<?php if ( $attributes['displayEpisodeTitle'] ) : ?>
				<h3 class="wp-block-podcasting-podcast__show-title">
					<?php if ( $attributes['displayEpisodeNumber'] ) : ?>
						<span>
							<?php echo esc_html( $episode_number ); ?>.
						</span>
					<?php endif; ?>
					<?php the_title(); ?>
				</h3>
			<?php endif; ?>
			
			<div class="wp-block-podcasting-podcast__show-details">
				<?php if ( $attributes['displayShowTitle'] ) : ?>
					<span class="wp-block-podcasting-podcast__show-title">
						<?php echo esc_html( $show_name ); ?>
					</span>
				<?php endif; ?>
				<?php if ( $attributes['displaySeasonNumber'] ) : ?>
					<span class="wp-block-podcasting-podcast__season">
						<?php esc_html_e( 'Season: ', 'simple-podcasting' ); ?>
						<?php echo esc_html( $season_number ); ?>
					</span>
				<?php endif; ?>
				<?php if ( $attributes['displayEpisodeNumber'] ) : ?>
					<span class="wp-block-podcasting-podcast__episode">
						<?php esc_html_e( 'Episode: ', 'simple-podcasting' ); ?>
						<?php echo esc_html( $episode_number ); ?>
					</span>
				<?php endif; ?>
				<?php if ( $attributes['displayEpisodeType'] ) : ?>
					<span class="wp-block-podcasting-podcast__season-number">
						<?php esc_html_e( 'Episode type: ', 'simple-podcasting' ); ?>
						<?php echo esc_html( $episode_type ); ?>
					</span>
				<?php endif; ?>
				<?php if ( $attributes['displayExplicitBadge'] ) : ?>
					<span class="wp-block-podcasting-podcast__explicit-badge">
						<?php esc_html_e( 'Explicit', 'simple-podcasting' ); ?>
						<?php echo esc_html( $explicit ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php echo wp_kses_post( $content ); ?>
</figure>
