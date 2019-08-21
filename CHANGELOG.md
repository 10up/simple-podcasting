# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).

## [Unreleased]

## [1.1.1] - 2019-08-02
### Added
- GitHub Actions for WordPress.org plugin deploy (props @helen via #75)

### Fixed
- Compatibility with WordPress 5.2 (props @adamsilverstein via #68, #70)
- Corrected `10up/wp_mock` reference for Composer (props @oscarssanchez via #69)

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
- Initial plugin release

[Unreleased]: https://github.com/10up/simple-podcasting/compare/1.1.1...develop
[1.1.1]: https://github.com/10up/simple-podcasting/compare/f8a958c...1.1.1
[1.1.0]: https://github.com/10up/simple-podcasting/compare/1.0.1...f8a958c
[1.0.1]: https://github.com/10up/simple-podcasting/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/10up/simple-podcasting/releases/tag/1.0.0
