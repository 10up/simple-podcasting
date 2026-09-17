<?php
/**
 * Template for transcripts.
 * Intentionally barebones with the minimum html for use by tools.
 *
 * @package tenup_podcasting
 */

$post_object = \tenup_podcasting\transcripts\get_requested_transcript_post();

if ( ! $post_object instanceof WP_Post ) {
	return;
}

?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( get_locale() ); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
	if ( function_exists( 'wp_robots' ) && function_exists( 'wp_robots_no_robots' ) && function_exists( 'add_filter' ) ) {
		add_filter( 'wp_robots', 'wp_robots_no_robots' );
		wp_robots();
	}
	?>
	<title>
		<?php
		printf(
			/* translators: %s: The page title */
			esc_html__( 'Transcript - %s', 'simple-podcasting' ),
			wp_strip_all_tags( get_the_title( $post_object ) ) // phpcs:ignore WordPress.Security.EscapeOutput
		);
		?>
	</title>
</head>
<body>
<?php
echo wp_kses_post(
	do_blocks(
		get_post_meta( $post_object->ID, 'podcast_transcript', true )
	)
);
?>
</body>
</html>
