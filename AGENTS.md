# AGENTS.md

Canonical guidance for every coding agent working in this repository. Keep
tool-specific shims small; update this file when project-wide behavior changes.

## Start with product truth

Before changing behavior, read:

1. `PRODUCT.md` for users, purpose, principles, accessibility, and design tone.
2. `ARCHITECTURE.md` for application surfaces, trust boundaries, data ownership,
   and external-service failure behavior.
3. The nearest feature tests and implementation files for the existing local
   convention.

If either project brief still contains scaffold prompts relevant to the task,
surface that missing decision before inventing a durable contract.

## Default stack and boundaries

- Laravel owns routing, authorization, validation, normalization, persistence,
  transactions, money, counters, and integration failure behavior.
- Inertia transports server-owned page data to React; shared TypeScript types
  must stay aligned with Laravel payloads.
- React owns interaction and presentation. Zustand is reserved for state shared
  across a genuinely complex client workflow, not routine form inputs.
- Filament, when installed, is for internal operator tooling. Customer-facing
  product workflows stay in the Inertia/React application unless the
  architecture brief explicitly says otherwise.
- PostgreSQL is the production database. SQLite is a fast test engine, not a
  substitute for PostgreSQL compatibility.
- External systems are optional at the point of failure unless the product
  brief explicitly makes them load-bearing. Persist local work safely and make
  integration failure visible and retryable.

## Work as a vertical slice

A normal Laravel/Inertia feature is one bounded slice:

`route -> authorization/validation -> domain/service -> persistence -> Inertia props/types -> React UI -> feature tests`

When delegation is available, prefer one feature implementer for that complete
slice. Add a specialist only for a genuinely separable boundary such as complex
client state, concurrency, money, external APIs, or infrastructure. Freeze the
interface first and assign non-overlapping files. The orchestrator remains
accountable for scope, integration, review order, and publishing.

Use the playbooks under `.agents/` for implementation, context checkpoints,
test-gap review, schema/demo-data review, and browser/PR evidence.

## Security and correctness defaults

- Treat request data, URL identifiers, uploaded files, and integration payloads
  as hostile.
- Authorize every owned resource and test cross-account access.
- Store money as integer minor units and compute totals server-side.
- Use database transactions and appropriate locking for counters, inventory,
  idempotency, or other concurrent writes.
- Keep stable identifiers stable across display-label changes.
- Make normalization replace untrusted input rather than allowing raw client
  values to win.
- Keep secrets, tokens, cookies, production credentials, and customer data out
  of fixtures, screenshots, logs, checkpoints, and pull requests.

Record project-specific load-bearing rules in `PRODUCT.md` or
`ARCHITECTURE.md`; do not bury them in an agent-only prompt.

## Schema and demo data move together

Any migration, model cast/fillable change, persisted JSON-shape change, or new
table/column must update the relevant factories and demo seeders in the same
pull request. Seed through real application write paths where they exist.

Verify schema changes on a throwaway SQLite database by migrating and seeding
twice. The second seed run must remain idempotent. Never use the shared local
PostgreSQL database for destructive seed verification.

## Required validation

Run commands from the main checkout because DDEV owns one project root. For a
worktree, use the supplied `--worktree NAME` support or change directories
inside the web container as documented in `docs/project/WORKTREES.md`.

```bash
ddev exec php artisan test       # complete SQLite suite
ddev test-postgres               # complete isolated PostgreSQL 16 suite
ddev exec vendor/bin/pint --test
ddev exec npm run build          # whenever frontend assets changed
```

Do not report a code change as fully validated unless both complete database
suites pass. Focused tests are useful during implementation but do not replace
the final complete suites.

## Mandatory pre-PR order

1. Finish the implementation and inspect the complete diff against the base.
2. Run the independent test-gap review and close every in-diff gap.
3. For schema/model-shape changes, run the independent schema/demo-data review
   and seed twice on throwaway SQLite.
4. Run the full SQLite suite, the full PostgreSQL suite, style checks, and the
   frontend build when applicable.
5. For UI or user-facing behavior, serve the worktree preview and run the
   independent browser review. Exercise the change end to end, inspect console
   and network results, and capture screenshots.
6. Only after the applicable gates pass: commit, push, and open or update a
   draft pull request from `.github/pull_request_template.md`.
7. Run `tools/pr-contract/check-current-pr.sh`, wait for required GitHub checks,
   and mark ready only when the branch is conflict-free and all gates pass.

Implementation tests do not substitute for independent coverage review.
Screenshots do not substitute for data round-trip assertions.

## Browser evidence contract

UI pull requests must include:

- the exact preview URL;
- the configured critical-flow status lines;
- console and unexpected network-error results;
- at least three descriptive screenshots when the configured contract requires
  them;
- durable PNG copies under `pr-assets/<branch>/`; and
- inline GitHub `user-attachments` URLs in the PR body.

Browser automation is for validation and capture. Publish screenshot files and
write the pull request through the GitHub CLI or connector, not browser UI,
clipboard automation, or a native file picker.

If browser tooling is genuinely unavailable, say `Not browser-validated:` and
provide exact manual steps and URLs. If no browser-visible surface exists, say
`No browser-visible surface:` and explain why.

## Git and worktree safety

- Preserve unrelated user changes in dirty worktrees.
- Never edit the same file concurrently from two agents.
- Never symlink a worktree's `vendor/` directory to the main checkout.
- Keep previews on disposable cloned databases; understand whether uploaded
  files use shared storage before testing uploads.
- Avoid destructive Git or filesystem commands unless explicitly authorized and
  precisely scoped.
- Open pull requests as drafts. Never weaken the PR validator to make a feature
  branch pass.

## Keep this file generic

Project-specific routes, account emails, domain concepts, third-party vendors,
and business invariants belong in project-owned documentation and the PR
contract configuration. The reusable workflow belongs here.

