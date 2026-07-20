---
name: Laravel Project Scaffold
description: A precise, portable operating baseline for Laravel product teams.
---

# Design System: Laravel Project Scaffold

## 1. Overview

**Creative North Star: "The Maintainer's Field Manual"**

This repository is primarily read in Markdown, terminals, pull requests, and
code review tools. Its design serves comprehension. Information should feel
calm, exact, and operational, with short paths from principle to command to
evidence. Decoration never competes with the instruction.

The scaffold rejects generic AI polish and product-specific branding. Examples
should be neutral enough to reuse, but concrete enough to run.

**Key Characteristics:**

- Clear hierarchy with descriptive headings.
- Compact prose and runnable examples.
- Tables only when comparison or mapping benefits from them.
- Explicit success, failure, and safety conditions.

## 2. Colors

Repository documentation inherits the reader's editor or Git host theme. Code
samples and generated applications must never assume a light or dark theme.

**The Context Rule.** Product colors belong to the generated project's
`DESIGN.md`, not this scaffold.

## 3. Typography

Documentation uses the host's interface and monospace fonts. Prose stays under
75 characters where practical; code and tables may run wider when precision
requires it.

**The Hierarchy Rule.** Heading depth communicates structure; bold text is not
a substitute for a coherent outline.

## 4. Elevation

The scaffold has no visual elevation system. Generated applications document
their own surface and shadow vocabulary through Impeccable.

## 5. Components

Commands, file trees, checklists, tables, and PR templates are the scaffold's
documentation components. Each must be copyable, accurately labeled, and clear
about placeholders. Nested callouts and decorative containers are prohibited.

## 6. Do's and Don'ts

### Do:

- **Do** lead with the outcome and the authoritative command or file.
- **Do** distinguish reusable policy from project-owned decisions.
- **Do** make automation configuration explicit and testable.

### Don't:

- **Don't** import customer-specific domain language into reusable files.
- **Don't** replace executable validation with an honor-system checklist.
- **Don't** prescribe a product palette, typeface, or visual identity to every
  generated application.
