=== Simple Podcasting ===
Contributors:      10up, helen, adamsilverstein, jakemgold, jeffpaul, cadic
Tags:              podcasting, podcast, apple podcasts, episode, season
Requires PHP:      7.4
Requires at least: 6.8
Tested up to:      7.0
Stable tag:        2.0.0
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block and podcast transcript block for the WordPress block editor.

== Description ==

Set up multiple podcast feeds using built-in WordPress posts. Includes a podcast block and podcast transcript block for the WordPress block editor (aka Gutenberg).

Podcasting is a method to distribute audio and video episodes through a feed to which listeners can subscribe. You can publish podcasts on your WordPress site and make them available for listeners in Apple Podcasts and through direct feed links for other podcasting apps by following these steps:

= Technical Notes =

* Requires PHP 7.4+.
* RSS feeds must not be disabled.

== Installation ==

1. Install the plugin via the plugin installer, either by searching for it or uploading a .zip file.
2. Activate the plugin.
3. Head to Posts → Podcasts and add at least one podcast.
4. Create a post and insert an audio embed (or a podcast block in Gutenberg) and select a Podcast feed to include it in.

== Usage ==

= Create your podcast =

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

= Add content to your podcast =

 * Create a new post and assign it to one or more Podcasts using the panel labeled Podcasts.
 * Upload or embed an audio file into this post using any of the usual WordPress methods. If using the new block-based WordPress editor (sometimes referred to as Gutenberg), insert a Podcast block. Only one Podcast block can be inserted per post.
 * For more advanced settings, use the Podcasting meta box to mark explicit content or closed captioning available, season number, episode number, episode type, add a transcript and to optionally specify one media item in the post if you have more than one in your post. In the block-based editor, these are the block settings that appear in the sidebar when the podcast block is selected.
 * Transcript: If desired, an optional transcript can be added from the settings of the Podcast block. This will add a Podcast Transcript block, allowing you to add a transcript consisting of time codes, citations, and paragrah text that can be embedded in the post, linked to an external plain HTML file, or linked in a special `<podcast:transcript>` XML element.

= Submit your podcast feed to Apple Podcasts =

* Each podcast has a unique feed URL you can find on the Podcasts page. This is the URL you will submit to Apple.
* Ensure you test feeds before submitting them, see [Apple's "Test a Podcast page"](https://help.apple.com/itc/podcasts_connect/#/itcac471c970) for more information.
* Once the validator passes, submit your podcast. Podcasts submitted to Apple Podcasts do not become immediately available for subscription by others. They are submitted for review by Apple staff, see [Apple's "Submit a podcast" page](https://help.apple.com/itc/podcasts_connect/#/itcd88ea40b9) for more information.

= Submit your podcast feed to Pocket Casts =

* Validate your feeds at [https://www.castfeedvalidator.com/ Cast Feed Validator] before submitting them.
* Submit the podcast feed to https://pocketcasts.com/submit/.

= Control how many episodes are listed on the feed =

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

= Customize RSS feed =

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

== Frequently Asked Questions ==

= How do I get my podcast featured on Pocket Casts? =

The Featured section of Pocket Casts is human-curated. To ensure that all podcasts have an equal opportunity at being featured, selections are made on the basis of merit.

If you’d like to suggest your podcast for a featured spot, reach out to `curation@pocketcasts.com`

For more information, [https://pocketcasts.com/podcast-producers/ read more].

= How do I submit private and paid podcast feeds? =

Follow this documentation to submit [https://support.pocketcasts.com/article/password-protected-podcasts-2/ private and paid podcast feeds]

= Where do I report security bugs found in this plugin? =

Please report security bugs found in the source code of the Simple Podcasting plugin through the [Patchstack Vulnerability Disclosure  Program](https://patchstack.com/database/vdp/0d49ba54-688e-484d-9411-4716696aa79b).  The Patchstack team will assist you with verification, CVE assignment, and notify the developers of this plugin.

== Screenshots ==

1. Podcast in block editor
2. Podcast Platforms block in the block editor
3. Creating a podcast
4. Podcast feed
5. Podcast Grid pattern
6. Podcast Transcript block

== Changelog ==

= 2.0.0 - 2026-06-25 =
**Note that this release bumps the WordPress minimum version from 6.6 to 6.8.**

* **Added:** Ability to dock podcast player to top or bottom of browser window. (props [@wadebekker](https://github.com/wadebekker), [@sanketio](https://github.com/sanketio), [@peterwilsoncc](https://github.com/peterwilsoncc), [@github-actions[bot]](https://github.com/apps/github-actions), [@jeffpaul](https://github.com/jeffpaul) via [#331](https://github.com/10up/simple-podcasting/pull/331)).
* **Changed:** Bump tested up to header to indicate WordPress 7.0 support. (props [@phpbits](https://github.com/phpbits), [@jeffpaul](https://github.com/jeffpaul), [@peterwilsoncc](https://github.com/peterwilsoncc), [@dkotter](https://github.com/dkotter) via [#349](https://github.com/10up/simple-podcasting/pull/349), [#360](https://github.com/10up/simple-podcasting/pull/360)).
* **Changed:** Update NPM dependencies via npm audit fix. (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@dkotter](https://github.com/dkotter) via [#351](https://github.com/10up/simple-podcasting/pull/351)).

= 1.9.1 - 2025-05-16 =
* **Added:** Screenshots for all new features (props [@gabriel-glo](https://github.com/gabriel-glo), [@jeffpaul](https://github.com/jeffpaul), [@Sidsector9](https://github.com/Sidsector9), [@dkotter](https://github.com/dkotter) via [#310](https://github.com/10up/simple-podcasting/pull/310)).
* **Changed:** Bump WordPress "tested up to" version to 6.8 (props [@jeffpaul](https://github.com/jeffpaul) via [#335](https://github.com/10up/simple-podcasting/pull/335), [#336](https://github.com/10up/simple-podcasting/pull/336)).
* **Changed:** Bump WordPress minimum from 6.5 to 6.6 (props [@jeffpaul](https://github.com/jeffpaul) via [#335](https://github.com/10up/simple-podcasting/pull/335), [#336](https://github.com/10up/simple-podcasting/pull/336)).
* **Fixed:** Issue where podcast feed title unexpectedly adding site title (props [@kirtangajjar](https://github.com/kirtangajjar), [@peterwilsoncc](https://github.com/peterwilsoncc), [@dabowman](https://github.com/dabowman) via [#295](https://github.com/10up/simple-podcasting/pull/295)).
* **Security:** Bump `@wordpress/scripts` from 27.9.0 to 30.6.0 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#328](https://github.com/10up/simple-podcasting/pull/328)).
* **Security:** Bump `cookie` from 0.4.2 to 0.7.1, `express` from 4.21.0 to 4.21.2, `@wordpress/e2e-test-utils-playwright` from 0.26.0 to 1.18.0, `serialize-javascript` from 6.0.0 to 6.0.2 and `mocha` from 10.4.0 to 11.1.0 (props [@dependabot](https://github.com/apps/dependabot), [@peterwilsoncc](https://github.com/peterwilsoncc) via [#332](https://github.com/10up/simple-podcasting/pull/332)).
* **Security:** Bump `axios` from 1.7.4 to 1.9.0 and `http-proxy-middleware` from 2.0.6 to 2.0.9 (props [@dependabot](https://github.com/apps/dependabot), [@peterwilsoncc](https://github.com/peterwilsoncc) via [#338](https://github.com/10up/simple-podcasting/pull/338)).

= 1.9.0 - 2024-11-18 =
* **Added:** New options to the Podcast block to allow for more display customization (props [@barneyjeffries](https://github.com/barneyjeffries), [@Firestorm980](https://github.com/Firestorm980), [@mehidi258](https://github.com/mehidi258), [@jayedul](https://github.com/jayedul), [@Sidsector9](https://github.com/Sidsector9), [@peterwilsoncc](https://github.com/peterwilsoncc), [@faisal-alvi](https://github.com/faisal-alvi), [@gusaus](https://github.com/gusaus), [@jeffpaul](https://github.com/jeffpaul) via [#272](https://github.com/10up/simple-podcasting/pull/272)).
* **Changed:** Update the rendering of the Podcast block to be more full featured and use all the newly added customization options (props [@barneyjeffries](https://github.com/barneyjeffries), [@Firestorm980](https://github.com/Firestorm980), [@mehidi258](https://github.com/mehidi258), [@jayedul](https://github.com/jayedul), [@Sidsector9](https://github.com/Sidsector9), [@peterwilsoncc](https://github.com/peterwilsoncc), [@faisal-alvi](https://github.com/faisal-alvi), [@gusaus](https://github.com/gusaus), [@sudar](https://github.com/sudar), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter) via [#272](https://github.com/10up/simple-podcasting/pull/272), [#318](https://github.com/10up/simple-podcasting/pull/318), [#320](https://github.com/10up/simple-podcasting/pull/320), [#322](https://github.com/10up/simple-podcasting/pull/322)).
* **Changed:** Bump WordPress "tested up to" version to 6.7 (props [@qasumitbagthariya](https://github.com/qasumitbagthariya), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter), [@sonali886](https://github.com/sonali886), [@godleman](https://github.com/godleman), [@mehul0810](https://github.com/mehul0810) via [#291](https://github.com/10up/simple-podcasting/pull/291), [#307](https://github.com/10up/simple-podcasting/pull/307), [#325](https://github.com/10up/simple-podcasting/pull/325), [#326](https://github.com/10up/simple-podcasting/pull/326)).
* **Changed:** Bump WordPress minimum from 5.7 to 6.5 (props [@qasumitbagthariya](https://github.com/qasumitbagthariya), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter), [@sonali886](https://github.com/sonali886), [@godleman](https://github.com/godleman), [@mehul0810](https://github.com/mehul0810) via [#291](https://github.com/10up/simple-podcasting/pull/291), [#307](https://github.com/10up/simple-podcasting/pull/307), [#325](https://github.com/10up/simple-podcasting/pull/325), [#326](https://github.com/10up/simple-podcasting/pull/326)).
* **Changed:** Update how we import the `PluginDocumentSettingPanel` component to use the new `@wordpress/editor` package if it exists (props [@gabriel-glo](https://github.com/gabriel-glo), [@dkotter](https://github.com/dkotter) via [#309](https://github.com/10up/simple-podcasting/pull/309)).
* **Security:** Bump `braces` from 3.0.2 to 3.0.3, `pac-resolver` from 7.0.0 to 7.0.1, `socks` from 2.7.1 to 2.8.3, `ws` from 7.5.9 to 7.5.10 and removes `ip` (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#297](https://github.com/10up/simple-podcasting/pull/297), [#306](https://github.com/10up/simple-podcasting/pull/306)).
* **Security:** Bump `axios` from 1.7.2 to 1.7.4 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#312](https://github.com/10up/simple-podcasting/pull/312)).
* **Security:** Bump `express` from 4.18.2 to 4.19.2, `follow-redirects` from 1.15.4 to 1.15.6, and `webpack-dev-middleware` from 5.3.3 to 5.3.4 (props [@dependabot](https://github.com/apps/dependabot), [@iamdharmesh](https://github.com/iamdharmesh) via [#290](https://github.com/10up/simple-podcasting/pull/290)).
* **Security:** Bump `webpack` from 5.91.0 to 5.94.0 (props [@dependabot](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi) via [#315](https://github.com/10up/simple-podcasting/pull/315)).
* **Security:** Bump `ws` from 7.5.10 to 8.18.0, `serve-static` from 1.15.0 to 1.16.2 and `express` from 4.19.2 to 4.21.0 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#319](https://github.com/10up/simple-podcasting/pull/319)).

= 1.8.0 - 2024-04-03 =
* **Added:** "Latest Podcast Episode" query block variation (props [@jeffpaul](https://github.com/jeffpaul), [@cadic](https://github.com/cadic), [@barneyjeffries](https://github.com/barneyjeffries), [@faisal-alvi](https://github.com/faisal-alvi) via [#266](https://github.com/10up/simple-podcasting/pull/266)).
* **Added:** Ability to add Unique Cover Art for Episodes (props [@jamesburgos](https://github.com/jamesburgos), [@jeffpaul](https://github.com/jeffpaul), [@zamanq](https://github.com/zamanq), [@iamdharmesh](https://github.com/iamdharmesh) via [#273](https://github.com/10up/simple-podcasting/pull/273)).
* **Added:** `simple_podcasting_feed_title` filter hook to modify feed title (props [@martinburch](https://github.com/martinburch), [@psorensen](https://github.com/psorensen), [@dkotter](https://github.com/dkotter) via [#279](https://github.com/10up/simple-podcasting/pull/279)).
* **Fixed:** Incorrect feed title (props [@martinburch](https://github.com/martinburch), [@psorensen](https://github.com/psorensen), [@dkotter](https://github.com/dkotter) via [#279](https://github.com/10up/simple-podcasting/pull/279)).
* **Fixed:** Fatal error in WordPress 5.8 and earlier (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@Sidsector9](https://github.com/Sidsector9) via [#277](https://github.com/10up/simple-podcasting/pull/277)).
* **Changed:** Disabled auto sync pull requests with target branch (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#281](https://github.com/10up/simple-podcasting/pull/281)).
* **Changed:** Removed `PULL_REQUEST_TEMPLATE.md` template (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#286](https://github.com/10up/simple-podcasting/pull/286)).
* **Changed:** Replaced [lee-dohm/no-response](https://github.com/lee-dohm/no-response) with [actions/stale](https://github.com/actions/stale) to help with closing no-response/stale issues (props [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter) via [#287](https://github.com/10up/simple-podcasting/pull/287)).
* **Changed:** Upgrade the download-artifact from v3 to v4 (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#285](https://github.com/10up/simple-podcasting/pull/285)).
* **Security:** Bumps `ip` from `1.1.8` to `1.1.9` (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#278](https://github.com/10up/simple-podcasting/pull/278)).

[View historical changelog details here](https://github.com/10up/simple-podcasting/blob/develop/CHANGELOG.md).

== Upgrade Notice ==

= 1.9.1 =
This release bumps the minimum required version of WordPress from 6.5 to 6.6.

= 1.9.0 =
This release bumps the minimum required version of WordPress from 5.7 to 6.5.

= 1.3.0 =
Note that this version bumps the minimum PHP version from 7.0 to 7.4 and the minimum WordPress version from 4.6 to 5.7.
