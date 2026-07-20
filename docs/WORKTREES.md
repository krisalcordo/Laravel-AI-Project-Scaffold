# Concurrent DDEV worktrees

Every generated project receives DDEV helpers for running multiple feature
worktrees at the same time. Each preview has its own hostname, port, process,
and cloned PostgreSQL database, so one branch can be exercised without changing
another branch's preview data.

Run all `ddev` commands from the main checkout. DDEV owns that checkout's
containers and mounts supported worktrees into them.

## Supported layouts

The helpers resolve a bare `NAME` in these locations:

- `.worktrees/<name>` for manual and general agent worktrees;
- `.claude/worktrees/<name>` for Claude Code worktrees; and
- `.worktrees/codex-app/<session>/<repository-directory>` for Codex app
  worktrees. Pass the session directory as `NAME` for this layout.

If the same name exists in more than one layout, the command stops and reports
the ambiguity instead of choosing a branch silently.

Create a conventional worktree from the main checkout with:

```bash
git worktree add .worktrees/my-feature -b my-feature
ddev worktree-setup my-feature
```

`worktree-setup` creates a local environment, installs Composer and npm
dependencies, builds assets, prepares Laravel storage and caches, and runs the
smoke-test filter configured in `.project-scaffold`.

## Start isolated previews

```bash
ddev preview start my-feature --fresh
ddev preview status
ddev preview stop my-feature
```

`start` assigns the first free preview slot and prints the exact URL. The
`--fresh` option replaces any prior preview clone with a new copy of the main
development database. Restarting without it reuses that worktree's existing
clone when possible. `stop` terminates the preview and drops its disposable
database.

Preview uploads are worktree-local by default. A project may explicitly set
`SHARE_PREVIEW_UPLOADS=1` in `.project-scaffold`, but that makes preview uploads
write to the main checkout's local storage and must be documented as a project
decision.

## Configure capacity

`.project-scaffold` controls the preview slug, base port, and slot count. The
default is 10 concurrent slots and the generator supports up to 50:

```bash
.ddev/generate-preview-slots.sh
.ddev/generate-preview-slots.sh 20
ddev restart
```

Do not hand-edit `.ddev/config.preview.yaml`; it is generated from those
settings.

## Test a worktree on PostgreSQL

```bash
ddev test-postgres --worktree my-feature
```

The command creates a uniquely named throwaway PostgreSQL database, runs the
requested Artisan tests inside the selected worktree, and drops the database
after either success or failure.

The installed project-specific reference is
`docs/project/WORKTREES.md`. Customize it when a project changes preview
storage, health checks, supported layouts, or database behavior.
