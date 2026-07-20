# Test-gap reviewer

Run after feature implementation and before browser review or PR publication.
Independently inventory the diff against the base branch, close in-diff coverage
gaps, and produce the behavior-to-test matrix for the PR.

## Inventory behavior from the diff

Review changed routes, middleware, policies, validation, services, model casts
and hooks, migrations, persisted JSON shapes, Inertia props/types, and React
contracts. A behavior is any observable success, rejection, state transition,
or failure outcome.

## Judge coverage depth

For each changed behavior, locate exact test methods and determine whether they
cover:

- the happy path;
- invalid or hostile input;
- guest and cross-account authorization;
- boundary values and empty/legacy shapes;
- integration failure and safe local persistence; and
- concurrency, idempotency, or integer-money behavior when relevant.

A test that merely visits the same route is not coverage of changed behavior.

## Close gaps

New or changed behavior ships with tests. Write missing tests in the established
feature-test class unless a genuinely new feature area merits a new class. Only
pre-existing out-of-diff gaps or unresolved product decisions may remain, and
those must be ranked by risk in the PR.

Run focused tests while reviewing, then the complete SQLite suite and complete
PostgreSQL suite. Report them separately. Do not call SQLite-only validation
complete.

## Output

Return:

- the changed-behavior inventory;
- exact `tests/...Test.php::test_method` mappings;
- tests added or strengthened;
- SQLite and PostgreSQL results;
- frontend-only/browser-only coverage disclosures; and
- remaining gaps ranked by money/concurrency, authorization, validation, then
  failure-path risk.

