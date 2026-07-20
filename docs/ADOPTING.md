# Adopting the scaffold

## 1. Create the Laravel application

Create a clean repository using the Laravel version chosen for the product.
Install the React/TypeScript Inertia variant of Breeze, then add the packages
the project actually needs.

Illustrative package set:

```bash
composer require inertiajs/inertia-laravel laravel/sanctum tightenco/ziggy
composer require --dev laravel/breeze laravel/pint phpunit/phpunit
php artisan breeze:install react --typescript
npm install zustand @headlessui/react
npm install --save-dev @tailwindcss/forms
```

Filament is optional:

```bash
composer require filament/filament
```

Use the installers and version constraints appropriate to the selected Laravel
release. Commit `composer.lock` and `package-lock.json` in the application.

## 2. Apply this operational baseline

From this scaffold repository:

```bash
./bin/apply-scaffold \
  --target /absolute/path/to/application \
  --name "Application Name" \
  --slug application-name \
  --description "What the application enables"
```

The command requires an existing Laravel `artisan` file, validates the slug,
and refuses to overwrite any path by default. If it reports conflicts, merge
those files intentionally. `--force` is intended only for a clean, committed
target where replacement has been reviewed.

## 3. Merge the reference configuration

The installer intentionally creates two reference files:

- `vite.config.scaffold.js`: merge its DDEV `server` block and plugins into the
  application's `vite.config.js`.
- `phpunit.scaffold.xml`: merge its test environment into `phpunit.xml`,
  preserving any project-specific suites.

Delete the reference copies after merging. Add the lines from
`.gitignore.scaffold` to the application's `.gitignore`, then delete that
reference as well.

## 4. Configure project-owned values

Edit `.project-scaffold`:

- `PROJECT_SLUG`: DDEV-safe lowercase slug.
- `PROJECT_REPOSITORY`: GitHub `owner/repository`, once known.
- preview slot count and port range;
- preview health path;
- a fast smoke-test filter that exists in the project; and
- optional shared-upload behavior.

Edit `tools/pr-contract/config.php`:

- file patterns that make a change UI-visible or runtime-affecting;
- critical flows every UI PR must report;
- minimum screenshot count;
- factory/seeder companions required for migrations; and
- whether local test credentials are required.

Keep the configured Impeccable checks enabled for UI paths. Specialize their
labels only when the project adds a stricter equivalent review.

Edit `.github/pull_request_template.md` so its critical-flow labels match that
configuration. Replace the example test-account row with safe, locally seeded
accounts. Never add production credentials.

## 5. Write product and architecture truth

Complete `PRODUCT.md`, `DESIGN.md`, and `ARCHITECTURE.md`. Start by loading the
vendored Impeccable skill:

```bash
node .agents/skills/impeccable/scripts/load-context.mjs
```

Use `$impeccable teach` to confirm product context and `$impeccable document`
to replace the generic design seed. Pay particular attention to:

- user roles and tenant ownership;
- integer-money and concurrency rules;
- public versus authenticated surfaces;
- optional versus required integrations;
- upload privacy and preview storage effects;
- the highest-risk end-to-end round trip; and
- the design and accessibility bar.

These briefs should contain durable product decisions. Tickets and chat history
are not substitutes.

## 6. Start DDEV

The installed `.ddev/config.yaml` uses PostgreSQL 16 and an HMR port. Generate
preview slots after choosing the project slug and slot count:

```bash
.ddev/generate-preview-slots.sh
ddev start
ddev composer install
ddev exec npm ci
ddev exec npm run build
ddev exec php artisan migrate
ddev exec php artisan db:seed
```

If the application does not seed data, change the documented startup and PR
account expectations rather than keeping a command that cannot work.

## 7. Verify the baseline

```bash
ddev exec php artisan test
ddev test-postgres
ddev exec vendor/bin/pint --test
ddev exec npm run build
php tools/pr-contract/tests/run.php
```

Create a disposable branch/worktree to verify the helpers:

```bash
git worktree add .worktrees/scaffold-smoke -b scaffold-smoke
ddev worktree-setup scaffold-smoke
ddev preview start scaffold-smoke --fresh
ddev preview status
ddev preview stop scaffold-smoke
git worktree remove .worktrees/scaffold-smoke
```

Run DDEV commands from the main checkout.

## 8. Protect the GitHub workflow

Once the repository is pushed:

1. Create the orphan `pr-assets` branch used for durable screenshot archives.
2. Enable branch protection for the Tests and PR Contract checks.
3. Keep pull requests as drafts until validation and evidence are complete.
4. Install `gh` and the `gh-image` extension for screenshot publishing if the
   team uses that workflow.
5. Test one intentionally incomplete UI PR to prove the contract rejects it.

The validator must evolve when the project changes, but feature work must never
weaken it simply to pass a branch.
