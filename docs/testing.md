# Testing the plugin

Simple Podcasting uses the WordPress test suite together with Cypress browser
tests. Run the checks that match the files you changed, then include the
result in the pull request:

```bash
npm run lint
npm run test:unit
npm run test:e2e
```

When WordPress or a browser service is unavailable, report the exact command
and environment instead of treating a skipped suite as a passing result.
