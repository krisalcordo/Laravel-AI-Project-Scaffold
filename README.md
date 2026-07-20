# Laravel Project Scaffold

An opinionated, reusable baseline for Laravel products built with DDEV,
PostgreSQL, Inertia, React, and TypeScript.

This repository packages the engineering workflow that tends to matter after
the first week of a project:

- canonical, tool-neutral agent instructions;
- an explicit product and architecture brief;
- isolated DDEV worktree previews;
- fast SQLite tests plus production-parity PostgreSQL tests;
- independent implementation, coverage, data, and browser-review playbooks;
- screenshot-backed pull requests; and
- a machine-enforced pull-request contract.

It is deliberately a **scaffold, not an application**. It contains no Ollivy
domain models, routes, seeded identities, product rules, deployment provider,
or customer-specific infrastructure. Start with a clean Laravel application,
then apply the operational baseline in this repository.

## Recommended stack

| Layer | Default | Role |
| --- | --- | --- |
| Runtime | PHP 8.3+, Laravel 13 | Application and domain logic |
| Local environment | DDEV | Repeatable PHP, Node, and database services |
| Production database | PostgreSQL 16 | Production-parity relational database |
| Fast test database | SQLite in memory | Fast feedback for the full PHP suite |
| App UI | Inertia 2, React, TypeScript, Headless UI | Server-routed product application |
| Client state | Zustand | Complex local interaction state only |
| Styling/build | Tailwind CSS, Vite; Alpine for small Blade islands | Design system and frontend assets |
| Authentication | Laravel Breeze | Small, understandable auth baseline |
| Internal admin | Filament (optional) | Operator tooling, kept separate from customer UI |
| Quality | PHPUnit, Pint, GitHub Actions | Behavior, style, and two-engine validation |

See [docs/STACK.md](docs/STACK.md) for the boundaries and rationale.

## Use it on a new project

1. Create a clean Laravel repository and install the application stack.
2. Run the installer from this repository:

   ```bash
   ./bin/apply-scaffold \
     --target /absolute/path/to/my-app \
     --name "My App" \
     --slug my-app \
     --description "A short statement of what the product does"
   ```

3. Complete the generated `PRODUCT.md`, `ARCHITECTURE.md`, and
   `.project-scaffold` files before feature work begins.
4. Merge the supplied Vite and PHPUnit references if the new Laravel starter
   already contains project-specific changes.
5. Generate preview slots, start DDEV, and run the verification commands in
   [docs/ADOPTING.md](docs/ADOPTING.md).

The installer refuses to overwrite existing files. Use `--force` only after
reviewing the target and committing its current state.

## What gets installed

```text
AGENTS.md                         canonical coding-agent rules
PRODUCT.md                        product/design brief template
ARCHITECTURE.md                   system-boundary template
.project-scaffold                 project-specific workflow settings
.agents/                          tool-neutral role playbooks
.ddev/                            local, worktree, preview, and Postgres helpers
.github/                          CI and pull-request contract
tools/pr-contract/                local/CI PR-body validator
docs/project/                     project-owned workflow documentation
```

The installer places `vite.config.scaffold.js` and
`phpunit.scaffold.xml` beside the application's existing files instead of
overwriting them. Review and merge them, then delete the reference copies.

## Design principle

The scaffold distinguishes reusable engineering policy from product truth:

- `AGENTS.md` says **how work is performed and proven**.
- `PRODUCT.md` says **who the product serves and what must remain true**.
- `ARCHITECTURE.md` says **where behavior belongs and which boundaries matter**.
- `.project-scaffold` supplies **local names, URLs, health checks, and smoke-test settings**.

That separation is the key to reusing this baseline without importing the
business logic of the project that inspired it.

## Repository status

This repository is ready to initialize and push as its own Git repository.
Run `./bin/check-scaffold` before publishing changes to the scaffold itself.
