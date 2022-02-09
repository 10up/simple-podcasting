# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).

## [Unreleased]
- Filter `simple_podcasting_feed_item` to modify RSS feed item data before output (pros [@cadic](https://github.com/cadic)).

## [1.2.1] - 2021-12-16
### Added
- Filter `simple_podcasting_episodes_per_page` to override default of 250 episodes per podcast feed (props [@pabamato](https://github.com/pabamato), [@dinhtungdu](https://github.com/dinhtungdu), [@monomo111](https://github.com/monomo111), [@jeffpaul](https://github.com/jeffpaul), [@jakemgold](https://github.com/jakemgold) via [#109](https://github.com/10up/simple-podcasting/pull/109)).
- End-to-end testing using Cypress and `wp-env` (props [@dinhtungdu](https://github.com/dinhtungdu), [@markjaquith](https://github.com/markjaquith), [@youknowriad](https://github.com/youknowriad), [@helen](https://github.com/helen) via [#115](https://github.com/10up/simple-podcasting/pull/115), [#117](https://github.com/10up/simple-podcasting/pull/117)).
- Issue management automation via GitHub Actions (props [@jeffpaul](https://github.com/jeffpaul) via [#119](https://github.com/10up/simple-podcasting/pull/119)).
- Pull request template (props [@jeffpaul](https://github.com/jeffpaul), [@dinhtungdu](https://github.com/dinhtungdu) via [#125](https://github.com/10up/simple-podcasting/pull/125)).

### Changed
- Default number of episodes in RSS feeds increased from 10 to 250 (props [@pabamato](https://github.com/pabamato), [@dinhtungdu](https://github.com/dinhtungdu), [@monomo111](https://github.com/monomo111), [@jeffpaul](https://github.com/jeffpaul), [@jakemgold](https://github.com/jakemgold) via [#109](https://github.com/10up/simple-podcasting/pull/109)).
- Use `@wordpress/scripts` as the build tool (props [@dinhtungdu](https://github.com/dinhtungdu) via [#114](https://github.com/10up/simple-podcasting/pull/114)).
- Bump WordPress version “tested up to” 5.8.1 (props [David Chabbi](https://www.linkedin.com/in/david-chabbi-985719b4/), [@jeffpaul](https://github.com/jeffpaul), [@pabamato](https://github.com/pabamato) via  [#106](https://github.com/10up/simple-podcasting/pull/106), [#110](https://github.com/10up/simple-podcasting/pull/110), [#124](https://github.com/10up/simple-podcasting/pull/124)).
- Documentation updates (props [@meszarosrob](https://github.com/meszarosrob), [@dinhtungdu](https://github.com/dinhtungdu) via [#101](https://github.com/10up/simple-podcasting/pull/101)).

### Fixed
- 'podcast' block core dependency  (props [@pabamato](https://github.com/pabamato), [@dinhtungdu](https://github.com/dinhtungdu), [@monomo111](https://github.com/monomo111), [@jeffpaul](https://github.com/jeffpaul), [@jakemgold](https://github.com/jakemgold) via [#109](https://github.com/10up/simple-podcasting/pull/109)).
- Minimum WordPress version used by `wp-env` (props [@dinhtungdu](https://github.com/dinhtungdu) via [#122](https://github.com/10up/simple-podcasting/pull/122)).

## [1.2.0] - 2020-07-10
### Added
- Podcast image in the taxonomy list table view (props [@Firestorm980](https://github.com/Firestorm980), [@helen](https://github.com/helen) via [#87](https://github.com/10up/simple-podcasting/pull/87)).
- Ability for user to transform to/from the podcast and audio blocks (props [@Firestorm980](https://github.com/Firestorm980), [@helen](https://github.com/helen) via [#85](https://github.com/10up/simple-podcasting/pull/85)).
- Core `MediaReplaceFlow` to edit the podcast media (props [@Firestorm980](https://github.com/Firestorm980), [@helen](https://github.com/helen) via [#86](https://github.com/10up/simple-podcasting/pull/86)).

### Changed
- GitHub Actions from HCL to YAML workflow syntax (props [@helen](https://github.com/helen) via [#78](https://github.com/10up/simple-podcasting/pull/78)).
- Stop committing built files (props [@helen](https://github.com/helen) via [#95](https://github.com/10up/simple-podcasting/pull/95)).
- Documentation updates (props [@jeffpaul](https://github.com/jeffpaul), [@nhalstead](https://github.com/nhalstead) via [#76](https://github.com/10up/simple-podcasting/pull/76), [#79](https://github.com/10up/simple-podcasting/pull/79)).

### Fixed
- Using the upload or drag and drop instead of media library populates duration and mimetype (props [@Firestorm980](https://github.com/Firestorm980), [@helen](https://github.com/helen) via [#82](https://github.com/10up/simple-podcasting/pull/82)).
- Issue where it is possible to add non-audio files to the Podcast block (props [@mattheu](https://github.com/mattheu) via [#77](https://github.com/10up/simple-podcasting/pull/77)).
- Issue where React would throw an error relating to keys for list items (props [@Firestorm980](https://github.com/Firestorm980), [@helen](https://github.com/helen) via [#85](https://github.com/10up/simple-podcasting/pull/85)).
- Ensure podcast-related meta is deleted after block is removed. (props [@dinhtungdu](https://github.com/dinhtungdu) via [#96](https://github.com/10up/simple-podcasting/pull/96)).

## [1.1.1] - 2019-08-02
### Added
- GitHub Actions for WordPress.org plugin deploy (props [@helen](https://github.com/helen) via [#75](https://github.com/10up/simple-podcasting/pull/75)).

### Fixed
- Compatibility with WordPress 5.2 (props [@adamsilverstein](https://github.com/adamsilverstein) via [#68](https://github.com/10up/simple-podcasting/pull/68), [#70](https://github.com/10up/simple-podcasting/pull/70)).
- Corrected `10up/wp_mock` reference for Composer (props [@oscarssanchez](https://github.com/oscarssanchez) via [#69](https://github.com/10up/simple-podcasting/pull/69)).

## [1.1.0] - 2018-12-04
### Added
- Retrieve metadata for externally hosted audio files in the block editor.
- Specify email address for a given podcast.
- Set language for a given podcast.
- Developers: Add linting for coding standards.

### Changed
- Clearer language on the add new podcast form.

### Fixed
- Delete all associated meta when block is removed from a post.
- Restore all block editor functionality to align with Gutenberg/block changes.
- Fully clear add new form after creating a new podcast.

## [1.0.1] - 2018-07-02
### Fixed
- Properly output podcast categories and subcategories in the feed.
- Avoid a minified JS error when selecting a podcast image.
- Display podcast summary on edit form.

## [1.0.0] - 2018-06-29
- Initial plugin release.

[Unreleased]: https://github.com/10up/simple-podcasting/compare/trunk...develop
[1.2.1]: https://github.com/10up/simple-podcasting/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/10up/simple-podcasting/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/10up/simple-podcasting/compare/f8a958c...1.1.1
[1.1.0]: https://github.com/10up/simple-podcasting/compare/1.0.1...f8a958c
[1.0.1]: https://github.com/10up/simple-podcasting/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/10up/simple-podcasting/releases/tag/1.0.0
