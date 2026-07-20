# AGENTS.md

Canonical guidance for maintaining Laravel Project Scaffold itself. Generated
applications receive their reusable instructions from `template/AGENTS.md`.

## Read first

1. `PRODUCT.md` defines the scaffold's users, purpose, and non-negotiable
   principles.
2. `DESIGN.md` defines the documentation design posture.
3. `ARCHITECTURE.md` defines the installer, template, skill, and validator trust
   boundaries.
4. `docs/ADOPTING.md` describes the consumer workflow changes must preserve.

## Source-of-truth boundaries

- Root documents describe this scaffold repository.
- `template/` contains files installed into future Laravel projects.
- `.agents/skills/impeccable/` is the vendored Impeccable distribution. The
  installer copies it to the generated project at the same path.
- `template/tools/pr-contract/config.php` carries project-customizable policy;
  `PrContractValidator.php` carries reusable enforcement.
- `.github/workflows/pr-contract.yml` enforces the scaffold repository's own
  documentation contract; `template/.github/workflows/pr-contract.yml` is the
  independently configurable workflow installed into generated projects.
- `template/.ddev/` is the source of truth for generated multi-worktree
  bootstrap, preview, and isolated PostgreSQL test commands.
- Do not add application domain models, routes, fixtures, accounts, deployment
  providers, or visual branding to this repository.

## Impeccable

Use the vendored Impeccable skill for any design-facing scaffold change. Load
`PRODUCT.md` and `DESIGN.md` through its context loader before editing. Do not
edit the vendored skill casually; synchronize the complete upstream bundle and
run the scaffold check so references and scripts cannot drift independently.

Generated-project UI policy belongs in `template/AGENTS.md`, the browser review
playbook, the PR template, and the PR-contract validator. Keep those four places
synchronized when the Impeccable review contract changes.

## Change requirements

- Preserve conflict-safe installation. New installed files must participate in
  the installer's conflict inventory and token replacement.
- Keep placeholders confined to generated templates. Root scaffold context must
  remain complete enough for its own agents to act without guessing.
- Add or update validator scenarios for every enforcement rule.
- Keep `pull_request_target` safe: never execute code from a PR head.
- Preserve executable bits on commands and scripts.
- Keep generated instructions tool-neutral except where a named capability such
  as Impeccable or DDEV is intentionally required.

## Validation

Run:

```bash
./bin/check-scaffold
```

Also apply the scaffold to a disposable Laravel-shaped directory and verify:

- first installation succeeds;
- project tokens are replaced only in installed files;
- a second installation refuses conflicts without `--force`;
- preview-slot generation honors the configured slug, count, and base port; and
- the installed PR-contract test suite passes in PHP 8.3.

Inspect the complete diff and ensure no product-specific source terms leaked
into `template/` before committing.
