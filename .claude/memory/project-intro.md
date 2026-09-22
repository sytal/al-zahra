# Al Zahra Institute — Project Memory

## What this is
Online institute platform for Dr. [Wife's Name] (Neurolinguist/Researcher),
brand name "Al Zahra Institute". Modules: Articles, Research, Courses,
Resources, Consultations, Certificates. No payment gateway yet.

## Stack
Laravel + Livewire (wire:navigate, no page reloads) + Filament (admin) +
MySQL + Redis + Tailwind. 5 locales: en, ur, hi, fa, ur-roman. UUID public
IDs everywhere. See docs/CLAUDE.md for full standards — do not repeat them
here, just follow them.

## Current phase
Phase 0 — foundation (branch `phase-0-foundation`). Packages installed,
not yet merged to `main` (user reviews manually per Section 23).

## Done so far
- Initial commit on `main` (Laravel skeleton + docs/CLAUDE.md +
  docs/PROJECT-BLUEPRINT.md).
- `.claude/` folder scaffolded: 10 agents, 9 skills, 4 rules files, memory.
- docs/CLAUDE.md expanded: seeders/factories standard (22B),
  mail/notifications (21.1), storage disk note (14), env vars checklist
  (28), autonomous-memory-update rule.
- docs/PROJECT-BLUEPRINT.md expanded: Part F2 seeder exact list.
- Branch `phase-0-foundation` created; all Phase 0 packages installed
  (10 Composer prod + Pest/pest-plugin-laravel dev + 4 npm dev) and
  committed in 2 commits.

## Next up
- Publish vendor configs (Breeze, Livewire, Filament panel, Spatie
  packages) and run their install commands (Filament panel install,
  Breeze scaffolding, Tailwind config wiring).
- `architect` agent: plan Part A tables in build order (start with
  `directors` + `settings` since Home/About depend on them).
