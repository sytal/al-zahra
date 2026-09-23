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

## Branching convention
One branch per major blueprint Part (docs/CLAUDE.md Section 23), stacked
on the previous, not squashed into a single phase branch:
- `phase-0-foundation`: Part A (DB), Part B (components), seeders,
  Article backend layer.
- `phase-1-public-pages` (current): public layout + Article C3/C4 pages,
  locale routing, Newsletter component.

## Known bug pattern (fixed once, watch for it elsewhere)
Any controller method on a route inside the `{locale}` group MUST
declare `string $locale` in its signature if the route has another URI
parameter — otherwise Laravel silently swaps the locale value into that
other parameter. Hit this on articles.show (see docs/CLAUDE.md Section
11). Will recur for courses.show, research.show, resources.show etc.
unless each one declares $locale explicitly.

## Next up
- Article (C3/C4) and Course (C9/C10) modules both fully working
  end-to-end with real demo content, SEO, sitemap — verified live.
- Same pattern still needs copying for Research, Resource, Consultation,
  Contact, Newsletter-confirm, Certificate-verify modules. Remember the
  $locale signature pitfall for each detail/show route.
- Course module still missing: dashboard.courses.* routes (My Courses,
  lesson viewer C13, lesson completion) — enroll() works but there's no
  "Continue Learning" destination yet (C10's button links to '#').
- Home page (C1) and About page (C2) not built yet — both need
  director-profile + settings, which are seeded and ready.
- i18n pass: only `en` locale files exist so far (common, nav, articles,
  newsletter); need ur/hi/fa/ur-roman per Section 11.
- Convert Breeze's Blade auth views (login/register/forgot/reset) to
  Livewire 4 components using shared components, per blueprint C21.
- Home page (C1) — needs director-profile + settings data, both seeded.
