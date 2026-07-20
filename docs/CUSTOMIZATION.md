# Project customization checklist

Use this list once per generated project.

## Identity

- [ ] Replace scaffold tokens with the real product name and slug.
- [ ] Set the GitHub `owner/repository` in `.project-scaffold`.
- [ ] Confirm the DDEV hostname does not conflict with another project.
- [ ] Replace generated README content with the product's own overview.

## Product and architecture

- [ ] Complete every prompt in `PRODUCT.md`.
- [ ] Run `$impeccable document` and replace the seed `DESIGN.md`.
- [ ] Name all application surfaces and audiences in `ARCHITECTURE.md`.
- [ ] Record tenant ownership and cross-account response behavior.
- [ ] Record money, locking, stable-identity, and idempotency rules.
- [ ] Record integration and upload failure behavior.

## Local environment

- [ ] Merge `vite.config.scaffold.js` into the real Vite config.
- [ ] Merge `phpunit.scaffold.xml` into the real PHPUnit config.
- [ ] Merge `.gitignore.scaffold` into `.gitignore`.
- [ ] Generate preview slots and restart DDEV.
- [ ] Point `SCAFFOLD_SMOKE_TEST_FILTER` at a stable fast test.
- [ ] Decide whether previews may share uploaded files.

## Data and test users

- [ ] Create realistic, idempotent demo seeders.
- [ ] Seed every role required for browser validation.
- [ ] Put local-only credentials in the PR template.
- [ ] Verify seeding twice on throwaway SQLite.

## Pull-request contract

- [ ] Tailor UI/runtime/frontend/migration path patterns.
- [ ] Keep Impeccable context, audit, and polish checks enabled for UI changes.
- [ ] Replace generic critical flows with the product's real round trip.
- [ ] Confirm migration companion rules match factory/seeder organization.
- [ ] Run the validator's own test harness.
- [ ] Create and push the orphan `pr-assets` branch.
- [ ] Protect Tests and PR Contract checks in GitHub.

## Clean-up

- [ ] Remove merged reference files.
- [ ] Search for `__PROJECT_` tokens and scaffold prompts.
- [ ] Run both complete database suites and the frontend build.
- [ ] Commit the baseline before feature work starts.
