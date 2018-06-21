<?php
namespace tenup_podcasting;

function register_meta() {
	\register_meta( 'post', 'podcast_episode', array(
		'show_in_rest' => true,
		'type'         => 'array',
		'single'       => true,
	) );

	\register_meta( 'post', 'podcast_explicit', array(
		'show_in_rest' => true,
		'type'         => 'boolean',
		'single'       => true,
	) );

	\register_meta( 'post', 'podcast_captioned', array(
		'show_in_rest' => true,
		'type'         => 'boolean',
		'single'       => true,
	) );
}
add_action( 'init', __NAMESPACE__ . '\register_meta' );

/**
 * Add a custom podcasts taxonomy.
 */
function create_podcasts_taxonomy() {
	register_taxonomy( TAXONOMY_NAME, 'post', array(
		'labels'        => array(
			'name'              => __( 'Podcasts', 'podcasting' ),
			'singular_name'     => __( 'Podcast', 'podcasting' ),
			'search_items'      => __( 'Search Podcasts', 'podcasting' ),
			'all_items'         => __( 'All Podcasts', 'podcasting' ),
			'parent_item'       => __( 'Parent Podcast', 'podcasting' ),
			'parent_item_colon' => __( 'Parent Podcast:', 'podcasting' ),
			'edit_item'         => __( 'Edit Podcast', 'podcasting' ),
			'update_item'       => __( 'Update Podcast', 'podcasting' ),
			'add_new_item'      => __( 'Add New Podcast', 'podcasting' ),
			'new_item_name'     => __( 'New Podcast', 'podcasting' ),
			'menu_name'         => __( 'Podcasts', 'podcasting' ),
		),
		'hierarchical'      => true,
		'show_tagcloud'     => false,
		'public'            => true,
		'show_in_rest'      => true,
		'show_in_nav_menus' => false,
		'show_admin_column' => true,
		'rewrite'           => array( 'slug' => 'podcasts' ),
	) );
}
add_action( 'init', __NAMESPACE__ . '\create_podcasts_taxonomy' );

/**
 * Filter the menu so podcasts are parent-less.
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
 * Set up term meta for podcasts.
 */
function register_term_meta() {
	$podcasting_meta_fields = get_meta_fields();

	foreach( $podcasting_meta_fields as $field ) {
		register_meta( 'term', $field['slug'], array(
			'sanitize_callback' => 'sanitize_my_meta_key',
			'type' => $field['type'],
			'description' => $field['title'],
			'single' => true,
			'show_in_rest' => false,
		) );
	}
}
add_action( 'init', __NAMESPACE__ . '\register_term_meta' );

/**
 * Add "Podcasts" as its own top level menu item.
 */
function add_top_level_menu() {
	remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=podcasting_podcasts' );
	add_menu_page(
		__( 'Podcasts', 'podcasting' ),
		__( 'Podcasts', 'podcasting' ),
		'manage_options',
		'edit-tags.php?taxonomy=podcasting_podcasts&amp;podcasts=true',
		null,
		'dashicons-microphone',
		13
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_top_level_menu' );

/**
 * Add fields to the add term screen.
 */
function add_podcasting_term_add_meta_fields( $term ) {
	$podcasting_meta_fields = get_meta_fields();
	foreach( $podcasting_meta_fields as $field ) {
		?>
		<label
			for="name"
		><?php echo esc_html( $field['title'] ); ?></label>
		<?php the_field( $field, '' );
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
		case 'textfield':
		?>
			<input
				name="<?php echo esc_attr( $field['slug'] ); ?>"
				id="<?php echo esc_attr( $field['slug'] ); ?>"
				type="text"
				value="<?php echo esc_attr( $value ); ?>"
				size="40"
				aria-required="true"
			>
		<?php
			break;
		case 'textarea':
		?>
			<textarea name="<?php echo esc_attr( $field['slug'] ); ?>" id="<?php echo esc_attr( $field['slug'] ); ?>" rows="5" cols="40"></textarea>
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
			$categories = $field['options'];
			foreach( $categories as $category ) {
				$slug = sanitize_title( $category );
				?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $slug, $value ); ?>>
					<?php echo esc_html( $category ); ?>
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
			<?php
			?>
			</div>
			<div class="podcasting-upload-image <?php echo ( ! $has_image ? 'hidden' : '' ); ?>">
				<input
					type="button"
					class="podcasting-media-button button-secondary"
					id="image-<?php echo esc_attr( $field['slug'] ); ?>"
					value="<?php esc_attr_e( 'Select Image', 'podcasting' ); ?>"
					data-slug="<?php echo esc_attr( $field['slug'] ); ?>"
					data-choose="<?php esc_attr_e( 'Podcast Image', 'podcasting' ); ?>"
					data-update="<?php esc_attr_e( 'Choose Selected Image', 'podcasting' ); ?>"
					data-preview-size="thumbnail"
					data-mime-type="image"
				>
			</div>
		</div>
		<?php
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
 */
function save_podcasting_term_meta( $term_id ) {
	$podcasting_meta_fields = get_meta_fields();
	foreach ( $podcasting_meta_fields as $field ) {
		$slug = $field['slug'];

		if ( isset( $_POST[ $slug ] ) ) {
			$sanitized_value = sanitize_text_field( $_POST[ $slug ] );

			// If the field is an image field, store the image URL along with the slug.
			if ( strpos( $slug, '_image' ) ) {
				$image_url = wp_get_attachment_url( (int) $sanitized_value );
				update_term_meta( $term_id, $slug . '_url', $image_url );
			}
			update_term_meta( $term_id, $slug, $sanitized_value );
		}
	}
}
add_action( 'edited_' . TAXONOMY_NAME, __NAMESPACE__ . '\save_podcasting_term_meta' );

/**
 * Add podcasting fields to the term screen.
 */
function add_podcasting_term_edit_meta_fields( $term ) {
	$podcasting_meta_fields = get_meta_fields();
	?>
	<table class="form-table">
		<tbody><tr class="form-field form-required term-name-wrap">
	<?php
	foreach ( $podcasting_meta_fields as $field ) {
		$value = get_term_meta( $term->term_id, $field['slug'], true );
		$value = $value ? $value : '';
		?>
		<tr class="form-field form-required term-name-wrap">
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
		<tbody><tr class="form-field form-required term-name-wrap">
	<?php
}

/**
 * Add podcasting nonce to the term screen.
 */
function add_podcasting_term_meta_nonce( $term, $taxonomy = false ) {
	echo '<style>
	.term-description-wrap{
		display: none;
	} </style>';
	wp_enqueue_media();
	if ( $taxonomy ) {
		$url = get_term_feed_link( $term->term_id, TAXONOMY_NAME );
		printf(
			/* translators: Linked podcast feed URL */
			__( 'Your Podcast Feed: %s', 'ads-txt' ),
			'<a href="' . esc_url( $url ) . '" target="_blank">' . esc_url( $url ) . '</a>'
		);
		echo '<br />';
		esc_html_e( 'This is the URL you submit to iTunes or podcasting service.', 'podcasting' );
	}
}
add_action( TAXONOMY_NAME . '_add_form_fields', __NAMESPACE__ . '\add_podcasting_term_meta_nonce' );
add_action( TAXONOMY_NAME . '_edit_form_fields', __NAMESPACE__ . '\add_podcasting_term_meta_nonce', 99, 2 );

add_action( TAXONOMY_NAME . '_edit_form', __NAMESPACE__ . '\add_podcasting_term_edit_meta_fields' );
add_action( TAXONOMY_NAME . '_add_form_fields', __NAMESPACE__ . '\add_podcasting_term_add_meta_fields' );


/**
 * Add a feed link to the podcasting term table.
 *
 * @param string $string      Blank string.
 * @param string $column_name Name of the column.
 * @param int    $term_id     Term ID.
 */
function add_podcasting_term_feed_link_column( $string, $column_name, $term_id ) {

	if ( 'feedurl' === $column_name ) {
		$url = get_term_feed_link( $term_id, TAXONOMY_NAME );
		echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_url( $url ) . '</a>';
	}
	return $string;
}
add_filter( 'manage_' . TAXONOMY_NAME . '_custom_column', __NAMESPACE__ . '\add_podcasting_term_feed_link_column',10,3);

/**
 * Add a custom column for the podcast feed link.
 * @param Array $columns An array of columns
 */
function add_custom_term_columns( $columns ){
	$columns['feedurl'] = 'Feed URL';
	unset( $columns['description'] );
	unset( $columns['author'] );
	return $columns;
}
add_filter( 'manage_edit-' . TAXONOMY_NAME . '_columns', __NAMESPACE__ . '\add_custom_term_columns', 99 );

/**
 * Get the meta fields used for podcasts.
 */
function get_meta_fields() {
	return array(
		array(
			'slug'  => 'podcasting_subtitle',
			'title' => __( 'Podcast subtitle', 'podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_talent_name',
			'title' => __( 'Podcast talent', 'podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_summary',
			'title' => __( 'Podcast summary', 'podcasting' ),
			'type'  => 'textarea',
		),
		array(
			'slug'  => 'podcasting_copyright',
			'title' => __( 'Podcast copyright', 'podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'    => 'podcasting_explicit',
			'title'   => __( 'Mark as explicit', 'podcasting' ),
			'type'    => 'select',
			'options' => array(
				'No',
				'Yes',
				'Clean',
			)
		),
		array(
			'slug'  => 'podcasting_image',
			'title' => __( 'Podcast image', 'podcasting' ),
			'type'  => 'image',
			'description' => __( 'Minimum size: 1400px x 1400 px — maximum size: 2048px x 2048px', 'podcasting' ),
		),
		array(
			'slug'  => 'podcasting_keywords',
			'title' => __( 'Podcast keywords', 'podcasting' ),
			'type'  => 'textfield',
		),
		array(
			'slug'    => 'podcasting_category_1',
			'title'   => __( 'Podcast category 1', 'podcasting' ),
			'type'    => 'select',
			'options' => get_podcasting_categories(),
		),
		array(
			'slug'    => 'podcasting_category_2',
			'title'   => __( 'Podcast category 2', 'podcasting' ),
			'type'    => 'select',
			'options' => get_podcasting_categories(),
		),
		array(
			'slug'    => 'podcasting_category_3',
			'title'   => __( 'Podcast category 3', 'podcasting' ),
			'type'    => 'select',
			'options' => get_podcasting_categories(),
		),
	);
}

/**
 * Get the podcasting categories.
 */
function get_podcasting_categories() {
	$to_return = array( __( 'None' ) );
	$categories = array(
		__( 'Arts', 'podcasting' ) => array(
			__( 'Design', 'podcasting' ),
			__( 'Fashion & Beauty', 'podcasting' ),
			__( 'Food', 'podcasting' ),
			__( 'Literature', 'podcasting' ),
			__( 'Performing Arts', 'podcasting' ),
			__( 'Visual Arts', 'podcasting' )
		),
		__( 'Business', 'podcasting' ) => array(
			__( 'Business News', 'podcasting' ),
			__( 'Careers', 'podcasting' ),
			__( 'Investing', 'podcasting' ),
			__( 'Management & Marketing', 'podcasting' ),
			__( 'Shopping', 'podcasting' )
		),
		__( 'Comedy', 'podcasting' ) => array(),
		__( 'Education', 'podcasting' ) => array(
			__( 'Educational Technology', 'podcasting' ),
			__( 'Higher Education', 'podcasting' ),
			__( 'K-12', 'podcasting' ),
			__( 'Language Courses', 'podcasting' ),
			__( 'Training', 'podcasting' )
		),
		__( 'Games & Hobbies', 'podcasting' ) => array(
			__( 'Automotive', 'podcasting' ),
			__( 'Aviation', 'podcasting' ),
			__( 'Hobbies', 'podcasting' ),
			__( 'Other Games', 'podcasting' ),
			__( 'Video Games', 'podcasting' )
		),
		__( 'Government & Organizations', 'podcasting' ) => array(
			__( 'Local', 'podcasting' ),
			__( 'National', 'podcasting' ),
			__( 'Non-Profit', 'podcasting' ),
			__( 'Regional', 'podcasting' )
		),
		__( 'Health', 'podcasting' ) => array(
			__( 'Alternative Health', 'podcasting' ),
			__( 'Fitness & Nutrition', 'podcasting' ),
			__( 'Self-Help', 'podcasting' ),
			__( 'Sexuality', 'podcasting' )
		),
		__( 'Kids & Family', 'podcasting' ) => array(),
		__( 'Music', 'podcasting' ) => array(),
		__( 'News & Politics', 'podcasting' ) => array(),
		__( 'Religion & Spirituality', 'podcasting' ) => array(
			__( 'Buddhism', 'podcasting' ),
			__( 'Christianity', 'podcasting' ),
			__( 'Hinduism', 'podcasting' ),
			__( 'Islam', 'podcasting' ),
			__( 'Judaism', 'podcasting' ),
			__( 'Other', 'podcasting' ),
			__( 'Spirituality', 'podcasting' ),
		),
		__( 'Science & Medicine', 'podcasting' ) => array(
			__( 'Medicine', 'podcasting' ),
			__( 'Natural Sciences', 'podcasting' ),
			__( 'Social Sciences', 'podcasting' )
		),
		__( 'Society & Culture', 'podcasting' ) => array(
			__( 'History', 'podcasting' ),
			__( 'Personal Journals', 'podcasting' ),
			__( 'Philosophy', 'podcasting' ),
			__( 'Places & Travel', 'podcasting' )
		),
		__( 'Sports & Recreation', 'podcasting' ) => array(
			__( 'Amateur', 'podcasting' ),
			__( 'College & High School', 'podcasting' ),
			__( 'Outdoor', 'podcasting' ),
			__( 'Professional', 'podcasting' ),
		),
		__( 'Technology', 'podcasting' ) => array(
			__( 'Gadgets', 'podcasting' ),
			__( 'Tech News', 'podcasting' ),
			__( 'Podcasting', 'podcasting' ),
			__( 'Software How-To', 'podcasting' ),
		),
		__( 'TV & Film', 'podcasting' ) => array()
	);
	foreach( $categories as $key => $category ) {
		$to_return[] = $key;

		if ( ! empty( $category ) ) {
			foreach( $category as $subcategory ) {
				$to_return[] = $key . ' » ' . $subcategory;
			}
		}
	}
	return $to_return;
}