<?php
namespace tenup_podcasting;

/**
 * Add a custom podcasts taxonomy.
 */
function create_podcasts_taxonomy() {
	register_taxonomy( Podcasting::$taxonomy, 'post', array(
		'labels'        => array(
			'name'              => 'Podcasts',
			'singular_name'     => 'Podcast',
			'search_items'      => 'Search Podcasts',
			'all_items'         => 'All Podcasts',
			'parent_item'       => 'Parent Podcast',
			'parent_item_colon' => 'Parent Podcast:',
			'edit_item'         => 'Edit Podcast',
			'update_item'       => 'Update Podcast',
			'add_new_item'      => 'Add New Podcast',
			'new_item_name'     => 'New Podcast',
			'menu_name'         => 'Podcasts'
		),
		'hierarchical'      => true,
		'show_tagcloud'     => false,
		'public'            => true,
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
	$podcasting_meta_fields = podcasting_get_meta_fields();

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
		'Podcasts',
		'Podcasts',
		'manage_options',
		'edit-tags.php?taxonomy=podcasting_podcasts&amp;podcasts=true',
		null,
		'dashicons-microphone',
		13
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\add_top_level_menu' );

/**
 * Add podcasting fields to the term screen.
 */
function add_podcasting_term_meta_fields() {
	$podcasting_meta_fields = podcasting_get_meta_fields();

	foreach( $podcasting_meta_fields as $field ) {
		switch ( $field['type'] ) {
			case 'textfield':
				$fm = new Fieldmanager_TextField( array(
					'name' => $field['slug'],
				) );
				$fm->add_term_meta_box( $field['title'], Podcasting::$taxonomy );
				break;
			case 'textarea':
				$fm = new Fieldmanager_TextArea( array(
					'name' => $field['slug'],
				) );
				$fm->add_term_meta_box( $field['title'], Podcasting::$taxonomy );
				break;
			case 'select':
				$fm = new Fieldmanager_Select( array(
					'name'    => $field['slug'],
					'options' => $field['options'],
				) );
				$fm->add_term_meta_box( $field['title'], Podcasting::$taxonomy );
				break;
			case 'image':
				$fm = new Fieldmanager_Media( array(
					'name'         => $field['slug'],
					'button_label' => 'Select Image',
					'modal_title'  => $field['title'],
					'modal_button_label' => 'Select Image',
					'preview_size' => 'thumbnail',
					'description'  => $field['description'],
				) );
				$fm->add_term_meta_box( $field['title'], Podcasting::$taxonomy );
				break;
		}

	}
}
add_action( 'fm_term_' . Podcasting::$taxonomy, __NAMESPACE__ . '\add_podcasting_term_meta_fields' );

/**
 * Add podcasting nonce to the term screen.
 */
function add_podcasting_term_meta_nonce( $term, $taxonomy = false ) {
	echo '<style>
	.term-description-wrap{
		display: none;
	} </style>';
	if ( $taxonomy ) {
		$url = get_term_feed_link( $term->term_id, Podcasting::$taxonomy );
		echo '<strong>Your Podcast Feed: </strong> <a href="' . esc_url( $url ) . '" target="_blank">' . esc_url( $url ) . '</a><br />';
		echo 'This is the URL you submit to iTunes or podcasting service.';
	}

}
add_action( Podcasting::$taxonomy . '_add_form_fields', __NAMESPACE__ . '\add_podcasting_term_meta_nonce' );
add_action( Podcasting::$taxonomy . '_edit_form_fields', __NAMESPACE__ . '\add_podcasting_term_meta_nonce', 99, 2 );
/**
 * Add a feed link to the podcasting term table.
 *
 * @param string $string      Blank string.
 * @param string $column_name Name of the column.
 * @param int    $term_id     Term ID.
 *
 */
function add_podcasting_term_feed_link_column( $string, $column_name, $term_id ) {

	if ( 'feedurl' === $column_name ) {
		$url = get_term_feed_link( $term_id, Podcasting::$taxonomy );
		echo '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_url( $url ) . '</a>';
	}
	return $string;
}
add_filter( 'manage_' . Podcasting::$taxonomy . '_custom_column', __NAMESPACE__ . '\add_podcasting_term_feed_link_column',10,3);

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
add_filter( 'manage_edit-' . Podcasting::$taxonomy . '_columns', __NAMESPACE__ . '\add_custom_term_columns', 99 );

/**
 * Get the meta fields used for podcasts.
 */
function get_meta_fields() {
	return array(
		array(
			'slug'  => 'podcasting_subtitle',
			'title' => 'Podcast subtitle',
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_talent_name',
			'title' => 'Podcast talent',
			'type'  => 'textfield',
		),
		array(
			'slug'  => 'podcasting_summary',
			'title' => 'Podcast summary',
			'type'  => 'textarea',
		),
		array(
			'slug'  => 'podcasting_copyright',
			'title' => 'Podcast copyright',
			'type'  => 'textfield',
		),
		array(
			'slug'    => 'podcasting_explicit',
			'title'   => 'Mark as explicit',
			'type'    => 'select',
			'options' => array(
				'No',
				'Yes',
				'Clean',
			)
		),
		array(
			'slug'  => 'podcasting_image',
			'title' => 'Podcast image',
			'type'  => 'image',
			'description' => 'Minimum size: 1400px x 1400 px — maximum size: 2048px x 2048px'
		),
		array(
			'slug'  => 'podcasting_keywords',
			'title' => 'Podcast keywords',
			'type'  => 'textfield',
		),
		array(
			'slug'    => 'podcasting_category_1',
			'title'   => 'Podcast category 1',
			'type'    => 'select',
			'options' => podcasting_get_podcasting_categories(),
		),
		array(
			'slug'    => 'podcasting_category_2',
			'title'   => 'Podcast category 2',
			'type'    => 'select',
			'options' => podcasting_get_podcasting_categories(),
		),
		array(
			'slug'    => 'podcasting_category_3',
			'title'   => 'Podcast category 3',
			'type'    => 'select',
			'options' => podcasting_get_podcasting_categories(),
		),
	);
}

/**
 * Get the podcasting categories.
 */
function get_podcasting_categories() {
	$to_return = array( 'None' );
	$categories = array(
		"Arts" => array(
			"Design",
			"Fashion & Beauty",
			"Food",
			"Literature",
			"Performing Arts",
			"Visual Arts"
		),
		"Business" => array(
			"Business News",
			"Careers",
			"Investing",
			"Management & Marketing",
			"Shopping"
		),
		"Comedy" => array(),
		"Education" => array(
			"Educational Technology",
			"Higher Education",
			"K-12",
			"Language Courses",
			"Training"
		),
		"Games & Hobbies" => array(
			"Automotive",
			"Aviation",
			"Hobbies",
			"Other Games",
			"Video Games"
		),
		"Government & Organizations" => array(
			"Local",
			"National",
			"Non-Profit",
			"Regional"
		),
		"Health" => array(
			"Alternative Health",
			"Fitness & Nutrition",
			"Self-Help",
			"Sexuality"
		),
		"Kids & Family" => array(),
		"Music" => array(),
		"News & Politics" => array(),
		"Religion & Spirituality" => array(
			"Buddhism",
			"Christianity",
			"Hinduism",
			"Islam",
			"Judaism",
			"Other",
			"Spirituality",
		),
		"Science & Medicine" => array(
			"Medicine",
			"Natural Sciences",
			"Social Sciences"
		),
		"Society & Culture" => array(
			"History",
			"Personal Journals",
			"Philosophy",
			"Places & Travel"
		),
		"Sports & Recreation" => array(
			"Amateur",
			"College & High School",
			"Outdoor",
			"Professional",
		),
		"Technology" => array(
			"Gadgets",
			"Tech News",
			"Podcasting",
			"Software How-To",
		),
		"TV & Film" => array()
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