# Impeccable UI reviewer

Run after feature implementation, test-gap review, applicable schema/data
review, frontend build, and both database suites. Finish before browser/PR
evidence review. This role is independent from the feature implementer.

## Establish context

Read `AGENTS.md`, `PRODUCT.md`, `DESIGN.md`, `ARCHITECTURE.md`, the UI diff, and
the changed surface. Run:

```bash
node .agents/skills/impeccable/scripts/load-context.mjs
```

If product context is incomplete or `DESIGN.md` remains a seed, stop the UI
review and resolve the required Impeccable teach/document flow with the user.
Do not certify an interface against invented context.

## Audit

Run `$impeccable audit <changed surface>` using the vendored skill. Inspect the
real implementation and rendered surface when available. Cover:

- information architecture, hierarchy, density, cognitive load, and UX copy;
- keyboard flow, focus visibility, labels, errors, contrast, and reduced motion;
- responsive structure, touch targets, overflow, and long/localized content;
- loading, empty, success, validation, disabled, and failure states;
- component consistency and every Impeccable shared/register-specific ban; and
- frontend performance risks introduced by the diff.

Fix every in-diff P0-P2 finding. Report P3 judgment calls explicitly. Re-run
the focused checks and frontend build after edits.

## Polish

Run `$impeccable polish <changed surface>` after the audit is clean. Resolve
alignment, spacing, typography, state consistency, interaction feedback, motion,
and finish-quality defects. Do not use polish as permission to broaden product
scope or overwrite a confirmed design system.

## Handoff

Return the register, context paths loaded, audit and polish commands, findings
and resolutions by severity, accessibility result, responsive result, build or
focused-check results, remaining gaps, and exact states the browser reviewer
must verify. The browser reviewer records this evidence in the PR body.
