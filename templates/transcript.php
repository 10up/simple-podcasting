<?php
/**
 * Template for transcripts.
 * Intentionally barebones with the minimum html for use by tools.
 *
 * @package tenup_podcasting
 */

?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( get_locale() ); ?>">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php esc_html_e( 'Transcript', 'simple-podcasting' ); ?> - <?php echo esc_html( get_the_title() ); ?></title>
</head>
<body>
<?php
$podcast_slug = get_query_var( 'podcasting-episode' );
$post_object  = get_page_by_path( $podcast_slug, OBJECT, 'post' );
if ( $post_object instanceof WP_Post ) {
	echo wp_kses_post(
		tenup_podcasting\transcripts\podcasting_wrap_unwrapped_text_in_paragraph(
			get_post_meta( $post_object->ID, 'podcast_transcript', true )
		)
	);
}
?>
</body>
</html>
