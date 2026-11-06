<?php
/**
 * Manual check for the Simple Podcasting abilities.
 *
 * Run with WP-CLI on a site that has the plugin active:
 *
 *     wp eval-file wp-content/plugins/simple-podcasting/tests/bin/abilities-check.php
 *
 * It creates temporary users and podcasts, then deletes them.
 *
 * @package tenup_podcasting
 */

$failures = 0;
$checks   = 0;

$check = function ( $label, $condition ) use ( &$failures, &$checks ) {
	++$checks;
	if ( $condition ) {
		WP_CLI::log( "PASS  $label" );
	} else {
		++$failures;
		WP_CLI::warning( "FAIL  $label" );
	}
};

$run = function ( $name, $input ) {
	return wp_get_ability( $name )->execute( $input );
};

$users = array();
foreach ( array( 'editor', 'subscriber' ) as $role ) {
	$users[ $role ] = wp_insert_user(
		array(
			'user_login' => 'abilities_check_' . $role . '_' . wp_rand(),
			'user_pass'  => wp_generate_password(),
			'role'       => $role,
		)
	);
}

$ids = array();

wp_set_current_user( 0 );
$check( 'abilities are registered', null !== wp_get_ability( 'simple-podcasting/create-podcast' ) && null !== wp_get_ability( 'simple-podcasting/delete-podcast' ) );
$check( 'logged-out user cannot list', true !== wp_get_ability( 'simple-podcasting/list-podcasts' )->check_permissions( array() ) );

wp_set_current_user( $users['subscriber'] );
$check( 'subscriber cannot create', true !== wp_get_ability( 'simple-podcasting/create-podcast' )->check_permissions( array( 'name' => 'Nope' ) ) );

wp_set_current_user( $users['editor'] );
$created = $run(
	'simple-podcasting/create-podcast',
	array(
		'name'        => 'Abilities Check ' . wp_rand(),
		'talent_name' => 'Jane Doe',
		'summary'     => 'Summary',
		'explicit'    => 'no',
		'category_1'  => 'arts',
		'platforms'   => array( 'apple-podcasts' => 'https://podcasts.apple.com/us/podcast/id123' ),
	)
);
$check( 'editor can create a podcast', is_array( $created ) && ! empty( $created['id'] ) );
$id    = $created['id'] ?? 0;
$ids[] = $id;
$check( 'create saves the Apple Podcasts link', 'https://podcasts.apple.com/us/podcast/id123' === ( $created['platforms']->{'apple-podcasts'} ?? '' ) );
$check( 'create returns a feed URL', ! empty( $created['feed_url'] ) );

$bad = $run( 'simple-podcasting/create-podcast', array( 'name' => 'Bad URL', 'platforms' => array( 'apple-podcasts' => 'not a url' ) ) );
$check( 'invalid platform URL is rejected', is_wp_error( $bad ) );
$bad = $run(
	'simple-podcasting/create-podcast',
	array(
		'name'      => 'Bad Scheme',
		'platforms' => array( 'apple-podcasts' => 'javascript://example.com/%0Aalert(1)' ),
	)
);
$check( 'non-web URL scheme is rejected', is_wp_error( $bad ) );
$bad = $run( 'simple-podcasting/create-podcast', array( 'name' => 'Bad Image', 'image' => 999999 ) );
$check( 'missing image is rejected', is_wp_error( $bad ) );

$updated = $run( 'simple-podcasting/update-podcast', array( 'id' => $id, 'summary' => 'New summary' ) );
$check( 'update changes the summary', is_array( $updated ) && 'New summary' === $updated['summary'] );
$check( 'update keeps other fields', is_array( $updated ) && 'Jane Doe' === $updated['talent_name'] );

$got = $run( 'simple-podcasting/get-podcast', array( 'id' => $id ) );
$check( 'get returns the podcast', is_array( $got ) && $got['id'] === $id );
$list = $run( 'simple-podcasting/list-podcasts', array() );
$check( 'list contains the podcast', in_array( $id, wp_list_pluck( $list['podcasts'], 'id' ), true ) );
$check( 'get on a missing id fails', is_wp_error( $run( 'simple-podcasting/get-podcast', array( 'id' => 999999 ) ) ) );

wp_set_current_user( $users['subscriber'] );
$check( 'subscriber cannot update', true !== wp_get_ability( 'simple-podcasting/update-podcast' )->check_permissions( array( 'id' => $id ) ) );
$check( 'subscriber cannot delete', true !== wp_get_ability( 'simple-podcasting/delete-podcast' )->check_permissions( array( 'id' => $id, 'confirm' => true ) ) );

wp_set_current_user( $users['editor'] );
$check( 'delete needs confirm', is_wp_error( $run( 'simple-podcasting/delete-podcast', array( 'id' => $id, 'confirm' => false ) ) ) );
$deleted = $run( 'simple-podcasting/delete-podcast', array( 'id' => $id, 'confirm' => true ) );
$check( 'editor can delete', is_array( $deleted ) && true === $deleted['success'] );
$check( 'podcast is gone', ! get_term( $id, PODCASTING_TAXONOMY_NAME ) || is_wp_error( get_term( $id, PODCASTING_TAXONOMY_NAME ) ) || null === get_term( $id, PODCASTING_TAXONOMY_NAME ) );

foreach ( $users as $user_id ) {
	wp_delete_user( $user_id );
}

if ( $failures ) {
	WP_CLI::error( "$failures of $checks checks failed." );
}
WP_CLI::success( "All $checks checks passed." );
