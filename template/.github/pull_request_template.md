## Summary

- What changed and why?
- What is the user/developer impact?

## Preview

[Open the worktree preview](http://__PROJECT_SLUG__-preview-N.ddev.site:PORT)

<!-- Replace this with the exact URL printed by `ddev preview start`. For a
main-checkout change, link the main DDEV URL and label it honestly. -->

## Test accounts

<!-- Safe local/preview seed data only. Never include production credentials. -->

| Scenario | Email | Password |
| --- | --- | --- |
| Demo user | `demo@__PROJECT_SLUG__.test` | `password` |

## Browser validation

<!-- For no UI surface: No browser-visible surface: <why> -->
<!-- If tooling is genuinely unavailable: Not browser-validated: <why>, then
add a `### Manual verification` section with exact steps and URLs. -->

- Console: no unexpected errors
- Network: no unexpected 4xx/5xx responses

![Changed UI state](https://github.com/user-attachments/assets/REPLACE_ME)
![Persisted round-trip](https://github.com/user-attachments/assets/REPLACE_ME)
![Downstream result](https://github.com/user-attachments/assets/REPLACE_ME)

Durable archive: `pr-assets/<branch-name>/`

## Critical flows

<!-- Keep these labels synchronized with tools/pr-contract/config.php. -->

- [ ] Authentication / sign-in
- [ ] Primary dashboard or home surface
- [ ] Core create/update round-trip: save, reload, and verify

## Test coverage

<!-- Every changed runtime behavior maps to a real test method. -->

| Behavior | Test |
| --- | --- |
| Replace with changed behavior | `tests/Feature/ExampleTest.php::test_example` |

Frontend-only coverage: <!-- Explain browser-only behavior and JS test limits; remove if no frontend changed. -->

### Remaining gaps

None.

## Validation

- [ ] Focused feature tests
- [ ] Full SQLite suite
- [ ] Full PostgreSQL 16 suite
- [ ] `vendor/bin/pint --test`
- [ ] `npm run build` when frontend files changed
- [ ] Factory and demo seeder updates when schema/persisted shape changed
- [ ] Throwaway SQLite migration + two seed runs when schema changed
- [ ] `php tools/pr-contract/tests/run.php`
- [ ] `tools/pr-contract/check-current-pr.sh` passes

