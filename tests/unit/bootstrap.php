<?php
/**
 * The bootstrap file for PHPUnit tests for the Simple Podcasting plugin.
 * Starts up WP_Mock and requires the files needed for testing.
 */

define( 'TEST_PLUGIN_DIR', dirname( dirname( __DIR__ ) ) . '/' );

// First we need to load the composer autoloader so we can use WP Mock
require_once TEST_PLUGIN_DIR . '/vendor/autoload.php';

// Now call the bootstrap method of WP Mock.
WP_Mock::bootstrap();

define( 'PODCASTING_VERSION', '1.2.1' );
define( 'PODCASTING_PATH', TEST_PLUGIN_DIR );
define( 'PODCASTING_URL', 'https://example.com/wp-content/plugins/simple-podcasting/' );
define( 'PODCASTING_TAXONOMY_NAME', 'podcasting_podcasts' );
define( 'PODCASTING_ITEMS_PER_PAGE', 250 );

require TEST_PLUGIN_DIR . 'includes/blocks.php';
require TEST_PLUGIN_DIR . 'includes/customize-feed.php';
require TEST_PLUGIN_DIR . 'includes/datatypes.php';
require TEST_PLUGIN_DIR . 'includes/helpers.php';
require TEST_PLUGIN_DIR . 'includes/post-meta-box.php';
require TEST_PLUGIN_DIR . 'includes/rest-external-url.php';
require TEST_PLUGIN_DIR . 'includes/transcripts.php';
