# Impeccable integration

Impeccable is vendored at `.agents/skills/impeccable/` and copied to the same
path in every generated project. The full distribution is present: `SKILL.md`,
command references, context loader, live-design scripts, and agent metadata.

## Why it is part of the baseline

Engineering tests cannot prove visual hierarchy, responsive composition,
interaction clarity, motion restraint, empty/error states, or whether a screen
feels coherent with the rest of a product. Impeccable makes those concerns an
explicit workflow with project context and review evidence.

It does not impose one aesthetic. `PRODUCT.md` establishes users, purpose,
personality, anti-references, and accessibility. `DESIGN.md` records the actual
visual tokens and component rules. Each project owns both.

## Required UI workflow

1. Load context with
   `node .agents/skills/impeccable/scripts/load-context.mjs`.
2. Use `$impeccable teach` if product context is incomplete.
3. Use `$impeccable document` if `DESIGN.md` is still a seed or no longer
   matches the code.
4. Shape new UX/UI before implementation and confirm the design brief.
5. After implementation, run an independent `$impeccable audit` and resolve
   accessibility, performance, responsive, theme, and anti-pattern findings.
6. Run `$impeccable polish`, resolve finish-quality findings, then perform the
   real-browser review and capture PR evidence.

The PR contract rejects UI changes when product/design context is absent or
still contains scaffold markers. It also requires completed Impeccable context,
audit, polish, accessibility, and responsive-review status lines.

## Updating the vendored skill

Synchronize the entire upstream skill directory. Do not update a single command
reference or script in isolation. Then run `./bin/check-scaffold`, apply the
scaffold to a disposable Laravel-shaped target, and confirm the installed
context loader resolves that target's `PRODUCT.md` and `DESIGN.md`.
