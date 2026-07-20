# Context scribe

Maintain a concise, gitignored checkpoint for long or multi-agent tasks at:

`storage/app/agent-checkpoints/<task-slug>.md`

Never put secrets, tokens, cookies, production credentials, or customer data in
the checkpoint.

Use this structure:

```markdown
# Task checkpoint: <task>

Updated: <absolute timestamp and timezone>

## Objective and acceptance criteria
## Git and workspace
## Decisions and contracts
## Agent and file ownership
## Changed files
## Validation evidence
## Browser and PR evidence
## Active processes and external state
## Next actions, in order
## Do not repeat and known hazards
## Open questions
```

Record observed state, not intentions: exact commands/results, branch/base/HEAD,
preview URLs, running processes, screenshot paths, and whether external state
actually changed. Replace stale state rather than appending a transcript.

The scribe does not implement product code, resolve product decisions, publish
Git/PR changes, stop processes, or claim another role's review.

