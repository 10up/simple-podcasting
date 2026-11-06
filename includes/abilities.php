<?php
/**
 * Register Simple Podcasting abilities with the WordPress Abilities API.
 *
 * Lets AI agents and other clients manage podcasts (the `podcasting_podcasts`
 * terms and their feed settings, including the Apple Podcasts link).
 *
 * @package tenup_podcasting
 */

namespace tenup_podcasting\abilities;

use WP_Error;
use WP_Term;

use function tenup_podcasting\get_podcasting_categories_options;
use function tenup_podcasting\get_supported_platforms;
use function tenup_podcasting\update_podcast_term_meta;

const CATEGORY = 'simple-podcasting';

/**
 * Register the hooks.
 *
 * @since x.x.x
 */
function setup() {
	add_action( 'wp_abilities_api_categories_init', __NAMESPACE__ . '\register_category' );
	add_action( 'wp_abilities_api_init', __NAMESPACE__ . '\register_abilities' );
}

/**
 * Register the ability category.
 *
 * @since x.x.x
 */
function register_category() {
	wp_register_ability_category(
		CATEGORY,
		array(
			'label'       => __( 'Simple Podcasting', 'simple-podcasting' ),
			'description' => __( 'Abilities for managing podcasts and their feed settings.', 'simple-podcasting' ),
		)
	);
}

/**
 * The editable podcast fields.
 *
 * Keys are the ability field names. Each one is stored in term meta under
 * `podcasting_<key>`.
 *
 * @since x.x.x
 *
 * @return array[] Field schemas keyed by field name.
 */
function get_field_schemas() {
	$categories = array_map( 'strval', array_keys( get_podcasting_categories_options() ) );
	$platforms  = array();

	foreach ( get_supported_platforms() as $slug => $platform ) {
		$platforms[ $slug ] = array(
			'type'        => 'string',
			/* translators: %s: platform name, for example Apple Podcasts. */
			'description' => sprintf( __( 'URL of the podcast on %s. Use an empty string to remove it.', 'simple-podcasting' ), wp_strip_all_tags( $platform['title'] ) ),
		);
	}

	$category_description = __( 'Apple Podcasts category key, for example "arts" or "arts:books". Use an empty string for none.', 'simple-podcasting' );

	return array(
		'subtitle'     => array(
			'type'        => 'string',
			'description' => __( 'Short subtitle of the podcast.', 'simple-podcasting' ),
		),
		'talent_name'  => array(
			'type'        => 'string',
			'description' => __( 'Artist or author name. Required by Apple Podcasts.', 'simple-podcasting' ),
		),
		'email'        => array(
			'type'        => 'string',
			'description' => __( 'Contact email for the podcast.', 'simple-podcasting' ),
		),
		'summary'      => array(
			'type'        => 'string',
			'description' => __( 'Summary of the podcast. Required by Apple Podcasts.', 'simple-podcasting' ),
		),
		'copyright'    => array(
			'type'        => 'string',
			'description' => __( 'Copyright or license information.', 'simple-podcasting' ),
		),
		'explicit'     => array(
			'type'        => 'string',
			'enum'        => array( 'no', 'yes', 'clean' ),
			'description' => __( 'Whether the podcast contains explicit content.', 'simple-podcasting' ),
		),
		'language'     => array(
			'type'        => 'string',
			'pattern'     => '^([a-z]{2,3}(_[A-Za-z0-9]+)?)?$',
			'description' => __( 'WordPress locale of the podcast, for example en_US.', 'simple-podcasting' ),
		),
		'image'        => array(
			'type'        => 'integer',
			'minimum'     => 1,
			'description' => __( 'Media library ID of the cover image. Apple Podcasts requires 1400px to 2048px square. Required by Apple Podcasts.', 'simple-podcasting' ),
		),
		'keywords'     => array(
			'type'        => 'string',
			'description' => __( 'Comma-separated keywords.', 'simple-podcasting' ),
		),
		'type_of_show' => array(
			'type'        => 'string',
			'enum'        => array( '0', 'episodic', 'serial' ),
			'description' => __( 'Type of show. Use 0 for not set.', 'simple-podcasting' ),
		),
		'category_1'   => array(
			'type'        => 'string',
			'enum'        => $categories,
			'description' => $category_description,
		),
		'category_2'   => array(
			'type'        => 'string',
			'enum'        => $categories,
			'description' => $category_description,
		),
		'category_3'   => array(
			'type'        => 'string',
			'enum'        => $categories,
			'description' => $category_description,
		),
		'icon_theme'   => array(
			'type'        => 'string',
			'enum'        => array( 'color', 'black', 'white' ),
			'description' => __( 'Icon theme for the platform links block.', 'simple-podcasting' ),
		),
		'platforms'    => array(
			'type'                 => 'object',
			'properties'           => $platforms,
			'additionalProperties' => false,
			'description'          => __( 'Links to the podcast on listening platforms, including Apple Podcasts, keyed by platform. Platforms you leave out are cleared on update.', 'simple-podcasting' ),
		),
	);
}

/**
 * Schema for a podcast returned by the abilities.
 *
 * @since x.x.x
 *
 * @return array
 */
function get_podcast_output_schema() {
	return array(
		'type'       => 'object',
		'properties' => array_merge(
			array(
				'id'            => array( 'type' => 'integer' ),
				'name'          => array( 'type' => 'string' ),
				'slug'          => array( 'type' => 'string' ),
				'description'   => array( 'type' => 'string' ),
				'feed_url'      => array(
					'type'        => 'string',
					'description' => __( 'URL of the podcast feed to submit to Apple Podcasts and other directories.', 'simple-podcasting' ),
				),
				'episode_count' => array( 'type' => 'integer' ),
				'image_url'     => array( 'type' => 'string' ),
			),
			array_map(
				function ( $schema ) {
					// Saved values can be empty, so the output does not repeat the input limits.
					unset( $schema['enum'], $schema['pattern'], $schema['minimum'] );
					return $schema;
				},
				get_field_schemas()
			)
		),
	);
}

/**
 * Register the abilities.
 *
 * @since x.x.x
 */
function register_abilities() {
	$podcast_output = get_podcast_output_schema();
	$fields         = get_field_schemas();

	$term_fields = array(
		'name'        => array(
			'type'        => 'string',
			'minLength'   => 1,
			'description' => __( 'Name of the podcast.', 'simple-podcasting' ),
		),
		'slug'        => array(
			'type'        => 'string',
			'description' => __( 'URL slug of the podcast. Created from the name when left out.', 'simple-podcasting' ),
		),
		'description' => array(
			'type'        => 'string',
			'description' => __( 'Description of the podcast.', 'simple-podcasting' ),
		),
	);

	$id_schema = array(
		'type'        => 'integer',
		'minimum'     => 1,
		'description' => __( 'ID of the podcast.', 'simple-podcasting' ),
	);

	$mcp = array( 'public' => true );

	wp_register_ability(
		'simple-podcasting/list-podcasts',
		array(
			'label'               => __( 'List podcasts', 'simple-podcasting' ),
			'description'         => __( 'Lists the podcasts on this site with their feed URLs and settings.', 'simple-podcasting' ),
			'category'            => CATEGORY,
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array(
					'search' => array(
						'type'        => 'string',
						'description' => __( 'Only return podcasts whose name matches this text.', 'simple-podcasting' ),
					),
				),
				'additionalProperties' => false,
			),
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'podcasts' => array(
						'type'  => 'array',
						'items' => $podcast_output,
					),
				),
				'required'   => array( 'podcasts' ),
			),
			'execute_callback'    => __NAMESPACE__ . '\execute_list',
			'permission_callback' => __NAMESPACE__ . '\can_list',
			'meta'                => array(
				'show_in_rest' => true,
				'mcp'          => $mcp,
				'annotations'  => array(
					'readonly'    => true,
					'destructive' => false,
					'idempotent'  => true,
				),
			),
		)
	);

	wp_register_ability(
		'simple-podcasting/get-podcast',
		array(
			'label'               => __( 'Get a podcast', 'simple-podcasting' ),
			'description'         => __( 'Returns one podcast with all its feed settings, including the Apple Podcasts link.', 'simple-podcasting' ),
			'category'            => CATEGORY,
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array( 'id' => $id_schema ),
				'required'             => array( 'id' ),
				'additionalProperties' => false,
			),
			'output_schema'       => $podcast_output,
			'execute_callback'    => __NAMESPACE__ . '\execute_get',
			'permission_callback' => __NAMESPACE__ . '\can_edit_podcast',
			'meta'                => array(
				'show_in_rest' => true,
				'mcp'          => $mcp,
				'annotations'  => array(
					'readonly'    => true,
					'destructive' => false,
					'idempotent'  => true,
				),
			),
		)
	);

	wp_register_ability(
		'simple-podcasting/create-podcast',
		array(
			'label'               => __( 'Create a podcast', 'simple-podcasting' ),
			'description'         => __( 'Creates a podcast and its feed. Apple Podcasts needs the author name, summary, cover image and first category.', 'simple-podcasting' ),
			'category'            => CATEGORY,
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array_merge( $term_fields, $fields ),
				'required'             => array( 'name' ),
				'additionalProperties' => false,
			),
			'output_schema'       => $podcast_output,
			'execute_callback'    => __NAMESPACE__ . '\execute_create',
			'permission_callback' => __NAMESPACE__ . '\can_create',
			'meta'                => array(
				'show_in_rest' => true,
				'mcp'          => $mcp,
				'annotations'  => array(
					'readonly'    => false,
					'destructive' => false,
					'idempotent'  => false,
				),
			),
		)
	);

	wp_register_ability(
		'simple-podcasting/update-podcast',
		array(
			'label'               => __( 'Update a podcast', 'simple-podcasting' ),
			'description'         => __( 'Changes the given settings of a podcast. Fields you leave out stay as they are. The platforms field replaces all platform links.', 'simple-podcasting' ),
			'category'            => CATEGORY,
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array_merge( array( 'id' => $id_schema ), $term_fields, $fields ),
				'required'             => array( 'id' ),
				'additionalProperties' => false,
			),
			'output_schema'       => $podcast_output,
			'execute_callback'    => __NAMESPACE__ . '\execute_update',
			'permission_callback' => __NAMESPACE__ . '\can_edit_podcast',
			'meta'                => array(
				'show_in_rest' => true,
				'mcp'          => $mcp,
				'annotations'  => array(
					'readonly'    => false,
					'destructive' => false,
					'idempotent'  => true,
				),
			),
		)
	);

	wp_register_ability(
		'simple-podcasting/delete-podcast',
		array(
			'label'               => __( 'Delete a podcast', 'simple-podcasting' ),
			'description'         => __( 'Permanently deletes a podcast and its feed. Episodes stay as regular posts. Set confirm to true to proceed.', 'simple-podcasting' ),
			'category'            => CATEGORY,
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array(
					'id'      => $id_schema,
					'confirm' => array(
						'type'        => 'boolean',
						'description' => __( 'Must be true. This cannot be undone.', 'simple-podcasting' ),
					),
				),
				'required'             => array( 'id', 'confirm' ),
				'additionalProperties' => false,
			),
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'success' => array( 'type' => 'boolean' ),
					'id'      => array( 'type' => 'integer' ),
				),
				'required'   => array( 'success' ),
			),
			'execute_callback'    => __NAMESPACE__ . '\execute_delete',
			'permission_callback' => __NAMESPACE__ . '\can_delete_podcast',
			'meta'                => array(
				'show_in_rest' => true,
				'mcp'          => $mcp,
				'annotations'  => array(
					'readonly'    => false,
					'destructive' => true,
					// Core requires DELETE for destructive and idempotent abilities, so this stays false.
					'idempotent'  => false,
				),
			),
		)
	);
}

/**
 * Get the podcast taxonomy object.
 *
 * @return \WP_Taxonomy|null
 */
function get_taxonomy_object() {
	$taxonomy = get_taxonomy( PODCASTING_TAXONOMY_NAME );

	return $taxonomy ? $taxonomy : null;
}

/**
 * Whether the current user can list podcasts.
 *
 * @since x.x.x
 *
 * @return bool
 */
function can_list() {
	$taxonomy = get_taxonomy_object();

	return $taxonomy && current_user_can( $taxonomy->cap->edit_terms );
}

/**
 * Whether the current user can create podcasts.
 *
 * @since x.x.x
 *
 * @return bool
 */
function can_create() {
	return can_list();
}

/**
 * Get the podcast ID from ability input.
 *
 * @param mixed $input Ability input.
 * @return int
 */
function get_input_id( $input ) {
	return is_array( $input ) && isset( $input['id'] ) ? (int) $input['id'] : 0;
}

/**
 * Whether the current user can edit the podcast in the input.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return bool
 */
function can_edit_podcast( $input = null ) {
	$id = get_input_id( $input );

	return $id > 0 && current_user_can( 'edit_term', $id );
}

/**
 * Whether the current user can delete the podcast in the input.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return bool
 */
function can_delete_podcast( $input = null ) {
	$id = get_input_id( $input );

	return $id > 0 && current_user_can( 'delete_term', $id );
}

/**
 * Load a podcast term.
 *
 * @param int $id Term ID.
 * @return WP_Term|WP_Error
 */
function get_podcast_term( $id ) {
	$term = get_term( $id, PODCASTING_TAXONOMY_NAME );

	if ( ! $term instanceof WP_Term ) {
		return new WP_Error(
			'podcast_not_found',
			__( 'No podcast exists with this ID.', 'simple-podcasting' ),
			array( 'status' => 404 )
		);
	}

	return $term;
}

/**
 * Format a podcast for output.
 *
 * @param WP_Term $term The podcast term.
 * @return array
 */
function format_podcast( WP_Term $term ) {
	$feed_url = get_term_feed_link( $term->term_id, PODCASTING_TAXONOMY_NAME );

	$podcast = array(
		'id'            => (int) $term->term_id,
		'name'          => $term->name,
		'slug'          => $term->slug,
		'description'   => $term->description,
		'feed_url'      => is_string( $feed_url ) ? $feed_url : '',
		'episode_count' => (int) $term->count,
		'image_url'     => '',
	);

	foreach ( get_field_schemas() as $key => $schema ) {
		$value = get_term_meta( $term->term_id, 'podcasting_' . $key, true );

		if ( 'platforms' === $key ) {
			$value = is_array( $value ) ? array_filter( array_map( 'strval', $value ) ) : array();
			$value = (object) $value;
		} elseif ( 'image' === $key ) {
			$value                = $value ? (int) $value : 0;
			$podcast['image_url'] = $value ? (string) wp_get_attachment_url( $value ) : '';
			if ( ! $value ) {
				continue;
			}
		} else {
			$value = (string) $value;
		}

		$podcast[ $key ] = $value;
	}

	return $podcast;
}

/**
 * Check the input values that the schema cannot check.
 *
 * @param array $input Ability input.
 * @return true|WP_Error
 */
function validate_input( $input ) {
	if ( isset( $input['image'] ) ) {
		$image_id = (int) $input['image'];

		if ( ! wp_attachment_is_image( $image_id ) || ! current_user_can( 'read_post', $image_id ) ) {
			return new WP_Error(
				'invalid_image',
				__( 'The image must be an image in the media library that you can use.', 'simple-podcasting' ),
				array( 'status' => 400 )
			);
		}
	}

	if ( isset( $input['platforms'] ) ) {
		foreach ( (array) $input['platforms'] as $platform => $url ) {
			// Only web links are allowed. FILTER_VALIDATE_URL alone accepts schemes such as javascript:.
			$is_web_url = false !== filter_var( $url, FILTER_VALIDATE_URL ) && in_array( wp_parse_url( $url, PHP_URL_SCHEME ), array( 'http', 'https' ), true );

			if ( '' !== $url && ! $is_web_url ) {
				return new WP_Error(
					'invalid_platform_url',
					/* translators: %s: platform slug. */
					sprintf( __( 'The URL for %s is not valid.', 'simple-podcasting' ), $platform ),
					array( 'status' => 400 )
				);
			}
		}
	}

	return true;
}

/**
 * Save the podcast fields from the input to term meta.
 *
 * @param int   $term_id Term ID.
 * @param array $input   Ability input.
 */
function save_fields( $term_id, $input ) {
	$values = array();

	foreach ( array_keys( get_field_schemas() ) as $key ) {
		if ( ! isset( $input[ $key ] ) ) {
			continue;
		}

		$value = $input[ $key ];

		if ( 'platforms' === $key ) {
			// Cast so that an empty object from JSON clears all links.
			$value = array_merge(
				array_fill_keys( array_keys( get_supported_platforms() ), '' ),
				(array) $value
			);
		}

		$values[ 'podcasting_' . $key ] = $value;
	}

	update_podcast_term_meta( $term_id, $values );
}

/**
 * Run the list ability.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return array
 */
function execute_list( $input = null ) {
	$args = array(
		'taxonomy'   => PODCASTING_TAXONOMY_NAME,
		'hide_empty' => false,
	);

	if ( is_array( $input ) && ! empty( $input['search'] ) ) {
		$args['search'] = (string) $input['search'];
	}

	$terms = get_terms( $args );

	return array(
		'podcasts' => is_array( $terms ) ? array_map( __NAMESPACE__ . '\format_podcast', $terms ) : array(),
	);
}

/**
 * Run the get ability.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return array|WP_Error
 */
function execute_get( $input = null ) {
	$term = get_podcast_term( get_input_id( $input ) );

	return is_wp_error( $term ) ? $term : format_podcast( $term );
}

/**
 * Run the create ability.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return array|WP_Error
 */
function execute_create( $input = null ) {
	$input = is_array( $input ) ? $input : array();

	$valid = validate_input( $input );
	if ( is_wp_error( $valid ) ) {
		return $valid;
	}

	$args = array();
	foreach ( array( 'slug', 'description' ) as $key ) {
		if ( isset( $input[ $key ] ) ) {
			$args[ $key ] = $input[ $key ];
		}
	}

	$result = wp_insert_term( $input['name'], PODCASTING_TAXONOMY_NAME, $args );
	if ( is_wp_error( $result ) ) {
		$result->add_data( array( 'status' => 400 ) );
		return $result;
	}

	save_fields( $result['term_id'], $input );

	return format_podcast( get_term( $result['term_id'], PODCASTING_TAXONOMY_NAME ) );
}

/**
 * Run the update ability.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return array|WP_Error
 */
function execute_update( $input = null ) {
	$input = is_array( $input ) ? $input : array();
	$term  = get_podcast_term( get_input_id( $input ) );

	if ( is_wp_error( $term ) ) {
		return $term;
	}

	$valid = validate_input( $input );
	if ( is_wp_error( $valid ) ) {
		return $valid;
	}

	$args = array();
	foreach ( array( 'name', 'slug', 'description' ) as $key ) {
		if ( isset( $input[ $key ] ) ) {
			$args[ $key ] = $input[ $key ];
		}
	}

	if ( $args ) {
		$result = wp_update_term( $term->term_id, PODCASTING_TAXONOMY_NAME, $args );
		if ( is_wp_error( $result ) ) {
			$result->add_data( array( 'status' => 400 ) );
			return $result;
		}
	}

	save_fields( $term->term_id, $input );

	return format_podcast( get_term( $term->term_id, PODCASTING_TAXONOMY_NAME ) );
}

/**
 * Run the delete ability.
 *
 * @since x.x.x
 *
 * @param array|null $input Ability input.
 * @return array|WP_Error
 */
function execute_delete( $input = null ) {
	$term = get_podcast_term( get_input_id( $input ) );

	if ( is_wp_error( $term ) ) {
		return $term;
	}

	if ( empty( $input['confirm'] ) ) {
		return new WP_Error(
			'confirmation_required',
			__( 'Set confirm to true to delete this podcast.', 'simple-podcasting' ),
			array( 'status' => 400 )
		);
	}

	$result = wp_delete_term( $term->term_id, PODCASTING_TAXONOMY_NAME );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	if ( ! $result ) {
		return new WP_Error(
			'delete_failed',
			__( 'The podcast could not be deleted.', 'simple-podcasting' ),
			array( 'status' => 500 )
		);
	}

	return array(
		'success' => true,
		'id'      => (int) $term->term_id,
	);
}

setup();
