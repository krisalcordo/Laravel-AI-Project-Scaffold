## Summary

- Describe the scaffold behavior, policy, or documentation changed.
- Explain how generated projects are affected.

## Preview

No runnable preview: this repository distributes tooling and templates rather
than a runtime application.

## Test accounts

No authentication required.

## Browser validation

No browser-visible surface: scaffold changes are validated through installer,
syntax, context-loader, and PR-contract checks.

## Test coverage

| Behavior | Check |
| --- | --- |
| Replace with changed scaffold behavior | `./bin/check-scaffold` |

### Remaining gaps

None.

## Validation

- [ ] `./bin/check-scaffold`
- [ ] Installer succeeds against a disposable Laravel-shaped target
- [ ] Second installation refuses conflicts without `--force`
- [ ] All three supported worktree layouts resolve correctly
- [ ] Preview-slot generation honors project configuration
- [ ] Generated-project PR-contract scenarios pass
- [ ] Git whitespace check passes
