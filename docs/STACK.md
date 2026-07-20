# Stack and architectural defaults

This is an opinionated starting point, not a rule that every future product
must use every package. Deviations should be deliberate and written into the
new project's architecture brief.

## Core application

### Laravel 13 on PHP 8.3+

Laravel owns the HTTP boundary, validation, authorization, persistence,
transactions, notifications, queues, and integration orchestration. Prefer
framework conventions before adding repositories, command buses, or other
abstraction layers.

### PostgreSQL 16 in DDEV and production

PostgreSQL is the source of truth for compatibility. Use database constraints,
transactions, row locks, and indexes where the domain requires them. Avoid SQL
that is accidentally SQLite-specific.

### SQLite in-memory tests

The default PHP suite runs quickly against SQLite. Every code change still runs
the complete suite against isolated PostgreSQL before handoff. This combination
gives fast feedback without surrendering production parity.

## Customer interface

### Inertia 2 + React + TypeScript

Use Laravel routes and controllers to shape pages; use React for product UI.
This keeps authorization and canonical data on the server while avoiding a
separate API application for normal product work.

Shared TypeScript types are contracts, not a second source of truth. When a
controller prop or submitted payload changes, update Laravel validation,
TypeScript types, UI state, and feature tests together.

### Zustand, selectively

Use Zustand when several components coordinate complex, long-lived client
state such as builders, canvases, editors, or carts. Use component state and
ordinary form helpers for routine screens. A global store for every form makes
simple behavior harder to trace.

### Headless UI and Alpine, selectively

Headless UI is a useful accessible primitive layer for React dialogs, menus,
listboxes, and transitions when native controls are insufficient. Keep product
styling in the project's design system rather than treating primitives as a
finished visual language.

Alpine is appropriate for small interactions in server-rendered Blade surfaces.
Do not create parallel React and Alpine implementations of the same customer
workflow.

### Tailwind CSS + Vite

Tailwind supports a project-owned design system; Vite compiles TypeScript and
React. Define tokens and primitives early instead of letting feature pages
accumulate unrelated utility combinations.

The supplied Vite reference advertises the correct HTTPS DDEV origin for HMR
and derives the host from `DDEV_PRIMARY_URL`, which remains stable when preview
hostnames are also configured.

## Authentication and administration

### Laravel Breeze

Breeze provides a small authentication baseline that remains easy to inspect
and modify. Choose the React/TypeScript Inertia stack.

### Filament, optional and internal

Install Filament only when internal operators need a data-management surface.
Do not route customers into it merely because CRUD screens are convenient.
Customer workflows belong in the customer application with its own language,
navigation, and accessibility bar.

## Quality system

### PHPUnit feature tests

Prefer observable behavior through Laravel's HTTP and application boundaries.
Cover the happy path, hostile input, authorization, relevant boundaries, and
failure behavior. Unit tests are useful for dense pure logic; they do not
replace the feature contract.

### Laravel Pint

Use Pint for PHP formatting. Run it in check mode in CI and before handoff.

### GitHub Actions

The supplied workflow builds frontend assets, installs PHP dependencies, runs
the complete suite on SQLite and PostgreSQL 16, runs Pint, and tests the PR
contract. Extend it with product-specific linting or browser automation rather
than removing the two-engine gate.

### Browser evidence

Tests prove contracts; the browser review proves rendered interaction and the
real cross-screen round trip. UI pull requests document the exact preview,
critical flows, console/network state, and screenshots. The PR contract turns
that documentation into an enforceable gate.

## Version policy

The scaffold describes major-version intent. Each generated application owns
its lockfiles and dependency upgrade decisions. Do not copy a lockfile from an
unrelated product. At project creation time, resolve compatible current
versions, commit the resulting lockfiles, and let CI reproduce them.
