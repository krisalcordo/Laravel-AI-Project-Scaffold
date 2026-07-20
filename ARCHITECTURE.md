# Architecture brief

Keep this concise and current. It is the map agents and reviewers use before
tracing code.

## System context

<!-- Describe the application, its users, and external systems in one paragraph. -->

## Application surfaces

| Surface | Audience | Technology | Entry points | Boundary |
| --- | --- | --- | --- | --- |
| Customer application | | Inertia + React/TypeScript | | |
| Public/guest experience | | Blade or Inertia | | |
| Internal operations | Staff only | Filament (optional) | `/admin` | Never a customer workflow |

Delete unused rows and add real surfaces. Do not mix audiences without an
explicit authorization and navigation decision.

## Core request paths

Document each load-bearing round trip in this form:

```text
route -> middleware/policy -> request validation -> service/domain action
      -> database transaction -> response/Inertia props -> UI state
```

## Data ownership and authorization

<!-- Which model owns each tenant-scoped resource? What is the expected 403/404
     behavior? Which public identifiers are safe to expose? -->

## Persisted contracts

<!-- Important columns, enums, JSON shapes, stable keys, and compatibility rules. -->

## Money, counters, and concurrency

<!-- Integer units, server authority, locking, idempotency, and boundary rules. -->

## External integrations

| Integration | Required or optional | Timeout/failure behavior | Retry/idempotency |
| --- | --- | --- | --- |
| | | | |

## Files and uploads

<!-- Disks, size/type rules, privacy, retention, and preview-environment effects. -->

## Background work and schedules

<!-- Queues, cron/scheduler requirements, retry limits, and operational visibility. -->

## Testing strategy

- SQLite in memory is the fast complete-suite default.
- PostgreSQL 16 is the required production-parity complete suite.
- Feature tests cover observable HTTP/domain behavior and authorization.
- Browser validation covers JavaScript interaction, rendering, accessibility,
  console errors, network failures, and the configured critical flows.

Add project-specific test boundaries and name the highest-risk suites.

