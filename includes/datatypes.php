<?php
/**
 * Register the data types
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting;

/**
 * Register the post meta to be associated with podcasts.
 */
function register_meta() {
	\register_meta(
		'post',
		'podcast_url',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_explicit',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
			'default'      => 'no',
		)
	);

	\register_meta(
		'post',
		'podcast_captioned',
		array(
			'show_in_rest' => true,
			'type'         => 'boolean',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_duration',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_filesize',
		array(
			'show_in_rest' => true,
			'type'         => 'number',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_mime',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'enclosure',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_season_number',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_episode_number',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
		)
	);

	\register_meta(
		'post',
		'podcast_episode_type',
		array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
			'default'      => 'none',
		)
	);

	\register_meta(
		'post',
		'podcast_transcript',
		array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => function( $val ) {
				return wp_kses_post( $val );
			},
		)
	);

	\register_term_meta(
		'podcasting_podcasts',
		'podcasting_talent_name',
		array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'auth_callback'     => 'podcasting_term_auth_callback',
			'sanitize_callback' => function( $val ) {
				return sanitize_text_field( wp_unslash( $val ) );
			},
		)
	);

	\register_term_meta(
		'podcasting_podcasts',
		'podcasting_summary',
		array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'auth_callback'     => 'podcasting_term_auth_callback',
			'sanitize_callback' => function( $val ) {
				return sanitize_text_field( wp_unslash( $val ) );
			},
		)
	);

	\register_term_meta(
		'podcasting_podcasts',
		'podcasting_category_1',
		array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'auth_callback'     => 'podcasting_term_auth_callback',
			'sanitize_callback' => function( $val ) {
				return sanitize_text_field( wp_unslash( $val ) );
			},
		)
	);

	\register_term_meta(
		'podcasting_podcasts',
		'podcasting_image',
		array(
			'show_in_rest'      => true,
			'type'              => 'number',
			'single'            => true,
			'auth_callback'     => 'podcasting_term_auth_callback',
			'sanitize_callback' => function( $val ) {
				return absint( wp_unslash( $val ) );
			},
		)
	);

	\register_term_meta(
		'podcasting_podcasts',
		'podcasting_image_url',
		array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'auth_callback'     => 'podcasting_term_auth_callback',
			'sanitize_callback' => function( $val ) {
				return filter_var( $val, FILTER_VALIDATE_URL );
			},
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_meta' );

/**
 * Podcasting term meta generic auth callback.
 *
 * @return boolean
 */
function podcasting_term_auth_callback() {
	if ( current_user_can( 'manage_categories' ) ) {
		return true;
	}

	return false;
}

/**
 * Add a custom podcasts taxonomy.
 */
function create_podcasts_taxonomy() {
	register_taxonomy(
		PODCASTING_TAXONOMY_NAME,
		'post',
		array(
			'labels'            => array(
				'name'                  => __( 'Podcasts', 'simple-podcasting' ),
				'singular_name'         => __( 'Podcast', 'simple-podcasting' ),
				'search_items'          => __( 'Search Podcasts', 'simple-podcasting' ),
				'all_items'             => __( 'All Podcasts', 'simple-podcasting' ),
				'parent_item'           => __( 'Parent Podcast', 'simple-podcasting' ),
				'parent_item_colon'     => __( 'Parent Podcast:', 'simple-podcasting' ),
				'edit_item'             => __( 'Edit Podcast', 'simple-podcasting' ),
				'view_item'             => __( 'View Podcast', 'simple-podcasting' ),
				'update_item'           => __( 'Update Podcast', 'simple-podcasting' ),
				'add_new_item'          => __( 'Add New Podcast', 'simple-podcasting' ),
				'new_item_name'         => __( 'New Podcast Name', 'simple-podcasting' ),
				'add_or_remove_items'   => __( 'Add or remove podcasts', 'simple-podcasting' ),
				'not_found'             => __( 'No podcasts found', 'simple-podcasting' ),
				'no_terms'              => __( 'No podcasts', 'simple-podcasting' ),
				'items_list_navigation' => __( 'Podcasts list navigation', 'simple-podcasting' ),
				'items_list'            => __( 'Podcasts list', 'simple-podcasting' ),
				'back_to_items'         => __( '&larr; Back to Podcasts', 'simple-podcasting' ),
			),
			'hierarchical'      => true,
			'show_tagcloud'     => false,
			'public'            => true,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'show_in_rest'      => true,
			'show_in_nav_menus' => false,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'podcasts' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\create_podcasts_taxonomy' );

/**
 * Filter the menu so podcasts are parent-less.
 *
 * @param string $file Url to the parent page.
 *
 * @return string
 */
function filter_parent_file( $file ) {
	$screen = get_current_screen();

	if (
		( 'edit-tags' === $screen->base || 'term' === $screen->base ) &&
		'podcasting_podcasts' === $screen->taxonomy
	) {
		return 'edit-tags.php?taxonomy=podcasting_podcasts&amp;podcasts=true';
	}
	return $file;
}
add_filter( 'parent_file', __NAMESPACE__ . '\filter_parent_file' );

/**
 * Add "Podcasts" as its own top level menu item.
 */
function add_top_level_menu() {
	remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=podcasting_podcasts' );
	add_menu_page(
		__( 'Podcasts', 'simple-podcasting' ),
		__( 'Podcasts', 'simple-podcasting' ),
		'manage_options',
		'edit-tags.php?taxonomy=podcasting_podcasts&amp;podcasts=true',
		null,
		'dashicons-microphone',
		13
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_top_level_menu' );

/**
 * Display some help for next steps on the podcast taxonomy screen.
 */
function add_podcasting_taxonomy_help_text() {
	echo '<div class="notice notice-info"><p>';
	esc_html_e( 'Once at least one podcast exists, you can add episodes by creating a post, assigning it to the appropriate podcast, and inserting an audio player or podcast block into the content of the post. You can then submit the feed URL to podcast directories.', 'simple-podcasting' );
	echo '</p></div>';
}
add_action( 'after-podcasting_podcasts-table', __NAMESPACE__ . '\add_podcasting_taxonomy_help_text' );

/**
 * Returns array of supported podcast platforms.
 *
 * @return array
 */
function get_supported_platforms() {
	$platforms = array(
		'pocket-casts'    => array(
			'slug'  => 'pocket-casts',
			'title' => esc_html__( 'Pocket Casts', 'simple-podcasting' ),
		),
		'apple-podcasts'  => array(
			'slug'  => 'apple-podcasts',
			'title' => esc_html__( 'Apple Podcasts', 'simple-podcasting' ),
		),
		'google-podcasts' => array(
			'slug'  => 'google-podcasts',
			'title' => esc_html__( 'Google Podcasts', 'simple-podcasting' ),
		),
		'stitcher'        => array(
			'slug'  => 'stitcher',
			'title' => esc_html__( 'Stitcher', 'simple-podcasting' ),
		),
		'playerfm'        => array(
			'slug'  => 'playerfm',
			'title' => esc_html__( 'PlayerFM', 'simple-podcasting' ),
		),
		'overcast'        => array(
			'slug'  => 'overcast',
			'title' => esc_html__( 'Overcast', 'simple-podcasting' ),
		),
		'pandora'         => array(
			'slug'  => 'pandora',
			'title' => esc_html__( 'Pandora', 'simple-podcasting' ),
		),
		'castro'          => array(
			'slug'  => 'castro',
			'title' => esc_html__( 'Castro', 'simple-podcasting' ),
		),
		'tunein'          => array(
			'slug'  => 'tunein',
			'title' => esc_html__( 'TuneIn', 'simple-podcasting' ),
		),
		'spotify'         => array(
			'slug'  => 'spotify',
			'title' => esc_html__( 'Spotify', 'simple-podcasting' ),
		),
	);

	return apply_filters( 'simple_podcasting_get_supported_platforms', $platforms );
}

/**
 * Renders the terms fields for platforms
 *
 * @param  array   $field   The field data.
 * @param  string  $value   The existing field value.
 * @param  boolean $term_id The term id, or false for the new term form.
 */
function render_platform_fields( $field, $value, $term_id ) {
	$theme = get_term_meta( $term_id, 'podcasting_icon_theme', true );

	if ( empty( $theme ) ) {
		$theme = 'color';
	}

	$platforms = get_supported_platforms();
	?>

	<table id="simple_podcasting__platforms" class="simple_podcasting__platforms widefat striped">
		<thead>
			<th><?php esc_html_e( 'Platform', 'simple-podcasting' ); ?></th>
			<th><?php esc_html_e( 'Podcast URL', 'simple-podcasting' ); ?></th>
			<th><?php esc_html_e( 'Icon', 'simple-podcasting' ); ?></th>
		</thead>

		<tbody>
			<?php foreach ( $platforms as $slug => $platform ) : ?>
				<tr>
					<td class="simple_podcasting__platforms-title"><?php echo esc_html( $platform['title'] ); ?></td>
					<td class="simple_podcasting__platforms-url">
						<input
							name="<?php printf( '%s[%s]', esc_attr( $field['slug'] ), esc_attr( $slug ) ); ?>"
							id="<?php printf( '%s[%s]', esc_attr( $field['slug'] ), esc_attr( $slug ) ); ?>"
							type="url"
							value="<?php echo isset( $value[ $slug ] ) ? esc_url( $value[ $slug ] ) : ''; ?>"
							class="widefat"
						>
					</td>
					<td class="simple_podcasting__platforms-icon <?php echo 'white' === $theme ? 'simple_podcasting__platforms-icon--darken-bg' : ''; ?>">
						<img
							src="
							<?php
							printf(
								'%s%s/%s/%s',
								esc_url( PODCASTING_URL ),
								'dist/images/icons',
								esc_attr( $slug ),
								esc_attr( $theme ) . '-100.png'
							)
							?>
							"
							data-platform="<?php echo esc_attr( $slug ); ?>"
						/>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php
}
add_action( 'simple_podcasting_custom_field_platform_fields', __NAMESPACE__ . '\render_platform_fields', 10, 3 );

/**
 * Add fields to the add term screen.
 *
 * @param \WP_Term $term The term object.
 */
function add_podcasting_term_add_meta_fields( $term ) {
	$podcasting_meta_fields = get_meta_fields();
	foreach ( $podcasting_meta_fields as $field ) {
		?>
		<div class="form-field">
			<label for="name" ><?php echo esc_html( $field['title'] ); ?></label>
			<?php the_field( $field, '' ); ?>
		</div>
		<?php
	}
}

/**
 * Generate and output a single field.
 *
 * @param  array   $field   The field data.
 * @param  string  $value   The existing field value.
 * @param  boolean $term_id The term id, or false for the new term form.
 */
function the_field( $field, $value = '', $term_id = false ) {
	switch ( $field['type'] ) {
		case 'language':
			echo wp_kses(
				$field['data'],
				array(
					'select'   => array(
						'name' => array(),
						'id'   => array(),
					),
					'optgroup' => array(
						'label' => array(),
					),
					'option'   => array(
						'value'          => array(),
						'lang'           => array(),
						'data-installed' => array(),
						'selected'       => array(),
					),
				)
			);
			break;

		case 'textfield':
			?>
				<input
					name="<?php echo esc_attr( $field['slug'] ); ?>"
					id="<?php echo esc_attr( $field['slug'] ); ?>"
					type="text"
					value="<?php echo esc_attr( $value ); ?>"
					size="40"
				>
				<?php
			break;

		case 'textarea':
			?>
				<textarea name="<?php echo esc_attr( $field['slug'] ); ?>" id="<?php echo esc_attr( $field['slug'] ); ?>" rows="5" cols="40"><?php echo esc_textarea( $value ); ?></textarea>
				<?php
			break;

		case 'select':
			?>
				<select
					name="<?php echo esc_attr( $field['slug'] ); ?>"
					id="<?php echo esc_attr( $field['slug'] ); ?>"
					class="postform"
				>
				<?php
				$options = $field['options'];
				foreach ( $options as $key => $label ) {
					?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $value ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
					<?php
				}
				?>
				</select>
				<?php
			break;

		case 'image':
			$image_url = get_term_meta( $term_id, $field['slug'] . '_url', true );
			?>
				<div class="media-wrapper">
				<?php
				$has_image = ( '' === $value );
				?>
					<div class="podasting-existing-image <?php echo ( $has_image ? 'hidden' : '' ); ?>">
						<a href="#" >
							<img
							src="<?php echo esc_url( $image_url ); ?>"
							alt=""
							class="podcast-image-thumbnail"
						>
						</a>
						<input
							type="hidden"
							id="<?php echo esc_attr( $field['slug'] ); ?>"
							name="<?php echo esc_attr( $field['slug'] ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
						>
						<br />
						<a href="#" class="podcast-media-remove" data-media-id="<?php echo esc_attr( $value ); ?>">
							remove image
						</a>
					</div>
					<div class="podcasting-upload-image <?php echo ( ! $has_image ? 'hidden' : '' ); ?>">
						<input
							type="button"
							class="podcasting-media-button button-secondary"
							id="image-<?php echo esc_attr( $field['slug'] ); ?>"
							value="<?php esc_attr_e( 'Select Image', 'simple-podcasting' ); ?>"
							data-slug="<?php echo esc_attr( $field['slug'] ); ?>"
							data-choose="<?php esc_attr_e( 'Podcast Image', 'simple-podcasting' ); ?>"
							data-update="<?php esc_attr_e( 'Choose Selected Image', 'simple-podcasting' ); ?>"
							data-preview-size="thumbnail"
							data-mime-type="image"
						>
					</div>
				</div>
				<?php
			break;

		case 'radio':
			$selected = empty( $value ) ? 'color' : $value;

			foreach ( $field['options'] as $option ) {
				?>
				<label>
					<input
						type="radio"
						name="podcasting_icon_theme"
						value="<?php echo esc_attr( $option['value'] ); ?>"
						<?php checked( $selected, $option['value'] ); ?>
					/>
					<?php echo esc_html( $option['label'] ); ?>
				</label>
				<?php
			}
			break;

		case $field['type']:
			do_action( 'simple_podcasting_custom_field_' . $field['type'], $field, $value, $term_id );
			break;
	}

	if ( isset( $field['description'] ) ) {
		?>
		<p class="description"><?php echo esc_html( $field['description'] ); ?></p>
			<?php
	}
}

/**
 * Save podcasting fields from the term screen to term meta.
 *
 * @param int $term_id The term is being saved.
 */
function save_podcasting_term_meta( $term_id ) {
	$tax = get_taxonomy( PODCASTING_TAXONOMY_NAME );

	if ( ! current_user_can( $tax->cap->edit_terms ) ) {
		return;
	}

	if ( empty( $_POST['podcasting_nonce'] ) || ! wp_verify_nonce( $_POST['podcasting_nonce'], 'podcasting_edit' ) ) {
		return;
	}

	$podcasting_meta_fields = get_meta_fields();

	foreach ( $podcasting_meta_fields as $field ) {
		$slug = $field['slug'];

		if ( isset( $_POST[ $slug ] ) ) {
			if ( is_array( $_POST[ $slug ] ) ) {
				$sanitized_value = filter_var_array(
					$_POST[ $slug ],
					array(
						'pocket-casts'    => FILTER_SANITIZE_URL,
						'apple-podcasts'  => FILTER_SANITIZE_URL,
						'google-podcasts' => FILTER_VALIDATE_URL,
						'stitcher'        => FILTER_VALIDATE_URL,
						'playerfm'        => FILTER_VALIDATE_URL,
						'overcast'        => FILTER_VALIDATE_URL,
						'pandora'         => FILTER_VALIDATE_URL,
						'castro'          => FILTER_VALIDATE_URL,
						'tunein'          => FILTER_VALIDATE_URL,
						'spotify'         => FILTER_VALIDATE_URL,
					)
				);
			} else {
				$sanitized_value = sanitize_text_field( wp_unslash( $_POST[ $slug ] ) );
			}

			// If the field is an image field, store the image URL along with the slug.
			if ( strpos( $slug, '_image' ) ) {
				$image_url = wp_get_attachment_url( (int) $sanitized_value );
				update_term_meta( $term_id, $slug . '_url', $image_url );
			}
			update_term_meta( $term_id, $slug, $sanitized_value );
		}
	}
}
add_action( 'edited_' . PODCASTING_TAXONOMY_NAME, __NAMESPACE__ . '\save_podcasting_term_meta' );
add_action( 'created_' . PODCASTING_TAXONOMY_NAME, __NAMESPACE__ . '\save_podcasting_term_meta' );

/**
 * Add podcasting fields to the term screen.
 *
 * @param \WP_Term $term The term object.
 */
function add_podcasting_term_edit_meta_fields( $term ) {
	$podcasting_meta_fields = get_meta_fields();
	?>
	<table class="form-table">
		<tbody><tr class="form-field term-name-wrap">
	<?php
	foreach ( $podcasting_meta_fields as $field ) {
		$value = get_term_meta( $term->term_id, $field['slug'], true );
		$value = $value ? $value : '';
		?>
		<tr class="form-field term-name-wrap">
			<th scope="row">
				<label
					for="name"
				><?php echo esc_html( $field['title'] ); ?></label>
			</th>
			<td>
				<?php the_field( $field, $value, $term->term_id ); ?>
			</td>
		</tr>
		<?php
	}
	?>
	<tbody>
	</table>
		<tbody><tr class="form-field term-name-wrap">
	<?php
}

/**
 * Add podcasting nonce to the term screen.
 *
 * @param \WP_Term $term     The term object.
 * @param bool     $taxonomy Is this a taxonomy.
 */
function add_podcasting_term_meta_nonce( $term, $taxonomy = false ) {
	echo '<style>
	.term-description-wrap{
		display: none;
	} </style>';

	wp_nonce_field( 'podcasting_edit', 'podcasting_nonce' );
	wp_enqueue_media();
	if ( $taxonomy ) {
		$url = get_term_feed_link( $term->term_id, PODCASTING_TAXONOMY_NAME );
		esc_html_e( 'Your Podcast Feed: ', 'simple-podcasting' );
		echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_url( $url ) . '</a><br />';
		esc_html_e( 'This is the URL you submit to iTunes or podcasting service.', 'simple-podcasting' );
	}
}
add_action( PODCASTING_TAXONOMY_NAME . '_add_form_fields', __NAMESPACE__ . '\add_podcasting_term_meta_nonce' );
add_action( PODCASTING_TAXONOMY_NAME . '_edit_form_fields', __NAMESPACE__ . '\add_podcasting_term_meta_nonce', 99, 2 );

add_action( PODCASTING_TAXONOMY_NAME . '_edit_form', __NAMESPACE__ . '\add_podcasting_term_edit_meta_fields' );
add_action( PODCASTING_TAXONOMY_NAME . '_add_form_fields', __NAMESPACE__ . '\add_podcasting_term_add_meta_fields' );

/**
 * Add a feed link to the podcasting term table.
 *
 * @param string $string      Blank string.
 * @param string $column_name Name of the column.
 * @param int    $term_id     Term ID.
 *
 * @return string
 */
function add_podcasting_term_feed_link_column( $string, $column_name, $term_id ) {

	if ( 'feedurl' === $column_name ) {
		$url = get_term_feed_link( $term_id, PODCASTING_TAXONOMY_NAME );
		echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_url( $url ) . '</a>';
	}
	return $string;
}
add_filter( 'manage_' . PODCASTING_TAXONOMY_NAME . '_custom_column', __NAMESPACE__ . '\add_podcasting_term_feed_link_column', 10, 3 );

/**
 * Add a podcasting image to the podcasting term table.
 *
 * @param string $string      Blank string.
 * @param string $column_name Name of the column.
 * @param int    $term_id     Term ID.
 *
 * @return string
 */
function add_podcasting_term_podcasting_image_column( $string, $column_name, $term_id ) {

	if ( 'podcasting_image' === $column_name ) {
		$image = get_term_meta( $term_id, 'podcasting_image', true );
		echo wp_get_attachment_image( $image, 'thumbnail' );
	}
	return $string;
}
add_filter( 'manage_' . PODCASTING_TAXONOMY_NAME . '_custom_column', __NAMESPACE__ . '\add_podcasting_term_podcasting_image_column', 10, 3 );


/**
 * Add a custom column for the podcast feed link.
 *
 * @param array $columns An array of columns
 *
 * @return array
 */
function add_custom_term_columns( $columns ) {
	$columns = array_merge(
		array(
			'podcasting_image' => __( 'Podcast Cover', 'simple-podcasting' ),
		),
		$columns,
		array(
			'feedurl' => __( 'Feed URL', 'simple-podcasting' ),
		)
	);
	unset( $columns['description'] );
	unset( $columns['author'] );
	return $columns;
}
add_filter( 'manage_edit-' . PODCASTING_TAXONOMY_NAME . '_columns', __NAMESPACE__ . '\add_custom_term_columns', 99 );

/**
 * Get the meta fields used for podcasts.
 */
function get_meta_fields() {
	return array(
		array(
			'slug'  => 'podcasting_subtitle',
			'title' => __( 'Subtitle', 'simple-podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_talent_name',
			'title' => __( 'Artist / Author name (required)', 'simple-podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_email',
			'title' => __( 'Podcast email', 'simple-podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_summary',
			'title' => __( 'Summary (required)', 'simple-podcasting' ),
			'type'  => 'textarea',
		),
		array(
			'slug'  => 'podcasting_copyright',
			'title' => __( 'Copyright / License information', 'simple-podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'    => 'podcasting_explicit',
			'title'   => __( 'Mark as explicit', 'simple-podcasting' ),
			'type'    => 'select',
			'options' => array(
				'No',
				'Yes',
				'Clean',
			),
		),
		array(
			'slug'  => 'podcasting_language',
			'title' => __( 'Language', 'simple-podcasting' ),
			'type'  => 'language',
			'data'  => get_podcasting_language_options(),
		),
		array(
			'slug'        => 'podcasting_image',
			'title'       => __( 'Cover image (required)', 'simple-podcasting' ),
			'type'        => 'image',
			'description' => __( 'Minimum size: 1400px x 1400 px — maximum size: 2048px x 2048px', 'simple-podcasting' ),
		),
		array(
			'slug'        => 'podcasting_keywords',
			'title'       => __( 'Keywords', 'simple-podcasting' ),
			'type'        => 'textfield',
			'description' => __( 'Comma-separated keywords to help people find your podcast.', 'simple-podcasting' ),
		),
		array(
			'slug'    => 'podcasting_type_of_show',
			'title'   => __( 'Type of show', 'simple-podcasting' ),
			'type'    => 'select',
			'options' => array(
				0          => __( 'n/a', 'simple-podcasting' ),
				'episodic' => __( 'Episodic', 'simple-podcasting' ),
				'serial'   => __( 'Serial', 'simple-podcasting' ),
			),
		),
		array(
			'slug'    => 'podcasting_category_1',
			'title'   => __( 'Category 1 (required)', 'simple-podcasting' ),
			'type'    => 'select',
			'options' => get_podcasting_categories_options(),
		),
		array(
			'slug'    => 'podcasting_category_2',
			'title'   => __( 'Category 2', 'simple-podcasting' ),
			'type'    => 'select',
			'options' => get_podcasting_categories_options(),
		),
		array(
			'slug'    => 'podcasting_category_3',
			'title'   => __( 'Category 3', 'simple-podcasting' ),
			'type'    => 'select',
			'options' => get_podcasting_categories_options(),
		),
		array(
			'slug'  => 'podcasting_platforms',
			'title' => __( 'Podcasting Platforms', 'simple-podcasting' ),
			'type'  => 'platform_fields',
		),
		array(
			'slug'    => 'podcasting_icon_theme',
			'title'   => __( 'Podcasting Platforms icon theme', 'simple-podcasting' ),
			'type'    => 'radio',
			'options' => array(
				array(
					'label' => 'Color',
					'value' => 'color',
				),
				array(
					'label' => 'Black',
					'value' => 'black',
				),
				array(
					'label' => 'White',
					'value' => 'white',
				),
			),
		),
	);
}

/**
 * Get array of podcasting categories.
 *
 * Podcasting category names are not translated because they need to be provided in English.
 *
 * @return array Array of podcasting categories.
 */
function get_podcasting_categories() {
	// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned -- keep nested array readable
	return array(
		'arts' => array(
			'name' => 'Arts',
			'subcategories' => array(
				'design'          => 'Design',
				'fashion-beauty'  => 'Fashion & Beauty',
				'food'            => 'Food',
				'books'           => 'Books',
				'performing-arts' => 'Performing Arts',
				'visual-arts'     => 'Visual Arts',
			),
		),
		'business' => array(
			'name' => 'Business',
			'subcategories' => array(
				'careers'          => 'Careers',
				'entrepreneurship' => 'Entrepreneurship',
				'investing'        => 'Investing',
				'management'       => 'Management',
				'marketing'        => 'Marketing',
				'non-profit'       => 'Non-Profit',
			),
		),
		'comedy' => array(
			'name' => 'Comedy',
			'subcategories' => array(
				'comedy-interviews' => 'Comedy Interviews',
				'improv'            => 'Improv',
				'stand-up'          => 'Stand-Up',
			),
		),
		'education' => array(
			'name' => 'Education',
			'subcategories' => array(
				'courses'           => 'Courses',
				'how-to'            => 'How-To',
				'language-learning' => 'Language Learning',
				'self-improvment'   => 'Self-Improvement',
			),
		),
		'fiction' => array(
			'name' => 'Fiction',
			'subcategories' => array(
				'comedy-fiction'  => 'Comedy Fiction',
				'drama'           => 'Drama',
				'science-fiction' => 'Science Fiction',
			),
		),
		'leisure' => array(
			'name' => 'Leisure',
			'subcategories' => array(
				'animation-manga' => 'Animation & Manga',
				'automotive'      => 'Automotive',
				'aviation'        => 'Aviation',
				'crafts'          => 'Crafts',
				'hobbies'         => 'Hobbies',
				'home-garden'     => 'Home & Garden',
				'games'           => 'Games',
				'video-games'     => 'Video Games',
			),
		),
		'government' => array(
			'name' => 'Government',
			'subcategories' => array(
				'local'    => 'Local',
				'national' => 'National',
				'regional' => 'Regional',
			),
		),
		'health-fitness' => array(
			'name' => 'Health & Fitness',
			'subcategories' => array(
				'alternative-health' => 'Alternative Health',
				'fitness'            => 'Fitness',
				'medicine'           => 'Medicine',
				'mental-health'      => 'Mental Health',
				'nutrition'          => 'Nutrition',
				'sexuality'          => 'Sexuality',
			),
		),
		'history' => array(
			'name' => 'History',
		),
		'kids-family' => array(
			'name' => 'Kids & Family',
			'subcategories' => array(
				'education-for-kids' => 'Education for Kids',
				'parenting'          => 'Parenting',
				'pets-animals'       => 'Pets & Animals',
				'stories-for-kids'   => 'Stories for Kids',
			),
		),
		'music' => array(
			'name' => 'Music',
			'subcategories' => array(
				'music-commentary' => 'Music Commentary',
				'music-history'    => 'Music History',
				'music-interviews' => 'Music Interviews',
			),
		),
		'news' => array(
			'name' => 'News',
			'subcategories' => array(
				'business-news'      => 'Business News',
				'daily-news'         => 'Daily News',
				'entertainment-news' => 'Entertainment News',
				'news-commentary'    => 'News Commentary',
				'politics'           => 'Politics',
				'sports-news'        => 'Sports News',
				'tech-news'          => 'Tech News',
			),
		),
		'religion-spirituality' => array(
			'name' => 'Religion & Spirituality',
			'subcategories' => array(
				'buddhism'     => 'Buddhism',
				'christianity' => 'Christianity',
				'hinduism'     => 'Hinduism',
				'islam'        => 'Islam',
				'judaism'      => 'Judaism',
				'religion'     => 'Religion',
				'spirituality' => 'Spirituality',
			),
		),
		'science' => array(
			'name' => 'Science',
			'subcategories' => array(
				'astronomy'        => 'Astronomy',
				'chemistry'        => 'Chemistry',
				'earth-sciences'   => 'Earth Sciences',
				'life-sciences'    => 'Life Sciences',
				'mathematics'      => 'Mathematics',
				'nature'           => 'Nature',
				'natural-sciences' => 'Natural Sciences',
				'physics'          => 'Physics',
				'social-sciences'  => 'Social Sciences',
			),
		),
		'society-culture' => array(
			'name' => 'Society & Culture',
			'subcategories' => array(
				'documentary'       => 'Documentary',
				'personal-journals' => 'Personal Journals',
				'philosophy'        => 'Philosophy',
				'places-travel'     => 'Places & Travel',
				'relationships'     => 'Relationships',
			),
		),
		'sports' => array(
			'name' => 'Sports',
			'subcategories' => array(
				'baseball'       => 'Baseball',
				'basketball'     => 'Basketball',
				'cricket'        => 'Cricket',
				'fantasy-sports' => 'Fantasy Sports',
				'football'       => 'Football',
				'golf'           => 'Golf',
				'hockey'         => 'Hockey',
				'rugby'          => 'Rugby',
				'soccer'         => 'Soccer',
				'swimming'       => 'Swimming',
				'tennis'         => 'Tennis',
				'volleyball'     => 'Volleyball',
				'wilderness'     => 'Wilderness',
				'wrestling'      => 'Wrestling',
			),
		),
		'technology' => array(
			'name' => 'Technology',
			'subcategories' => array(
				'education'       => 'Education',
				'gadgets'         => 'Gadgets',
				'podcasting'      => 'Podcasting',
				'software-how-to' => 'Software How-To',
			),
		),
		'true-crime' => array(
			'name' => 'True Crime',
		),
		'tv-film' => array(
			'name' => 'TV & Film',
			'subcategories' => array(
				'after-shows'     => 'After Shows',
				'film-history'    => 'Film History',
				'film-interviews' => 'Film Interviews',
				'film-reviews'    => 'Film Reviews',
				'tv-reviews'      => 'TV Reviews',
			),
		),
	);
	// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
}

/**
 * Transform podcasting categories into dropdown options
 */
function get_podcasting_categories_options() {
	$to_return  = array( '' => __( 'None', 'simple-podcasting' ) );
	$categories = get_podcasting_categories();

	foreach ( $categories as $key => $category ) {
		$to_return[ $key ] = $category['name'];

		if ( ! empty( $category['subcategories'] ) ) {
			foreach ( $category['subcategories'] as $subkey => $subcategory ) {
				$to_return[ "$key:$subkey" ] = '— ' . $subcategory;
			}
		}
	}

	return $to_return;
}

/**
 * Return the list of available languages.
 *
 * @see wp_dropdown_languages()
 *
 * @return string
 */
function get_podcasting_language_options() {
	$lang = '';
	if ( is_admin() ) {
		global $tag_ID; // WPCS: @codingStandardsIgnoreLine - we can't control WP global names.
		// Are we on the term edit screen?
		$term_id = $tag_ID; // WPCS: @codingStandardsIgnoreLine - we can't control WP global names.
		if ( $term_id ) {
			$lang = get_term_meta( $term_id, 'podcasting_language', true );
		}
	}
	return \wp_dropdown_languages(
		array(
			'echo'     => false,
			'name'     => 'podcasting_language',
			'selected' => $lang,
		)
	);
}
