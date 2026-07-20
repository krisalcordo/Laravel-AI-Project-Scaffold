# Architecture

## System context

This repository is a source scaffold, not a Laravel runtime application. Its
installer applies a versioned overlay to an existing Laravel repository. The
target project then owns the copied instructions, local tooling, design context,
and pull-request enforcement.

## Repository surfaces

| Surface | Owner | Purpose |
| --- | --- | --- |
| Root documentation | Scaffold maintainers | Explain the reusable stack and adoption model |
| `template/` | Generated project | Files copied into a Laravel application |
| `.agents/skills/impeccable/` | Scaffold and generated project | Vendored design workflow and references |
| `bin/apply-scaffold` | Adopter | Conflict-safe installation and token replacement |
| `bin/check-scaffold` | Scaffold CI | Structural, syntax, leakage, and validator checks |
| `template/tools/pr-contract/` | Generated project CI | Enforce PR evidence against trusted base code |

## Installation path

```text
existing Laravel repository
  -> conflict inventory
  -> copy generated-project template
  -> copy vendored Impeccable skill
  -> replace project identity tokens in installed files only
  -> preserve existing application files unless --force was explicit
```

The installer never creates a Laravel application or resolves application
dependencies. The generated project owns its Composer and npm lockfiles.

## Trust boundaries

- `pull_request_target` executes only validator code and configuration from the
  trusted base branch.
- Pull-request head files are read for changed paths and exact test-method
  mappings, but head code is never executed by the PR-contract workflow.
- Screenshot archives come from the orphan `pr-assets` branch and must match the
  PR body's declared branch folder.
- `.project-scaffold` is committed configuration and must never contain secrets.
- Preview databases are disposable clones. Upload storage is isolated unless a
  project explicitly opts into sharing and documents the effect.

## Extension points

Generated projects customize `.project-scaffold`, `PRODUCT.md`, `DESIGN.md`,
`ARCHITECTURE.md`, `tools/pr-contract/config.php`, critical flows, test users,
and demo seeders. Generic behavior changes belong upstream in this repository;
domain behavior stays in the application.

## Verification

`bin/check-scaffold` verifies required files, shell syntax, product-term
leakage, and the PR validator's scenario suite. The installer is also exercised
against a disposable Laravel-shaped target before releases.

