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
		'isDocked'             => 'none',
	]
);

if ( ! $attributes['id'] ) {
	return;
}

$post_id        = get_the_id();
$podcast_shows  = get_the_terms( $post_id, 'podcasting_podcasts' );
$podcast_show   = $podcast_shows ? $podcast_shows[0] : '';
$show_name      = $podcast_show ? $podcast_show->name : '';
$src            = get_post_meta( $post_id, 'src', true );
$duration       = get_post_meta( $post_id, 'podcast_duration', true );
$explicit       = get_post_meta( $post_id, 'podcast_explicit', true );
$episode_type   = get_post_meta( $post_id, 'podcast_episode_type', true );
$episode_number = get_post_meta( $post_id, 'podcast_episode_number', true );
$season_number  = get_post_meta( $post_id, 'podcast_season_number', true );
if ( is_a( $podcast_show, 'WP_Term' ) ) {
	$term_image_id = get_term_meta( $podcast_show->term_id, 'podcasting_image', true );
} else {
	$term_image_id = '';
}

/*
 * If not on a single post, set isDocked to none.
 *
 * This is to prevent having multiple instances of the podcast block with same docked position.
 */
$is_docked = ! is_singular() ? 'none' : $attributes['isDocked'];

// Output the body class based on isDocked value
$body_class = '';
if ( 'top' === $is_docked ) {
	$body_class = 'has-docked-top';
} elseif ( 'bottom' === $is_docked ) {
	$body_class = 'has-docked-bottom';
}

$podcast_details_id       = wp_unique_prefixed_id( 'podcast-details' );
$toggle_details_button_id = wp_unique_prefixed_id( 'toggle-details-button' );
?>

<div class="wp-block-podcasting-podcast-outer <?php echo 'docked-' . sanitize_html_class( $is_docked ); ?>">
	<div class="wp-block-podcasting-podcast__container">
		<?php if ( $attributes['displayArt'] && ( has_post_thumbnail() || ! empty( $term_image_id ) ) ) : ?>
			<div class="wp-block-podcasting-podcast__show-art">
				<div class="wp-block-podcasting-podcast__image">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'medium' );
					} elseif ( $podcast_show instanceof \WP_Term ) {
						echo wp_get_attachment_image( $term_image_id, 'medium' );
					}
					?>
				</div>
			</div>
		<?php endif; ?>

		<div class="wp-block-podcasting-podcast__details">
			<?php if ( $attributes['displayEpisodeTitle'] ) : ?>
				<h3 class="wp-block-podcasting-podcast__show-title">
					<?php if ( $attributes['displayEpisodeNumber'] && ! empty( $episode_number ) ) : ?>
						<span>
							<?php echo esc_html( $episode_number ); ?>.
						</span>
					<?php endif; ?>
					<?php the_title(); ?>
				</h3>
			<?php endif; ?>
			<div id="<?php echo esc_attr( $podcast_details_id ); ?>" style="display: none;" aria-hidden="true">
				<div class="wp-block-podcasting-podcast__show-details">
					<?php if ( $attributes['displayShowTitle'] && ! empty( $show_name ) ) : ?>
						<span class="wp-block-podcasting-podcast__title">
							<?php echo esc_html( $show_name ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $attributes['displaySeasonNumber'] && ! empty( $season_number ) ) : ?>
						<span class="wp-block-podcasting-podcast__season">
							<?php esc_html_e( 'Season: ', 'simple-podcasting' ); ?>
							<?php echo esc_html( $season_number ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $attributes['displayEpisodeNumber'] && ! empty( $episode_number ) ) : ?>
						<span class="wp-block-podcasting-podcast__episode">
							<?php esc_html_e( 'Episode: ', 'simple-podcasting' ); ?>
							<?php echo esc_html( $episode_number ); ?>
						</span>
					<?php endif; ?>
				</div>
				<div class="wp-block-podcasting-podcast__show-details">
					<?php if ( $attributes['displayDuration'] && ! empty( $duration ) ) : ?>
						<span class="wp-block-podcasting-podcast__duration">
							<?php esc_html_e( 'Listen Time: ', 'simple-podcasting' ); ?>
							<?php echo esc_html( $duration ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $attributes['displayEpisodeType'] && ! empty( $episode_type ) && 'none' !== $episode_type ) : ?>
						<span class="wp-block-podcasting-podcast__episode-type">
							<?php esc_html_e( 'Episode type: ', 'simple-podcasting' ); ?>
							<?php echo esc_html( $episode_type ); ?>
						</span>
					<?php endif; ?>
					<?php if ( $attributes['displayExplicitBadge'] && ! empty( $explicit ) ) : ?>
						<span class="wp-block-podcasting-podcast__explicit-badge">
							<?php esc_html_e( 'Explicit: ', 'simple-podcasting' ); ?>
							<?php echo esc_html( $explicit ); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
			<div class="wp-block-podcasting-podcast__toggle-details">
				<button class="wp-block-podcasting-podcast__toggle-details-button" id="<?php echo esc_attr( $toggle_details_button_id ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $podcast_details_id ); ?>" aria-label="<?php esc_attr_e( 'Toggle podcast details', 'simple-podcasting' ); ?>">
					<?php esc_html_e( 'More', 'simple-podcasting' ); ?>
				</button>
			</div>
			<?php
			if ( isset( $is_docked ) && 'none' !== $is_docked ) {
				echo wp_kses_post( $content );
			}
			?>
		</div>
	</div>

	<?php
	if ( isset( $is_docked ) && 'none' === $is_docked ) {
		echo wp_kses_post( $content );
	}
	?>
</div>

<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function() {
		// Add the body class for docked position
		<?php if ( $body_class ) : ?>
			document.body.classList.add('<?php echo esc_attr( $body_class ); ?>');
		<?php endif; ?>

		var toggleButton = document.getElementById('<?php echo esc_attr( $toggle_details_button_id ); ?>');
		var detailsDiv = document.getElementById('<?php echo esc_attr( $podcast_details_id ); ?>');

		// If isDocked is 'none', show details by default
		<?php if ( 'none' === $is_docked ) : ?>
			detailsDiv.style.display = 'block';
			detailsDiv.setAttribute('aria-hidden', 'false');
			toggleButton.textContent = <?php echo wp_json_encode( __( 'Less', 'simple-podcasting' ) ); ?>;
			toggleButton.style.display = 'none';
			toggleButton.setAttribute('aria-expanded', 'true');
			toggleButton.setAttribute('aria-hidden', 'true');
		<?php endif; ?>

		toggleButton.addEventListener('click', function() {
			if (detailsDiv.style.display === 'none') {
				detailsDiv.style.display = 'block';
				detailsDiv.setAttribute('aria-hidden', 'false');
				toggleButton.textContent = <?php echo wp_json_encode( __( 'Less', 'simple-podcasting' ) ); ?>;
				toggleButton.setAttribute('aria-expanded', 'true');
			} else {
				detailsDiv.style.display = 'none';
				detailsDiv.setAttribute('aria-hidden', 'true');
				toggleButton.textContent = <?php echo wp_json_encode( __( 'More', 'simple-podcasting' ) ); ?>;
				toggleButton.setAttribute('aria-expanded', 'false');
			}
		});
	});
</script>
