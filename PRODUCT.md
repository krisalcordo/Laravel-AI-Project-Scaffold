# Product

## Register

product

## Users

Laravel teams and coding agents starting a new product repository. They want a
repeatable technical baseline without copying another application's business
logic, accounts, routes, infrastructure, or visual identity.

The primary job is to move from a clean Laravel application to a disciplined,
reviewable development environment with little reinvention. Maintainers also
need one upstream place to improve that baseline for future projects.

## Product Purpose

Laravel Project Scaffold packages the operational layer that ordinary framework
starters omit: canonical agent guidance, DDEV worktrees, isolated previews,
SQLite and PostgreSQL validation, demo-data discipline, browser evidence,
Impeccable design review, and a machine-enforced pull-request contract.

Success means a new project can apply the scaffold, supply its own product and
design truth, and achieve the same review rigor without inheriting unrelated
domain code.

## Brand Personality

Precise, dependable, opinionated. The scaffold should feel like experienced
engineering judgment made portable: direct enough to follow under pressure,
specific enough to automate, and restrained enough to fit many products.

## Anti-references

- Framework starters that demonstrate installation but omit the operating
  practices required to ship safely.
- Giant boilerplate applications that force new products to delete sample
  domain code before they can begin.
- Process documents made entirely of unenforced checklists.
- Generic AI-generated interfaces that ignore product context, accessibility,
  responsive behavior, and a coherent design system.
- Tool-specific instructions that make the repository unusable by another
  coding agent.

## Design Principles

- **Portable, not abstract.** Defaults are immediately useful, while every
  product-specific name, route, account, and critical flow remains configurable.
- **Evidence is part of the work.** Tests, browser round trips, console and
  network inspection, screenshots, and PR-body mappings are deliverables.
- **Design context precedes interface code.** Generated projects carry
  Impeccable, `PRODUCT.md`, and `DESIGN.md`; UI work cannot pass the PR contract
  while those files remain placeholders.
- **One source for every rule.** Reusable policy lives in the scaffold; each
  generated project owns its product, architecture, and visual system.
- **The validator protects the standard.** Feature work fixes evidence or
  implementation gaps. It never weakens the gate merely to pass.

## Accessibility & Inclusion

Generated projects target WCAG 2.1 AA at minimum. The workflow requires
keyboard, focus, responsive, motion, contrast, error-state, and real browser
review for user-facing changes. Instructions use plain language and remain
tool-neutral wherever the underlying capability is not tool-specific.

