# Worktrees and previews

DDEV serves the main checkout, so run every `ddev` command from that checkout.
The helper commands resolve a bare worktree name under:

- `.claude/worktrees/<name>`;
- `.worktrees/<name>`; or
- `.worktrees/codex-app/<session>/<repository-directory>`.

## Bootstrap

```bash
ddev worktree-setup NAME
```

This creates a worktree-local `.env`, installs Composer and npm dependencies,
builds assets inside the container, prepares storage/cache directories, and
runs the configured smoke test. A worktree must have a real `vendor/` tree;
Composer symlinks can load application classes from the wrong checkout.

## Preview

```bash
ddev preview start NAME
ddev preview status
ddev preview stop NAME
```

Each preview receives a distinct hostname, port, and cloned PostgreSQL database.
Use the exact URL printed by the start command. The clone is disposable and is
dropped when the preview stops.

Uploads remain worktree-local unless `SHARE_PREVIEW_UPLOADS=1`. If sharing is
enabled, uploads can modify real local files even though database writes are
isolated; record that decision in `ARCHITECTURE.md` and the PR template.

## Production-parity tests

```bash
ddev test-postgres
ddev test-postgres --worktree NAME
```

The command creates a uniquely named PostgreSQL database, runs the requested
Artisan test arguments, and drops the database even after failure.

