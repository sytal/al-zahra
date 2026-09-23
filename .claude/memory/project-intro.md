# Al Zahra Institute — Project Memory

## What this is
Online institute platform for Dr. [Wife's Name] (Neurolinguist/Researcher),
brand name "Al Zahra Institute". Modules: Articles, Research, Courses,
Resources, Consultations, Certificates. No payment gateway yet.

## Stack
Laravel 12 + Livewire 3 (wire:navigate, no page reloads) + Filament v5
(admin) + SQLite (dev) + Tailwind v3 (classic PostCSS, needed for
tailwindcss-rtl's JS plugin system) + Redis config-ready (Section 13). 5
locales: en, ur, hi, fa, ur-roman. UUID public IDs everywhere. See
docs/CLAUDE.md for full standards — do not repeat them here, just follow.

## Branching convention
One branch per major blueprint Part (docs/CLAUDE.md Section 23), stacked
on the previous, not squashed into a single phase branch:
- `phase-0-foundation`: Part A (DB, 16 tables), Part B (24 shared
  components), essential+demo seeders, Article backend layer.
- `phase-1-public-pages` (current): everything below.

## Done so far (phase-1-public-pages)
- **All 4 public content modules** (C3-C10): Article, Course, Research,
  Resource — Repository/Service(where needed)/Policy, Livewire index
  pages, detail pages, full SEO ($seo array + SeoSchema + sitemap),
  demo seeders. All verified live.
- **Home (C1), About (C2), Consultation (C17) + signed view (C20),
  Contact (C18), Certificate verify (C19)** — all built, all verified
  live (incl. real queued-job emails, signed-URL tamper rejection).
- **Newsletter confirm flow** (Part E/F) — was a silent gap (only
  subscribe existed), now has confirm route + job + mail.
- **Filament admin panel (Part D) — fully built**: 13 Resources
  (Article, ResearchPaper, Resource, Course w/ Lessons relation manager,
  Director, Category, Tag, User, Consultation, Certificate,
  NewsletterSubscriber, ContactMessage) + ManageSettings custom page +
  5 dashboard widgets. 34 admin routes registered. No
  filament-spatie-media-library-plugin installed — media uploads use a
  plain FileUpload + afterCreate/afterSave `addMediaFromDisk` pattern
  instead (edit-page previews of already-saved files don't render yet,
  functionally correct for upload/replace though — flagged as a known
  simplification if full round-trip fidelity is wanted later).
- **Caching layer (Section 13)**: `App\Support\CacheService` +
  Article/Course/ResearchPaper/Resource repositories retrofitted
  (slug-detail 24h, lists 1h, invalidated on save/delete via model
  `booted()` hooks). Verified hit + invalidation via tinker.
- **SendWelcomeEmailJob** (Part F) wired to `Registered` event.
- **i18n**: all 5 locales (en, ur, hi, fa, ur-roman) complete for every
  UI string introduced this session — en/ur-roman written directly,
  ur/hi/fa machine-translated with `// TODO: verify native translation`.
- **`.claude` itself was audited and fixed** — Section 27 checklist now
  has a mandatory Filament-resource step, a new `admin-builder` agent, a
  new `caching` skill, and an SEO-skill callout for Livewire-only pages.
  See `deferred-scope.md` for what's still intentionally out of scope.

## Known deviations from blueprint (all deliberate, documented at point of use)
- Breeze installed with the **Blade** stack, not Livewire — Breeze pins
  Livewire ^3.6.4 which conflicts with Filament v5's Livewire ^4.1
  requirement. Auth pages (C21) are still Breeze's stock Blade
  views/controllers — not yet converted to Livewire 4.
- Signed-URL routes (`consultations.signed-view`, `newsletter.confirm`)
  drop the blueprint's literal `/view/{signature}` URI segment —
  `temporarySignedRoute()` puts the signature in the query string, a
  literal path segment would never match. Comment left in each
  `routes/modules/*.php` file. Same pattern needed for any future
  signed route — don't take `{signature}` literally from blueprint text.
- Research/Contact-message-adjacent modules skip a Service class where
  there's no real business logic beyond CRUD (e.g. ResearchPaper) —
  avoids empty pass-through classes.

## Known bug patterns (fixed once each, watch for recurrence)
1. **`{locale}` + extra URI param**: any controller method on a route
   inside the `{locale}` group MUST declare `string $locale` explicitly
   if the route has another parameter (e.g. `{slug}`) — otherwise
   Laravel silently swaps the locale value into that other parameter.
   `abort(404)` isn't logged, so this fails silently. Hit on
   articles.show originally; documented in docs/CLAUDE.md Section 11.
2. **Livewire full-page components need `#[Layout(...)]`, never a
   hand-wrapped `<x-layouts.*>` tag around their own view** — wrapping
   causes "Multiple root elements detected" (a full HTML doc has more
   than one top-level node). Use `#[Layout('components.layouts.public'
   |'minimal')]` + `@push('head')` for SEO tags (see
   `components/layouts/public.blade.php`'s `@stack('head')`).
3. **`config/cache.php`'s `'serializable_classes' => false`** (Laravel
   12's new secure default) silently breaks unserializing ANY cached
   object (Eloquent models/collections/paginators) regardless of cache
   driver. If something introduces object caching again, check this
   config is `true` (already fixed) rather than reaching for a runtime
   `config()` override in a ServiceProvider — `CacheManager` reads it
   once via `?? null`, and `false` isn't `null`, so a runtime override
   set during `register()` doesn't take effect against the already
   file-configured `false` default.
4. **blade-icons registers its own `<x-icon>` tag** — collides with our
   shared component. `config/blade-icons.php`'s `default` is set to
   `null` to disable it; our `x-icon` component calls the `svg()`
   helper directly instead of `<x-dynamic-component>` (which doesn't
   trigger blade-icons' compile-time resolution).
5. **Mailables using `<x-mail::message>` must use `->markdown()`, never
   `->view()`** — Laravel only registers the `mail::` view namespace
   inside `Markdown::render()`, which only `->markdown()` calls.
6. **`@alpinejs/collapse` wasn't installed** despite `x-collapse` being
   used in `accordion.blade.php` from Part B onward — now installed and
   registered in `app.js` before `Alpine.start()`.

## Next up
- **Dashboard (C11-C16)** — not started. Needed for: "Continue Learning"
  destination (Course detail's enrolled-state button currently links to
  `#`), My Courses, lesson viewer, consultations list, certificates
  list, profile edit.
- **Auth Livewire conversion (C21)** — Breeze's stock Blade views still
  in place; convert to Livewire 4 + shared components.
- RTL and responsive-breakpoint verification (Section 26) — never done
  with actual rendering, only curl+grep. Worth a real check on `/ur/`
  and `/fa/` locales, and at 375/768/1280px.
- `Certificate` model's PDF generation (barryvdh/laravel-dompdf,
  Section 14) not wired yet — CertificateResource can revoke but
  nothing generates the actual PDF on course completion
  (`IssueCertificateListener` / `CourseCompleted` event, Part F, not
  built).
- `RecentActivityWidget` (Filament) explicitly skipped by the ops-agent,
  reads `activity_log` — pick up later if wanted.
