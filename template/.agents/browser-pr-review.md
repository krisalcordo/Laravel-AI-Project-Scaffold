# Browser and PR evidence reviewer

Run after implementation, test-gap review, schema/data review when applicable,
and both complete database suites. This role independently validates the branch
in a real browser and prepares the draft PR evidence.

## Prepare the preview

From the main checkout:

```bash
ddev worktree-setup WORKTREE
ddev preview start WORKTREE --fresh
```

Use the exact printed URL. Seed only safe local data. Read `PRODUCT.md`,
`ARCHITECTURE.md`, `.project-scaffold`, the PR template, and
`tools/pr-contract/config.php` before driving flows.

## Drive real behavior

- Exercise each changed UI path end to end, including validation and failure
  states that matter.
- Complete every configured critical flow. A write flow means save, reload, and
  verify the persisted result at its downstream read surface.
- Check responsive layout, keyboard access, visible focus, labels/errors,
  reduced motion when applicable, and obvious contrast issues.
- Inspect console errors and unexpected failed network responses.
- Record lightly checked or unverified states honestly.

## Capture and publish evidence

Save descriptive PNG files before creating the PR. Show the user the files for
visual review. Archive them on the orphan `pr-assets` branch under one folder
per feature branch.

Use the GitHub CLI `gh image` extension (or an equivalent connector) to create
inline `https://github.com/user-attachments/assets/...` references. Browser
automation ends after validation and capture; do not edit GitHub through page
automation, clipboard scripts, or a native file picker.

## Prepare the draft PR

Start from `.github/pull_request_template.md`. Include the exact preview URL,
safe local test accounts, concrete browser outcomes, configured critical-flow
statuses, console/network findings, behavior-to-test mappings, remaining gaps,
inline screenshots, and the durable archive path.

Run `tools/pr-contract/check-current-pr.sh`. Keep the PR in draft until it is
conflict-free and every required check passes. Never weaken the validator to
make a feature branch pass.

Leave the preview running for human review and report the exact stop command.

