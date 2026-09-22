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

## Known deviations from plan
- Breeze installed with the **Blade** stack, not Livewire — Breeze pins
  Livewire ^3.6.4 which conflicts with Filament v5's Livewire ^4.1
  requirement. Auth pages (C21) are currently Breeze's stock Blade
  views/controllers; still need conversion to Livewire 4 + shared
  `x-input`/`x-button` components per blueprint.
- Tailwind is v3 (classic PostCSS, `tailwind.config.js`), not v4 —
  required because `tailwindcss-rtl` only works with v3's JS plugin
  system. Section 8.1 allows either.

## Next up
- Essential seeders done — director-profile/footer components now have
  real data to render.
- Newsletter Livewire component (`livewire:newsletter.newsletter-form`)
  still needs building — footer references it as a stub.
- i18n pass: `common.php` only has `en` so far; need ur/hi/fa/ur-roman.
- Convert Breeze's Blade auth views (login/register/forgot/reset) to
  Livewire 4 components using shared components, per blueprint C21.
- Backend layer (Requests/Policies/Repositories/Services/Controllers) for
  each module, per Section 7 — then first real page (Home, C1).
