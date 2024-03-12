=== Simple Podcasting ===
Contributors:      10up, helen, adamsilverstein, jakemgold, jeffpaul, cadic
Tags:              simple podcasting, podcasting, podcast, apple podcasts, episode, gutenberg, blocks, block
Requires at least: 5.7
Tested up to:      6.4
Requires PHP:      7.4
Stable tag:        1.8.0
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Easily set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block and podcast transcript block for the WordPress block editor (aka Gutenberg).

== Description ==

Easily set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block and podcast transcript block for the WordPress block editor (aka Gutenberg).

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
 * For more advanced settings, use the Podcasting meta box to mark explicit content or closed captioning available, season number, episode number, episode type, add a transcript and to optionally specify one media item in the post if you have more than one in your post. In the block-based editor, these are the block settings that appear in the sidebar when the podcast block is selected.
 * Transcript: If desired, an optional transcript can be added from the settings of the Podcast block. This will add a Podcast Transcript block, allowing you to add a transcript consisting of time codes, citations, and paragrah text that can be embedded in the post, linked to an external plain HTML file, or linked in a special `<podcast:transcript>` XML element.

=== Submit your podcast feed to Apple Podcasts ===

* Each podcast has a unique feed URL you can find on the Podcasts page. This is the URL you will submit to Apple.
* Ensure you test feeds before submitting them, see [Apple's "Test a Podcast page"](https://help.apple.com/itc/podcasts_connect/#/itcac471c970) for more information.
* Once the validator passes, submit your podcast. Podcasts submitted to Apple Podcasts do not become immediately available for subscription by others. They are submitted for review by Apple staff, see [Apple's "Submit a podcast" page](https://help.apple.com/itc/podcasts_connect/#/itcd88ea40b9) for more information.

=== Submit your podcast feed to Pocket Casts

* Validate your feeds at [https://www.castfeedvalidator.com/ Cast Feed Validator] before submitting them.
* Submit the podcast feed to https://pocketcasts.com/submit/

=== How do I get my podcast featured on Pocket Casts?

The Featured section of Pocket Casts is human-curated. To ensure that all podcasts have an equal opportunity at being featured, selections are made on the basis of merit.

If you’d like to suggest your podcast for a featured spot, reach out to `curation@pocketcasts.com`

For more information, [https://pocketcasts.com/podcast-producers/ read more].

=== How do I submit private and paid podcast feeds?

Follow this documentation to submit [https://support.pocketcasts.com/article/password-protected-podcasts-2/ private and paid podcast feeds]

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

= 1.8.0 - 2024-03-13 =
* **Added:** "Latest Podcast Episode" query block variation (props [#jeffpaul](https://github.com/jeffpaul), [@cadic](https://github.com/cadic), [@barneyjeffries](https://github.com/barneyjeffries), [@faisal-alvi](https://github.com/faisal-alvi)) via [#266](https://github.com/10up/simple-podcasting/pull/266).
* **Added:** Ability to add Unique Cover Art for Episodes (props [@jamesburgos](https://github.com/jamesburgos), [@jeffpaul](https://github.com/jeffpaul), [@zamanq](https://github.com/zamanq), [@iamdharmesh](https://github.com/iamdharmesh)) via [#273](https://github.com/10up/simple-podcasting/pull/273).
* **Added:** `simple_podcasting_feed_title` filter hook to modify feed title (props [@martinburch](https://github.com/martinburch), [@psorensen](https://github.com/psorensen), [@dkotter](https://github.com/dkotter)) via [#279](https://github.com/10up/simple-podcasting/pull/279).
* **Fixed:** Incorrect feed title (props [@martinburch](https://github.com/martinburch), [@psorensen](https://github.com/psorensen), [@dkotter](https://github.com/dkotter)) via [#279](https://github.com/10up/simple-podcasting/pull/279).
* **Fixed:** Fatal error in WordPress 5.8 and earlier (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@Sidsector9](https://github.com/Sidsector9)) via [#277](https://github.com/10up/simple-podcasting/pull/277).
* **Changed:** Disabled auto sync pull requests with target branch (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul)) via [#281](https://github.com/10up/simple-podcasting/pull/281).
* **Security:** Bumps `ip` from `1.1.8` to `1.1.9` (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9)) via [#278](https://github.com/10up/simple-podcasting/pull/278).

= 1.7.0 - 2024-01-16 =
* **Added:** Ability to add a transcript to a podcast episode by utilizing a new Podcast Transcript block. This block is added by clicking the `Add Transcript` button that will now show in the sidebar panel of the Podcast block (props [@nateconley](https://github.com/nateconley), [@peterwilsoncc](https://github.com/peterwilsoncc), [@sksaju](https://github.com/sksaju), [@kirtangajjar](https://github.com/kirtangajjar) via [#221](https://github.com/10up/simple-podcasting/pull/221)).
* **Added:** Support for the WordPress.org plugin preview (props [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul) via [#265](https://github.com/10up/simple-podcasting/pull/265)).
* **Fixed:** Ensure we show all Podcasting terms in the Block Editor sidebar (props [@dkotter](https://github.com/dkotter), [@channchetra](https://github.com/channchetra), [@Sidsector9](https://github.com/Sidsector9) via [#268](https://github.com/10up/simple-podcasting/pull/268)).
* **Security:** Bump `axios` from 0.25.0 to 1.6.2 and `@wordpress/scripts` from 26.9.0 to 26.18.0 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#263](https://github.com/10up/simple-podcasting/pull/263)).
* **Security:** Bump `follow-redirects` from 1.15.3 to 1.15.4 (props [@dependabot](https://github.com/apps/dependabot), [@dkotter](https://github.com/dkotter) via [#269](https://github.com/10up/simple-podcasting/pull/269)).

= 1.6.1 - 2023-11-21 =
* **Added:** Repo Automator GitHub Action (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#253](https://github.com/10up/simple-podcasting/pull/253)).
* **Changed:** Bump WordPress "tested up to" version to 6.4 (props [@qasumitbagthariya](https://github.com/qasumitbagthariya), [@jeffpaul](https://github.com/jeffpaul) via [#259](https://github.com/10up/simple-podcasting/pull/259), [#260](https://github.com/10up/simple-podcasting/pull/260)).
* **Changed:** Ensure end-to-end tests work on Cypress v13 and bump `cypress` from 11.2.0 to 13.2.0, `@10up/cypress-wp-utils` from 0.1.0 to 0.2.0, `@wordpress/env` from 5.4.0 to 8.7.0, `cypress-localstorage-commands` from 2.2.2 to 2.2.4 and `cypress-mochawesome-reporter` from 3.4.0 to 3.6.0 (props [@iamdharmesh](https://github.com/iamdharmesh), [@Sidsector9](https://github.com/Sidsector9) via [#254](https://github.com/10up/simple-podcasting/pull/254)).
* **Security:** Bump `postcss` from 8.4.27 to 8.4.31 (props [@dependabot](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi) via [#256](https://github.com/10up/simple-podcasting/pull/256)).
* **Security:** Bump `@babel/traverse` from 7.22.8 to 7.23.2 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#257](https://github.com/10up/simple-podcasting/pull/257)).

= 1.6.0 - 2023-08-31 =
* **Added:** Ability to create a Podcast from within the Block Editor (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh) via [#232](https://github.com/10up/simple-podcasting/pull/232)).
* **Added:** New Podcast Platforms block that allows you to display icons and links to multiple podcast platforms (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#241](https://github.com/10up/simple-podcasting/pull/241)).
* **Added:** Check for minimum required PHP version before loading the plugin (props [@kmgalanakis](https://github.com/kmgalanakis), [@dkotter](https://github.com/dkotter) via [#248](https://github.com/10up/simple-podcasting/pull/248)).
* **Changed:** Rename `TAXONOMY_NAME` constant to `PODCASTING_TAXONOMY_NAME` (props [@jayedul](https://github.com/jayedul), [@peterwilsoncc](https://github.com/peterwilsoncc), [@dkotter](https://github.com/dkotter) via [#238](https://github.com/10up/simple-podcasting/pull/238)).
* **Changed:** Bump WordPress "tested up to" version to 6.3 (props [@dkotter](https://github.com/dkotter) via [#248](https://github.com/10up/simple-podcasting/pull/248)).
* **Fixed:** Resolved a PHP warning when creating a new podcast (props [@kmgalanakis](https://github.com/kmgalanakis), [@iamdharmesh](https://github.com/iamdharmesh) via [#247](https://github.com/10up/simple-podcasting/pull/247)).
* **Security:** Bump `word-wrap` from 1.2.3 to 1.2.4 (props [@dependabot](https://github.com/apps/dependabot), [@iamdharmesh](https://github.com/iamdharmesh) via [#243](https://github.com/10up/simple-podcasting/pull/243)).

= 1.5.0 - 2023-06-29 =
* **Added:** Post Grid Block to display a grid of episode posts (props [@mehul0810](https://github.com/mehul0810), [@cadic](https://github.com/cadic), [@nateconley](https://github.com/nateconley), [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul), [@ajmaurya99](https://github.com/ajmaurya99), [@nickolas-kola](https://github.com/nickolas-kola), [@achchu93](https://github.com/achchu93) via [#214](https://github.com/10up/simple-podcasting/pull/214)).
* **Added:** Mochawesome reporter added for Cypress end-to-end test report (props [@jayedul](https://github.com/jayedul), [@iamdharmesh](https://github.com/iamdharmesh) via [#236](https://github.com/10up/simple-podcasting/pull/236)).
* **Changed:** Mark any required fields when adding/editing a podcast feed (props [@mehul0810](https://github.com/mehul0810), [@cadic](https://github.com/cadic), [@nateconley](https://github.com/nateconley), [@jeffpaul](https://github.com/jeffpaul), [@Spoygg](https://github.com/Spoygg), [@ggutenberg](https://github.com/ggutenberg), [@peterwilsoncc](https://github.com/peterwilsoncc), [@Sidsector9](https://github.com/Sidsector9), [@ravinderk](https://github.com/ravinderk), [@faisal-alvi](https://github.com/faisal-alvi), [@helen](https://github.com/helen) via [#216](https://github.com/10up/simple-podcasting/pull/216)).
* **Changed:** Bumped WordPress "tested up to" version 6.2 (props [@jayedul](https://github.com/jayedul), [@peterwilsoncc](https://github.com/peterwilsoncc), [@jeffpaul](https://github.com/jeffpaul) via [#230](https://github.com/10up/simple-podcasting/pull/230)).
* **Changed:** Run end-to-end tests on the zip generated by the "Build Release ZIP" GitHub Action (props [@jayedul](https://github.com/jayedul), [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh) via [#227](https://github.com/10up/simple-podcasting/pull/227)).
* **Changed:** GitHub Action `uses` updates (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh) via [#234](https://github.com/10up/simple-podcasting/pull/234)).
* **Changed:** Updated Dependency Review GitHub Action (props [@jeffpaul](https://github.com/jeffpaul), [@Sidsector9](https://github.com/Sidsector9) via [#237](https://github.com/10up/simple-podcasting/pull/237)).
* **Removed:** Deprecated `<itunes:summary>` tag (props [@ggutenberg](https://github.com/ggutenberg), [@Sidsector9](https://github.com/Sidsector9), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#223](https://github.com/10up/simple-podcasting/pull/223)).
* **Removed:** Unnecessary term meta registration on "init" (props [@kmgalanakis](https://github.com/kmgalanakis), [@faisal-alvi](https://github.com/faisal-alvi), [@cadic](https://github.com/cadic) via [#225](https://github.com/10up/simple-podcasting/pull/225)).
* **Fixed:** Deprecation notices for `strpos` and `str_replace` on PHP >= 8.1 (props [@bmarshall511](https://github.com/bmarshall511), [@Sidsector9](https://github.com/Sidsector9), [@peterwilsoncc](https://github.com/peterwilsoncc) via [#239](https://github.com/10up/simple-podcasting/pull/239)).
* **Security:** Bump `simple-git` from 3.15.1 to 3.16.0 (props [@dependabot](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic) via [#215](https://github.com/10up/simple-podcasting/pull/215)).
* **Security:** Bump `http-cache-semantics` from 4.1.0 to 4.1.1 (props [@dependabot](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic) via [#219](https://github.com/10up/simple-podcasting/pull/219)).
* **Security:** Bump `@sideway/formula` from 3.0.0 to 3.0.1 (props [@dependabot](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic) via [#220](https://github.com/10up/simple-podcasting/pull/220)).
* **Security:** Bump `webpack` from 5.75.0 to 5.76.1 (props [@dependabot](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi) via [#222](https://github.com/10up/simple-podcasting/pull/222)).

= 1.4.0 - 2023-01-06 =
* **Added:** New podcast onboarding flow (props [@Sidsector9](https://github.com/Sidsector9), [@cadic](https://github.com/cadic), [@iamdharmesh](https://github.com/iamdharmesh), [@helen](https://github.com/helen), [@jeffpaul](https://github.com/jeffpaul), [@Nicolas-knight](https://github.com/Nicolas-knight), [@jnetek](https://github.com/jnetek) via [#193](https://github.com/10up/simple-podcasting/pull/193)).
* **Added:** Description field to RSS feed (props [@supersmo](https://github.com/supersmo), [@cadic](https://github.com/cadic) via [#204](https://github.com/10up/simple-podcasting/pull/204)).
* **Added:** Build pre-release zip GitHub Action (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter), [@faisal-alvi](https://github.com/faisal-alvi), [@vikrampm1](https://github.com/vikrampm1) via [#199](https://github.com/10up/simple-podcasting/pull/199)).
* **Changed:** Bump Wordpress "tested up to" to 6.1 (props [@jayedul](https://github.com/jayedul), [@dkotter](https://github.com/dkotter) via [#201](https://github.com/10up/simple-podcasting/pull/201)).
* **Changed:** Cypress integration migrated to 11+ (props [@jayedul](https://github.com/jayedul), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#205](https://github.com/10up/simple-podcasting/pull/205)).
* **Changed:** Updated docs to add podcast feed to Pocket Casts (props [@jeffpaul](https://github.com/jeffpaul), [@Sidsector9](https://github.com/Sidsector9), [@cadic](https://github.com/cadic) via [#192](https://github.com/10up/simple-podcasting/pull/192)).
* **Fixed:** Spotify not accepting feeds with empty `<description>` field (props [@supersmo](https://github.com/supersmo), [@cadic](https://github.com/cadic) via [#204](https://github.com/10up/simple-podcasting/pull/204)).
* **Security:** Bump `json5` from 1.0.1 to 1.0.2 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#212](https://github.com/10up/simple-podcasting/pull/212)).
* **Security:** Bump `loader-utils` from 2.0.2 to 2.0.4 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#195](https://github.com/10up/simple-podcasting/pull/195), [#198](https://github.com/10up/simple-podcasting/pull/198)).
* **Security:** Bump `simple-git` from 3.14.1 to 3.15.1 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@jeffpaul](https://github.com/jeffpaul) via [#202](https://github.com/10up/simple-podcasting/pull/202)).

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
