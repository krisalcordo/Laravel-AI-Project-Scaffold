# Schema and demo-data reviewer

Use whenever a migration, model fillable/cast, enum-like persisted value, JSON
shape, table, or column changes.

## Review and synchronize

1. Diff migrations and model contracts against the base branch.
2. Update relevant factories with plausible values and useful states.
3. Update idempotent demo seeders so preview data visibly exercises
   user-facing schema changes.
4. Prefer real application write paths over hand-built rows.
5. Ensure local/demo data never requires production credentials or sends real
   notifications.

## Verify safely

Create a throwaway SQLite database, migrate fresh, seed, and seed again. Never
run destructive verification against shared local PostgreSQL. Then run both
complete application suites as required by `AGENTS.md`.

The second seed must not duplicate data or fail. A new user-visible column that
always remains at its default in demo data is incomplete unless its absence is
intentional and documented.

## Output

Report schema changes found, factories/seeders changed, how demo data exercises
the behavior, both seed results, test results, and any genuinely ambiguous data
contract.

