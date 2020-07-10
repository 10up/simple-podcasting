# Contributing and Maintaining

First, thank you for taking the time to contribute!

The following is a set of guidelines for contributors as well as information and instructions around our maintenance process. The two are closely tied together in terms of how we all work together and set expectations, so while you may not need to know everything in here to submit an issue or pull request, it's best to keep them in the same document.

## Ways to contribute

Contributing isn't just writing code - it's anything that improves the project. All contributions for Simple Podcasting are managed right here on GitHub. Here are some ways you can help:

### Reporting bugs

If you're running into an issue with the plugin, please take a look through [existing issues](https://github.com/10up/simple-podcasting/issues) and [open a new one](https://github.com/10up/simple-podcasting/issues/new) if needed. If you're able, include steps to reproduce, environment information, and screenshots/screencasts as relevant.

### Suggesting enhancements

New features and enhancements are also managed via [issues](https://github.com/10up/simple-podcasting/issues). As project owners, 10up sets the direction and roadmap and may not prioritize or decide to implement if outside of the main goals of the plugin.

### Pull requests

Pull requests represent a proposed solution to a specified problem. They should always reference an issue that describes the problem and contains discussion about the problem itself. Discussion on pull requests should be limited to the pull request itself, i.e. code review.

For more on how 10up writes and manages code, check out our [10up Engineering Best Practices](https://10up.github.io/Engineering-Best-Practices/).

## Maintenance process

### Triage

Issues and WordPress.org forum posts should be reviewed weekly and triaged as necessary. Not all tasks have to be done at once or by the same person. Triage tasks include:

* Responding to new WordPress.org forum posts and GitHub issues/PRs with an acknolwedgment and following up on existing open/unresolved items that have had movement in the previous week.
* Marking forum posts as resolved when corresponding issues are fixed or as not a support issue if not relevant.
* Creating GitHub issues for WordPress.org forum posts as necessary or linking to them from existing related issues.
* Applying labels and milestones to GitHub issues.

#### Issue labels

All issues should be labeled as bugs (`type:bug`), enhancements/feature requests (`type:enhancement`), or questions/support (`type:question`). Each issue should only be of one "type".

Bugs and enhancements that are closed without a related change should be labeled as `declined`, `duplicate`, or `invalid`. Invalid issues would be where a problem is not reproducible or opened in the wrong repo and should be relatively uncommon. These labels are all prefixed with `closed:`.

There are two other labels that are GitHub defaults with more global meaning we've kept: `good first issue` and `help wanted`.

### Review against WordPress updates

During weekly triage, the tested up to version should be compared against the latest versions of WordPress, both the new and classic editors, and the standalone Gutenberg plugin. If there's a newer version of either, the plugin should be re-tested using any automated tests as well as any manual tests indicated below, and the tested up to version bumped and committed to both GitHub and the WordPress.org repository.

### Release cycle

New releases are targeted based on number and severity of changes along with human availability. When a release is targeted, a due date will be assigned to the appropriate milestone.

### Release instructions

1. Branch: Starting from `develop`, cut a release branch named `release/X.Y.Z` for your changes.
1. Version bump: Bump the version number in `simple-podcasting.php`, `package.json`, and `readme.txt` if it does not already reflect the version being released.  Update both the plugin "Version:" property and the plugin `PODCASTING_VERSION` constant in `simple-podcasting.php`.
1. Changelog: Add/update the changelog in both `CHANGELOG.md` and `readme.txt`.
1. Props: update `CREDITS.md` with any new contributors, confirm maintainers are accurate.
1. New files: Check to be sure any new files/paths that are unnecessary in the production version are included in `.distignore`.
1. Readme updates: Make any other readme changes as necessary. `README.md` is geared toward GitHub and `readme.txt` contains WordPress.org-specific content. The two are slightly different.
1. Merge: Make a non-fast-forward merge from your release branch to `develop` (or merge the pull request), then do the same for `develop` into `trunk` (`git checkout trunk && git merge --no-ff develop`). `trunk` contains the latest stable release.
1. Test: Run through common tasks while on `trunk` to be sure it functions correctly. Don't forget to build.
1. Push: Push your `trunk` branch to GitHub (e.g. `git push origin trunk`).
1. Release: Create a [new release](https://github.com/10up/simple-podcasting/releases/new), naming the tag and the release with the new version number, and targeting the `trunk` branch.  Paste the changelog from `CHANGELOG.md` into the body of the release and include a link to the [closed issues on the milestone](https://github.com/10up/simple-podcasting/milestone/#?closed=1).
1. SVN: Wait for the [GitHub Action](https://github.com/10up/simple-podcasting/actions) to finish deploying to the WordPress.org repository. If all goes well, users with SVN commit access for that plugin will receive an emailed diff of changes.
1. Check WordPress.org: Ensure that the changes are live on https://wordpress.org/plugins/simple-podcasting/. This may take a few minutes.
1. Close the milestone: Edit the [milestone](https://github.com/10up/simple-podcasting/milestone/#) with release date (in the `Due date (optional)` field) and link to GitHub release (in the `Description` field), then close the milestone.
1. Punt incomplete items: If any open issues or PRs which were milestoned for `X.Y.Z` do not make it into the release, update their milestone to `X.Y.Z+1`, `X.Y+1.0`, `X+1.0.0`, or `Future Release`.
