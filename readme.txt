=== Simple Podcasting ===
Contributors:      10up, helen, adamsilverstein, jakemgold
Tags:              simple podcasting, podcasting, podcast, apple podcasts, episode, gutenberg, blocks, block
Requires at least: 5.7
Tested up to:      6.0
Requires PHP:      7.4
Stable tag:        1.3.0
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Easily set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block for the WordPress block editor (aka Gutenberg).

== Description ==

Easily set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block for the WordPress block editor (aka Gutenberg).

Podcasting is a method to distribute audio and video episodes through a feed to which listeners can subscribe. You can publish podcasts on your WordPress site and make them available for listeners in Apple Podcasts and through direct feed links for other podcasting apps by following these steps:

=== Create your podcast ===

From the WordPress Admin, go to Podcasts.
To create a podcast, complete all of the "Add New Podcast" fields and click "Add New Podcast".

 * Name: this title appears in Apple Podcasts and any other podcast apps.
 * Slug: this is the URL-friendly version of the Name field.
 * Subtitle: the subtitle also appears in Apple Podcasts and any other podcast apps.
 * Artist / Author name: the artist or producer of the work.
 * Podcast email: a contact email address for your podcast.
 * Summary: Apple Podcasts displays this summary when browsing through podcasts.
 * Copyright / License information: copyright information viewable in Apple Podcasts or other podcast apps.
 * Mark as explicit: mark Yes if podcast contains adult language or adult themes.
 * Language: the main language spoken in the podcast.
 * Cover image: add the URL for the cover art to appear in Apple Podcasts and other podcast apps. Click "Select Image" and choose an image from the Media Library. Note that podcast cover images must be between 1400 x 1400 and 3000 x 3000 pixels in JPG or PNG formats to work on Apple Podcasts.
 * Keywords: add terms to help your podcast show up in search results on Apple Podcasts and other podcast apps.
 * Categories: these allow your podcast to show up for those browsing Apple Podcasts or other podcast apps by category.

Repeat for each podcast you would like to create.

=== Add content to your podcast ===

 * Create a new post and assign it to one or more Podcasts using the panel labeled Podcasts.
 * Upload or embed an audio file into this post using any of the usual WordPress methods. If using the new block-based WordPress editor (sometimes referred to as Gutenberg), insert a Podcast block. Only one Podcast block can be inserted per post.
 * For more advanced settings, use the Podcasting meta box to mark explicit content or closed captioning available, season number, episode number, episode type and to optionally specify one media item in the post if you have more than one in your post. In the block-based editor, these are the block settings that appear in the sidebar when the podcast block is selected.

=== Submit your podcast feed to Apple Podcasts ===

* Each podcast has a unique feed URL you can find on the Podcasts page. This is the URL you will submit to Apple.
* Ensure you test feeds before submitting them, see [Apple's "Test a Podcast page"](https://help.apple.com/itc/podcasts_connect/#/itcac471c970) for more information.
* Once the validator passes, submit your podcast. Podcasts submitted to Apple Podcasts do not become immediately available for subscription by others. They are submitted for review by Apple staff, see [Apple's "Submit a podcast" page](https://help.apple.com/itc/podcasts_connect/#/itcd88ea40b9) for more information.

=== Control how many episodes are listed on the feed ===

If you want to adjust the default number of episodes included in a podcast RSS feed, then utilize the following to do so...

`<?php

add_filter( 'simple_podcasting_episodes_per_page', 'podcasting_feed_episodes_per_page' );

/**
 * Filter how many items are displayed on the feed
 * Default is 250
 *
 * @param int $qty Items count.
 * @return string
 */
function podcasting_feed_episodes_per_page( $qty ) {
	return 300;
}
`

=== Customize RSS feed ===

If you want to modify RSS feed items output, there is a filter for that:

`<?php
function podcasting_feed_item_filter( $feed_item = array(), $post_id = null, $term_id = null ) {
	if ( 42 === $post_id ) {
		$feed_item['keywords'] = 'one,two,three';
	}
	return $feed_item;
}
add_filter( 'simple_podcasting_feed_item', 'podcasting_feed_item_filter', 10, 3 );
`

=== Technical Notes ===

* Requires PHP 5.3+.
* RSS feeds must not be disabled.

== Screenshots ==

1. Podcast in classic editor
2. Podcast block in the WordPress block editor (aka Gutenberg)
3. Creating a podcast
4. Podcast feed

== Installation ==
1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file.
2. Activate the plugin.
3. Head to Posts → Podcasts and add at least one podcast.
4. Create a post and insert an audio embed (or a podcast block in Gutenberg) and select a Podcast feed to include it in.

== Changelog ==

= 1.3.0 - 2022-10-18 =
**Note that this version bumps the minimum PHP version from 7.0 to 7.4 and the minimum WordPress version from 4.6 to 5.7.**

* **Added** Podcasts Taxonomy term(s) added in block settings (props [@helen](https://github.com/helen), [@jeffpaul](https://github.com/jeffpaul), [@faisal-alvi](https://github.com/faisal-alvi), [@peterwilsoncc](https://github.com/peterwilsoncc), [@cadic](https://github.com/cadic) via [#183](https://github.com/10up/simple-podcasting/pull/183)).
* **Added** Type of show setting for the podcast (props [@cadic](https://github.com/cadic), [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#188](https://github.com/10up/simple-podcasting/pull/188)).
* **Changed** Podcasting Categories and Sub-Categories (props [@zamanq](https://github.com/zamanq), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter), [@cadic](https://github.com/cadic), [@dchucks](https://github.com/dchucks) via [#179](https://github.com/10up/simple-podcasting/pull/179)).
* **Changed** Bumped minimum PHP version required from 7.0 to 7.4 (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul), [@vikrampm1](https://github.com/vikrampm1) via [#184](https://github.com/10up/simple-podcasting/pull/184)).
* **Changed** Bumped minimum WordPress version required from 4.6 to 5.7 (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul), [@vikrampm1](https://github.com/vikrampm1) via [#184](https://github.com/10up/simple-podcasting/pull/184)).
* **Changed** Upgraded dependencies (props [@cadic](https://github.com/cadic), [@faisal-alvi](https://github.com/faisal-alvi) via [#187](https://github.com/10up/simple-podcasting/pull/187)).
* **Fixed** Saving podcast enclosure with Classic Editor (props [@cadic](https://github.com/cadic), [@faisal-alvi](https://github.com/faisal-alvi) via [#186](https://github.com/10up/simple-podcasting/pull/186)).
* **Security** Bump `got` from 10.7.0 to 11.8.5 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#185](https://github.com/10up/simple-podcasting/pull/185)).
* **Security** Bump `@wordpress/env` from 4.5.0 to 5.2.0 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#185](https://github.com/10up/simple-podcasting/pull/185)).

= 1.2.4 - 2022-07-27 =
* **Added:** Season number, episode number and episode type attributes can now be stored with a Podcast (props [@zamanq](https://github.com/zamanq), [@dchucks](https://github.com/dchucks), [@cadic](https://github.com/cadic) via [#175](https://github.com/10up/simple-podcasting/pull/175)).
* **Changed:** Bump WordPress version "tested up to" 6.0 (props [@cadic](https://github.com/cadic) via [#171](https://github.com/10up/simple-podcasting/issues/171)).
* **Fixed:** Incorrect Language value in the Feed (props [@zamanq](https://github.com/zamanq), [@dchucks](https://github.com/dchucks), [@cadic](https://github.com/cadic) via [#176](https://github.com/10up/simple-podcasting/pull/176)).
* **Security:** Bump `terser` from 5.12.1 to 5.14.2 (props [@dependabot](https://github.com/apps/dependabot) via [#180](https://github.com/10up/simple-podcasting/pull/180)).

= 1.2.3 - 2022-04-28 =
* **Added** Compatibility tests against PHP 7 and 8 (props [@cadic](https://profiles.wordpress.org/cadic), [@dkotter](https://profiles.wordpress.org/dkotter), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Added** Default Pull Request Reviewers via CODEOWNERS file (props [@jeffpaul](https://profiles.wordpress.org/jeffpaul), [@cadic](https://profiles.wordpress.org/cadic)).
* **Added** Dependency security scanning (props [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Changed** Unit tests against PHP 8 (props [@cadic](https://profiles.wordpress.org/cadic), [@dkotter](https://profiles.wordpress.org/dkotter), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Changed** Bump required PHP 7.0 (props [@cadic](https://profiles.wordpress.org/cadic), [@dkotter](https://profiles.wordpress.org/dkotter), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Changed** Replaced custom commands with @10up/cypress-wp-utils in end-to-end tests (props [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu), [@cadic](https://profiles.wordpress.org/cadic), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Fixed** Missing `<enclosure>` in feed item (props [@davexpression](https://profiles.wordpress.org/davexpression/), [@cadic](https://profiles.wordpress.org/cadic), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Fixed** Failing Cypress test on WP Minimum (props [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu), [@cadic](https://profiles.wordpress.org/cadic), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Fixed** Updated badges in readme (props [@cadic](https://profiles.wordpress.org/cadic), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Security** Upgraded node dependencies (props [@cadic](https://profiles.wordpress.org/cadic), [@dharm1025](https://profiles.wordpress.org/dharm1025), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Security** Bump async from 2.6.3 to 2.6.4 (props [@dependabot](https://github.com/apps/dependabot)).
* **Security** Bump node-forge from 1.2.1 to 1.3.0 (props [@dependabot](https://github.com/apps/dependabot)).
* **Security** Bump minimist from 1.2.5 to 1.2.6 (props [@dependabot](https://github.com/apps/dependabot).

= 1.2.2 - 2022-03-01 =
* **Added:** Filter 'simple_podcasting_feed_item' to modify RSS feed item data before output (props [@cadic](https://profiles.wordpress.org/cadic), [@dharm1025](https://profiles.wordpress.org/dharm1025), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Added:** Unit tests (props [@cadic](https://profiles.wordpress.org/cadic), [@dkotter](https://profiles.wordpress.org/dkotter), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Added:** GitHub action job to run PHPCS (props [@cadic](https://profiles.wordpress.org/cadic), [@dkotter](https://profiles.wordpress.org/dkotter)).
* **Added:** Auto-create pot file in languages folder during the build process (props [@dkotter](https://profiles.wordpress.org/dkotter), [@cadic](https://profiles.wordpress.org/cadic)).
* **Changed:** Bump WordPress "tested up to" version 5.9 (props [@sudip-10up](https://github.com/sudip-10up), [@cadic](https://profiles.wordpress.org/cadic), [@peterwilsoncc](https://profiles.wordpress.org/peterwilsoncc)).
* **Fixed:** End-to-end tests with WordPress 5.9 element IDs (props[@cadic](https://profiles.wordpress.org/cadic), [@felipeelia](https://profiles.wordpress.org/felipeelia), [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu)).
* **Fixed:** Podcast feed link output on Edit Podcast screen (props [@mehidi258](https://profiles.wordpress.org/mehidi258), [@jeffpaul](https://profiles.wordpress.org/jeffpaul), [@cadic](https://profiles.wordpress.org/cadic)).
* **Fixed:** Bug fix for `is_feed` being called too early (props [@tomjn](https://profiles.wordpress.org/tomjn), [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Fixed:** Missing and incorrect text-domain (props [@dkotter](https://profiles.wordpress.org/dkotter), [@cadic](https://profiles.wordpress.org/cadic)).
* **Security:** Bump `nanoid` from 3.1.25 to 3.2.0 (props [@dependabot](https://github.com/apps/dependabot)).

= 1.2.1 =
* **Added:** Filter 'simple_podcasting_episodes_per_page' to override default of 250 episodes per podcast feed (props [@pabamato](https://profiles.wordpress.org/pabamato), [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu), [@monomo111](https://github.com/monomo111), [@jeffpaul](https://profiles.wordpress.org/jeffpaul/), [@jakemgold](https://profiles.wordpress.org/jakemgold/)).
* **Added:** End-to-end testing using Cypress and `wp-env` (props [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu), [@markjaquith](https://profiles.wordpress.org/markjaquith/), [@youknowriad](https://profiles.wordpress.org/youknowriad/), [@helen](https://profiles.wordpress.org/helen/)).
* **Added:** Issue management automation via GitHub Actions (props [@jeffpaul](https://profiles.wordpress.org/jeffpaul)).
* **Added:** Pull request template (props [@jeffpaul](https://profiles.wordpress.org/jeffpaul), [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu)).
* **Changed:** Default number of episodes in RSS feeds increased from 10 to 250 (props [@pabamato](https://profiles.wordpress.org/pabamato), [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu), [@monomo111](https://github.com/monomo111), [@jeffpaul](https://profiles.wordpress.org/jeffpaul/), [@jakemgold](https://profiles.wordpress.org/jakemgold/)).
* **Changed:** Use `@wordpress/scripts` as the build tool (props [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu)).
* **Changed:** Bump WordPress version “tested up to” 5.8.1 (props [David Chabbi](https://profiles.wordpress.org/davidchabbi/), [@jeffpaul](https://profiles.wordpress.org/jeffpaul/),[@pabamato](https://profiles.wordpress.org/pabamato)).
* **Changed:** Documentation updates (props [@meszarosrob](https://profiles.wordpress.org/meszarosrob/), [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu)).
* **Fixed:** 'podcast' block core dependency  (props [@pabamato](https://profiles.wordpress.org/pabamato), [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu), [@monomo111](https://github.com/monomo111), [@jeffpaul](https://profiles.wordpress.org/jeffpaul/), [@jakemgold](https://profiles.wordpress.org/jakemgold/)).
* **Fixed:** Minimum WordPress version used by `wp-env` (props [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu)).

= 1.2.0 =
* **Added:** Podcast image in the taxonomy list table view (props [@jonmchristensen](https://profiles.wordpress.org/jonmchristensen), [@helen](https://profiles.wordpress.org/helen)).
* **Added:** Ability for user to transform to/from the podcast and audio blocks (props [@jonmchristensen](https://profiles.wordpress.org/jonmchristensen), [@helen](https://profiles.wordpress.org/helen)).
* **Added:** Core `MediaReplaceFlow` to edit the podcast media (props [@jonmchristensen](https://profiles.wordpress.org/jonmchristensen), [@helen](https://profiles.wordpress.org/helen)).
* **Changed:** GitHub Actions from HCL to YAML workflow syntax (props [@helen](https://profiles.wordpress.org/helen)).
* **Changed:** Stop committing built files to Git (props [@helen](https://profiles.wordpress.org/helen)).
* **Changed:** Documentation updates (props [@jeffpaul](https://profiles.wordpress.org/jeffpaul), [@nhalstead](https://profiles.wordpress.org/nhalstead)).
* **Fixed:** Using the upload or drag and drop instead of media library populates duration and mimetype (props [@jonmchristensen](https://profiles.wordpress.org/jonmchristensen), [@helen](https://profiles.wordpress.org/helen)).
* **Fixed:** Issue where it is possible to add non-audio files to the Podcast block (props [@mattheu](https://profiles.wordpress.org/mattheu)).
* **Fixed:** Issue where React would throw an error relating to keys for list items (props [@jonmchristensen](https://profiles.wordpress.org/jonmchristensen), [@helen](https://profiles.wordpress.org/helen)).
* **Fixed:** Ensure podcast-related meta is deleted after block is removed. (props [@dinhtungdu](https://profiles.wordpress.org/dinhtungdu)).

= 1.1.1 =
* Fixed: Compatibility with WordPress 5.2 (props [@adamsilverstein](https://profiles.wordpress.org/adamsilverstein)).

= 1.1.0 =
* Added: Retrieve metadata for externally hosted audio files in the block editor.
* Added: Specify email address for a given podcast.
* Added: Set language for a given podcast.
* Tweaked: Clearer language on the add new podcast form.
* Bug fix: Delete all associated meta when block is removed from a post.
* Bug fix: Restore all block editor functionality to align with Gutenberg/block changes.
* Bug fix: Fully clear add new form after creating a new podcast.
* Developers: Add linting for coding standards.

= 1.0.1 =
* Bug fix: Properly output podcast categories and subcategories in the feed.
* Bug fix: Avoid a minified JS error when selecting a podcast image.
* Bug fix: Display podcast summary on edit form.

= 1.0 =
* Initial plugin release.

== Upgrade Notice ==

= 1.3.0 =
* Note that this version bumps the minimum PHP version from 7.0 to 7.4 and the minimum WordPress version from 4.6 to 5.7.
