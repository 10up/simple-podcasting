# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).

## [Unreleased] - TBD

## [1.8.0] - 2024-04-03
### Added
- "Latest Podcast Episode" query block variation (props [#jeffpaul](https://github.com/jeffpaul), [@cadic](https://github.com/cadic), [@barneyjeffries](https://github.com/barneyjeffries), [@faisal-alvi](https://github.com/faisal-alvi) via [#266](https://github.com/10up/simple-podcasting/pull/266)).
- Ability to add Unique Cover Art for Episodes (props [@jamesburgos](https://github.com/jamesburgos), [@jeffpaul](https://github.com/jeffpaul), [@zamanq](https://github.com/zamanq), [@iamdharmesh](https://github.com/iamdharmesh) via [#273](https://github.com/10up/simple-podcasting/pull/273)).
- `simple_podcasting_feed_title` filter hook to modify feed title (props [@martinburch](https://github.com/martinburch), [@psorensen](https://github.com/psorensen), [@dkotter](https://github.com/dkotter) via [#279](https://github.com/10up/simple-podcasting/pull/279)).

### Fixed
- Incorrect feed title (props [@martinburch](https://github.com/martinburch), [@psorensen](https://github.com/psorensen), [@dkotter](https://github.com/dkotter) via [#279](https://github.com/10up/simple-podcasting/pull/279)).
- Fatal error in WordPress 5.8 and earlier (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@Sidsector9](https://github.com/Sidsector9) via [#277](https://github.com/10up/simple-podcasting/pull/277)).

### Changed
- Disabled auto sync pull requests with target branch (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#281](https://github.com/10up/simple-podcasting/pull/281)).
- Removed `PULL_REQUEST_TEMPLATE.md` template (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#286](https://github.com/10up/simple-podcasting/pull/286)).
- Replaced [lee-dohm/no-response](https://github.com/lee-dohm/no-response) with [actions/stale](https://github.com/actions/stale) to help with closing no-response/stale issues (props [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter) via [#287](https://github.com/10up/simple-podcasting/pull/287)).
- Upgrade the download-artifact from v3 to v4 (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#285](https://github.com/10up/simple-podcasting/pull/285)).

### Security
- Bumps `ip` from `1.1.8` to `1.1.9` (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#278](https://github.com/10up/simple-podcasting/pull/278)).

## [1.7.0] - 2024-01-16
### Added
- Ability to add a transcript to a podcast episode by utilizing a new Podcast Transcript block. This block is added by clicking the `Add Transcript` button that will now show in the sidebar panel of the Podcast block (props [@nateconley](https://github.com/nateconley), [@peterwilsoncc](https://github.com/peterwilsoncc), [@sksaju](https://github.com/sksaju), [@kirtangajjar](https://github.com/kirtangajjar) via [#221](https://github.com/10up/simple-podcasting/pull/221)).
- Support for the WordPress.org plugin preview (props [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul) via [#265](https://github.com/10up/simple-podcasting/pull/265)).

### Fixed
- Ensure we show all Podcasting terms in the Block Editor sidebar (props [@dkotter](https://github.com/dkotter), [@channchetra](https://github.com/channchetra), [@Sidsector9](https://github.com/Sidsector9) via [#268](https://github.com/10up/simple-podcasting/pull/268)).

### Security
- Bump `axios` from 0.25.0 to 1.6.2 and `@wordpress/scripts` from 26.9.0 to 26.18.0 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#263](https://github.com/10up/simple-podcasting/pull/263)).
- Bump `follow-redirects` from 1.15.3 to 1.15.4 (props [@dependabot](https://github.com/apps/dependabot), [@dkotter](https://github.com/dkotter) via [#269](https://github.com/10up/simple-podcasting/pull/269)).

## [1.6.1] - 2023-11-21
### Added
- Repo Automator GitHub Action (props [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#253](https://github.com/10up/simple-podcasting/pull/253)).

### Changed
- Bump WordPress "tested up to" version to 6.4 (props [@qasumitbagthariya](https://github.com/qasumitbagthariya), [@jeffpaul](https://github.com/jeffpaul) via [#259](https://github.com/10up/simple-podcasting/pull/259), [#260](https://github.com/10up/simple-podcasting/pull/260)).
- Ensure end-to-end tests work on Cypress v13 and bump `cypress` from 11.2.0 to 13.2.0, `@10up/cypress-wp-utils` from 0.1.0 to 0.2.0, `@wordpress/env` from 5.4.0 to 8.7.0, `cypress-localstorage-commands` from 2.2.2 to 2.2.4 and `cypress-mochawesome-reporter` from 3.4.0 to 3.6.0 (props [@iamdharmesh](https://github.com/iamdharmesh), [@Sidsector9](https://github.com/Sidsector9) via [#254](https://github.com/10up/simple-podcasting/pull/254)).

### Security
- Bump `postcss` from 8.4.27 to 8.4.31 (props [@dependabot](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi) via [#256](https://github.com/10up/simple-podcasting/pull/256)).
- Bump `@babel/traverse` from 7.22.8 to 7.23.2 (props [@dependabot](https://github.com/apps/dependabot), [@Sidsector9](https://github.com/Sidsector9) via [#257](https://github.com/10up/simple-podcasting/pull/257)).

## [1.6.0] - 2023-08-31
### Added
- Ability to create a Podcast from within the Block Editor (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh) via [#232](https://github.com/10up/simple-podcasting/pull/232)).
- New Podcast Platforms block that allows you to display icons and links to multiple podcast platforms (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#241](https://github.com/10up/simple-podcasting/pull/241)).
- Check for minimum required PHP version before loading the plugin (props [@kmgalanakis](https://github.com/kmgalanakis), [@dkotter](https://github.com/dkotter) via [#248](https://github.com/10up/simple-podcasting/pull/248)).

### Changed
- Rename `TAXONOMY_NAME` constant to `PODCASTING_TAXONOMY_NAME` (props [@jayedul](https://github.com/jayedul), [@peterwilsoncc](https://github.com/peterwilsoncc), [@dkotter](https://github.com/dkotter) via [#238](https://github.com/10up/simple-podcasting/pull/238)).
- Bump WordPress "tested up to" version to 6.3 (props [@dkotter](https://github.com/dkotter) via [#248](https://github.com/10up/simple-podcasting/pull/248)).

### Fixed
- Resolved a PHP warning when creating a new podcast (props [@kmgalanakis](https://github.com/kmgalanakis), [@iamdharmesh](https://github.com/iamdharmesh) via [#247](https://github.com/10up/simple-podcasting/pull/247)).

### Security
- Bump `word-wrap` from 1.2.3 to 1.2.4 (props [@dependabot](https://github.com/apps/dependabot), [@iamdharmesh](https://github.com/iamdharmesh) via [#243](https://github.com/10up/simple-podcasting/pull/243)).

## [1.5.0] - 2023-06-29
### Added
- Post Grid Block to display a grid of episode posts (props [@mehul0810](https://github.com/mehul0810), [@cadic](https://github.com/cadic), [@nateconley](https://github.com/nateconley), [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul), [@ajmaurya99](https://github.com/ajmaurya99), [@nickolas-kola](https://github.com/nickolas-kola), [@achchu93](https://github.com/achchu93) via [#214](https://github.com/10up/simple-podcasting/pull/214)).
- Mochawesome reporter added for Cypress end-to-end test report (props [@jayedul](https://github.com/jayedul), [@iamdharmesh](https://github.com/iamdharmesh) via [#236](https://github.com/10up/simple-podcasting/pull/236)).

### Changed
- Mark any required fields when adding/editing a podcast feed (props [@mehul0810](https://github.com/mehul0810), [@cadic](https://github.com/cadic), [@nateconley](https://github.com/nateconley), [@jeffpaul](https://github.com/jeffpaul), [@Spoygg](https://github.com/Spoygg), [@ggutenberg](https://github.com/ggutenberg), [@peterwilsoncc](https://github.com/peterwilsoncc), [@Sidsector9](https://github.com/Sidsector9), [@ravinderk](https://github.com/ravinderk), [@faisal-alvi](https://github.com/faisal-alvi), [@helen](https://github.com/helen) via [#216](https://github.com/10up/simple-podcasting/pull/216)).
- Bumped WordPress "tested up to" version 6.2 (props [@jayedul](https://github.com/jayedul), [@peterwilsoncc](https://github.com/peterwilsoncc), [@jeffpaul](https://github.com/jeffpaul) via [#230](https://github.com/10up/simple-podcasting/pull/230)).
- Run end-to-end tests on the zip generated by the "Build Release ZIP" GitHub Action (props [@jayedul](https://github.com/jayedul), [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh) via [#227](https://github.com/10up/simple-podcasting/pull/227)).
- GitHub Action `uses` updates (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh) via [#234](https://github.com/10up/simple-podcasting/pull/234)).
- Updated Dependency Review GitHub Action (props [@jeffpaul](https://github.com/jeffpaul), [@Sidsector9](https://github.com/Sidsector9) via [#237](https://github.com/10up/simple-podcasting/pull/237)).

### Removed
- Deprecated `<itunes:summary>` tag (props [@ggutenberg](https://github.com/ggutenberg), [@Sidsector9](https://github.com/Sidsector9), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#223](https://github.com/10up/simple-podcasting/pull/223)).
- Unnecessary term meta registration on "init" (props [@kmgalanakis](https://github.com/kmgalanakis), [@faisal-alvi](https://github.com/faisal-alvi), [@cadic](https://github.com/cadic) via [#225](https://github.com/10up/simple-podcasting/pull/225)).

### Fixed
- Deprecation notices for `strpos` and `str_replace` on PHP >= 8.1 (props [@bmarshall511](https://github.com/bmarshall511), [@Sidsector9](https://github.com/Sidsector9), [@peterwilsoncc](https://github.com/peterwilsoncc) via [#239](https://github.com/10up/simple-podcasting/pull/239)).

### Security
- Bump `simple-git` from 3.15.1 to 3.16.0 (props [@dependabot](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic) via [#215](https://github.com/10up/simple-podcasting/pull/215)).
- Bump `http-cache-semantics` from 4.1.0 to 4.1.1 (props [@dependabot](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic) via [#219](https://github.com/10up/simple-podcasting/pull/219)).
- Bump `@sideway/formula` from 3.0.0 to 3.0.1 (props [@dependabot](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic) via [#220](https://github.com/10up/simple-podcasting/pull/220)).
- Bump `webpack` from 5.75.0 to 5.76.1 (props [@dependabot](https://github.com/apps/dependabot), [@faisal-alvi](https://github.com/faisal-alvi) via [#222](https://github.com/10up/simple-podcasting/pull/222)).

## [1.4.0] - 2023-01-23
### Added
- New podcast onboarding flow (props [@Sidsector9](https://github.com/Sidsector9), [@cadic](https://github.com/cadic), [@iamdharmesh](https://github.com/iamdharmesh), [@helen](https://github.com/helen), [@jeffpaul](https://github.com/jeffpaul), [@Nicolas-knight](https://github.com/Nicolas-knight), [@jnetek](https://github.com/jnetek) via [#193](https://github.com/10up/simple-podcasting/pull/193)).
- Description field to RSS feed (props [@supersmo](https://github.com/supersmo), [@cadic](https://github.com/cadic) via [#204](https://github.com/10up/simple-podcasting/pull/204)).
- Build pre-release zip GitHub Action (props [@Sidsector9](https://github.com/Sidsector9), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter), [@faisal-alvi](https://github.com/faisal-alvi), [@vikrampm1](https://github.com/vikrampm1) via [#199](https://github.com/10up/simple-podcasting/pull/199)).

### Changed
- Bump Wordpress "tested up to" to 6.1 (props [@jayedul](https://github.com/jayedul), [@dkotter](https://github.com/dkotter) via [#201](https://github.com/10up/simple-podcasting/pull/201)).
- Cypress integration migrated to 11+ (props [@jayedul](https://github.com/jayedul), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#205](https://github.com/10up/simple-podcasting/pull/205)).
- Updated docs to add podcast feed to Pocket Casts (props [@jeffpaul](https://github.com/jeffpaul), [@Sidsector9](https://github.com/Sidsector9), [@cadic](https://github.com/cadic) via [#192](https://github.com/10up/simple-podcasting/pull/192)).

### Fixed
- Spotify not accepting feeds with empty `<description>` field (props [@supersmo](https://github.com/supersmo), [@cadic](https://github.com/cadic) via [#204](https://github.com/10up/simple-podcasting/pull/204)).

### Security
- Bump `json5` from 1.0.1 to 1.0.2 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#212](https://github.com/10up/simple-podcasting/pull/212)).
- Bump `loader-utils` from 2.0.2 to 2.0.4 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#195](https://github.com/10up/simple-podcasting/pull/195), [#198](https://github.com/10up/simple-podcasting/pull/198)).
- Bump `simple-git` from 3.14.1 to 3.15.1 (props [@dependabot[bot]](https://github.com/apps/dependabot), [@jeffpaul](https://github.com/jeffpaul) via [#202](https://github.com/10up/simple-podcasting/pull/202)).

## [1.3.0] - 2022-10-18
**Note that this version bumps the minimum PHP version from 7.0 to 7.4 and the minimum WordPress version from 4.6 to 5.7.**

### Added
- Podcasts Taxonomy term(s) added in block settings (props [@helen](https://github.com/helen), [@jeffpaul](https://github.com/jeffpaul), [@faisal-alvi](https://github.com/faisal-alvi), [@peterwilsoncc](https://github.com/peterwilsoncc), [@cadic](https://github.com/cadic) via [#183](https://github.com/10up/simple-podcasting/pull/183)).
- Type of show setting for the podcast (props [@cadic](https://github.com/cadic), [@faisal-alvi](https://github.com/faisal-alvi), [@jeffpaul](https://github.com/jeffpaul) via [#188](https://github.com/10up/simple-podcasting/pull/188)).

### Changed
- Podcasting Categories and Sub-Categories (props [@zamanq](https://github.com/zamanq), [@jeffpaul](https://github.com/jeffpaul), [@dkotter](https://github.com/dkotter), [@cadic](https://github.com/cadic), [@dchucks](https://github.com/dchucks) via [#179](https://github.com/10up/simple-podcasting/pull/179)).
- Bumped minimum PHP version required from 7.0 to 7.4 (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul), [@vikrampm1](https://github.com/vikrampm1) via [#184](https://github.com/10up/simple-podcasting/pull/184)).
- Bumped minimum WordPress version required from 4.6 to 5.7 (props [@peterwilsoncc](https://github.com/peterwilsoncc), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul), [@vikrampm1](https://github.com/vikrampm1) via [#184](https://github.com/10up/simple-podcasting/pull/184)).
- Upgrade dependencies (props [@cadic](https://github.com/cadic), [@faisal-alvi](https://github.com/faisal-alvi) via [#187](https://github.com/10up/simple-podcasting/pull/187)).

### Fixed
- Saving podcast enclosure with Classic Editor (props [@cadic](https://github.com/cadic), [@faisal-alvi](https://github.com/faisal-alvi) via [#186](https://github.com/10up/simple-podcasting/pull/186)).

### Security
- Bump `got` from 10.7.0 to 11.8.5 (props [@faisal-alvi](https://github.com/faisal-alvi), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#185](https://github.com/10up/simple-podcasting/pull/185)).
- Bump `@wordpress/env` from 4.5.0 to 5.2.0 (props [@faisal-alvi](https://github.com/faisal-alvi), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#185](https://github.com/10up/simple-podcasting/pull/185)).

## [1.2.4] - 2022-07-27
### Added
- Season number, episode number and episode type attributes can now be stored with a Podcast (props [@zamanq](https://github.com/zamanq), [@dchucks](https://github.com/dchucks), [@cadic](https://github.com/cadic) via [#175](https://github.com/10up/simple-podcasting/pull/175)).

### Changed
- Bump WordPress version "tested up to" 6.0 (props [@cadic](https://github.com/cadic) via [#171](https://github.com/10up/simple-podcasting/issues/171)).

### Fixed
- Incorrect Language value in the Feed (props [@zamanq](https://github.com/zamanq), [@dchucks](https://github.com/dchucks), [@cadic](https://github.com/cadic) via [#176](https://github.com/10up/simple-podcasting/pull/176)).

### Security
- Bump `terser` from 5.12.1 to 5.14.2 (props [@dependabot](https://github.com/apps/dependabot) via [#180](https://github.com/10up/simple-podcasting/pull/180)).

## [1.2.3] - 2022-04-28
### Added
- Compatibility tests against PHP 7 and 8 (props [@cadic](https://github.com/cadic), [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul) via [#150](https://github.com/10up/simple-podcasting/pull/150)).
- Default Pull Request Reviewers via CODEOWNERS file (props [@jeffpaul](https://github.com/jeffpaul), [@cadic](https://github.com/cadic) via [#156](https://github.com/10up/simple-podcasting/pull/156)).
- Dependency security scanning (props [@jeffpaul](https://github.com/jeffpaul) via [#168](https://github.com/10up/simple-podcasting/pull/168)).

### Changed
- Unit tests against PHP 8 (props [@cadic](https://github.com/cadic), [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul) via [#150](https://github.com/10up/simple-podcasting/pull/150)).
- Bump required PHP 7.0 (props [@cadic](https://github.com/cadic), [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul) via [#150](https://github.com/10up/simple-podcasting/pull/150)).
- Replaced custom commands with @10up/cypress-wp-utils in end-to-end tests (props [@dinhtungdu](https://github.com/dinhtungdu), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#162](https://github.com/10up/simple-podcasting/pull/162)).

### Fixed
- Missing `<enclosure>` in feed item (props [@davexpression](https://github.com/davexpression), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#147](https://github.com/10up/simple-podcasting/pull/147)).
- Failing Cypress test on WP Minimum (props [@dinhtungdu](https://github.com/dinhtungdu), [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#164](https://github.com/10up/simple-podcasting/pull/164)).
- Updated badges in readme (props [@cadic](https://github.com/cadic), [@jeffpaul](https://github.com/jeffpaul) via [#167](https://github.com/10up/simple-podcasting/pull/167)).

### Security
- Upgraded node dependencies (props [@cadic](https://github.com/cadic), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#158](https://github.com/10up/simple-podcasting/pull/158) and [#163](https://github.com/10up/simple-podcasting/pull/163)).
- Bump async from 2.6.3 to 2.6.4 (props [@dependabot](https://github.com/apps/dependabot) via [#166](https://github.com/10up/simple-podcasting/pull/166)).
- Bump node-forge from 1.2.1 to 1.3.0 (props [@dependabot](https://github.com/apps/dependabot) via [#160](https://github.com/10up/simple-podcasting/pull/160)).
- Bump minimist from 1.2.5 to 1.2.6 (props [@dependabot](https://github.com/apps/dependabot) via [#159](https://github.com/10up/simple-podcasting/pull/159)).

## [1.2.2] - 2022-03-01
### Added
- Filter `simple_podcasting_feed_item` to modify RSS feed item data before output (props [@cadic](https://github.com/cadic), [@iamdharmesh](https://github.com/iamdharmesh), [@jeffpaul](https://github.com/jeffpaul) via [#144](https://github.com/10up/simple-podcasting/pull/144)).
- Unit tests (props [@cadic](https://github.com/cadic) via [#142](https://github.com/10up/simple-podcasting/pull/142), [@dkotter](https://github.com/dkotter), [@jeffpaul](https://github.com/jeffpaul)).
- GitHub action job to run PHPCS (props [@cadic](https://github.com/cadic), [@dkotter](https://github.com/dkotter) via [#136](https://github.com/10up/simple-podcasting/pull/136)).
- Auto-create pot file in languages folder during the build process (props [@dkotter](https://github.com/dkotter), [@cadic](https://github.com/cadic) via [#131](https://github.com/10up/simple-podcasting/pull/131)).

### Changed
- Bump WordPress "tested up to" version 5.9 (props [@sudip-10up](https://github.com/sudip-10up), [@cadic](https://github.com/cadic), [@peterwilsoncc](https://github.com/peterwilsoncc) via [#140](https://github.com/10up/simple-podcasting/pull/140)).

### Fixed
- End-to-end tests with WordPress 5.9 element IDs (props[@cadic](https://github.com/cadic), [@felipeelia](https://github.com/felipeelia), [@dinhtungdu](https://github.com/dinhtungdu) via [#146](https://github.com/10up/simple-podcasting/pull/146)).
- Podcast feed link output on Edit Podcast screen (props [@mehidi258](https://github.com/mehidi258), [@jeffpaul](https://github.com/jeffpaul), [@cadic](https://github.com/cadic) via [#139](https://github.com/10up/simple-podcasting/pull/139)).
- Bug fix for `is_feed` being called too early (props [@tomjn](https://github.com/tomjn), [@jeffpaul](https://github.com/jeffpaul) via [#135](https://github.com/10up/simple-podcasting/pull/135)).
- Missing and incorrect text-domain (props [@dkotter](https://github.com/dkotter), [@cadic](https://github.com/cadic) via [#131](https://github.com/10up/simple-podcasting/pull/131)).

### Security
- Bump `nanoid` from 3.1.25 to 3.2.0 (props [@dependabot](https://github.com/apps/dependabot) via [#143](https://github.com/10up/simple-podcasting/pull/143)).

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
[1.8.0]: https://github.com/10up/simple-podcasting/compare/1.7.0...1.8.0
[1.7.0]: https://github.com/10up/simple-podcasting/compare/1.6.1...1.7.0
[1.6.1]: https://github.com/10up/simple-podcasting/compare/1.6.0...1.6.1
[1.6.0]: https://github.com/10up/simple-podcasting/compare/1.5.0...1.6.0
[1.5.0]: https://github.com/10up/simple-podcasting/compare/1.4.0...1.5.0
[1.4.0]: https://github.com/10up/simple-podcasting/compare/1.3.0...1.4.0
[1.3.0]: https://github.com/10up/simple-podcasting/compare/1.2.4...1.3.0
[1.2.4]: https://github.com/10up/simple-podcasting/compare/1.2.3-deploy...1.2.4
[1.2.3]: https://github.com/10up/simple-podcasting/compare/1.2.2...1.2.3-deploy
[1.2.2]: https://github.com/10up/simple-podcasting/compare/1.2.1...1.2.2
[1.2.1]: https://github.com/10up/simple-podcasting/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/10up/simple-podcasting/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/10up/simple-podcasting/compare/f8a958c...1.1.1
[1.1.0]: https://github.com/10up/simple-podcasting/compare/1.0.1...f8a958c
[1.0.1]: https://github.com/10up/simple-podcasting/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/10up/simple-podcasting/releases/tag/1.0.0
