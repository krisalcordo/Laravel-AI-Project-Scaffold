# Scaffold adoption checklist

The source guide is maintained in the scaffold repository. For this project:

- [ ] Complete `PRODUCT.md` and `ARCHITECTURE.md`.
- [ ] Configure `.project-scaffold` including the repository name and smoke test.
- [ ] Merge and delete `vite.config.scaffold.js`.
- [ ] Merge and delete `phpunit.scaffold.xml`.
- [ ] Merge and delete `.gitignore.scaffold`.
- [ ] Tailor `tools/pr-contract/config.php` and the PR template together.
- [ ] Create realistic idempotent demo data and safe local test accounts.
- [ ] Generate preview slots with `.ddev/generate-preview-slots.sh`.
- [ ] Pass SQLite, PostgreSQL, Pint, frontend build, and PR-validator tests.
- [ ] Push an orphan `pr-assets` branch and protect Tests + PR Contract checks.

