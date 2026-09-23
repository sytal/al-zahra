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
7. **`[x-cloak] { display: none !important; }` was never defined in
   app.css** — without it, `x-cloak` is a complete no-op (it's just a
   marker attribute Alpine removes after hydration; the actual hiding is
   the app's own CSS responsibility). Affected every x-cloak usage since
   Part B (mobile nav, dropdown, modal, accordion), not just the theme
   toggle that surfaced it (both sun/moon icons stayed visible at once).
   Now fixed in app.css. When debugging "element flashes visible" or
   "two conditionally-shown things both show", check this rule exists
   before assuming the Alpine logic itself is wrong.
8. **`<x-icon>` silently dropped every attribute except `class`** —
   icon.blade.php only ever read `$attributes->get('class')`; anything
   else passed to it (`x-show`, `x-cloak`, `x-bind:class`, etc.) was
   discarded before reaching the `svg()` helper call, so those
   directives never existed in the rendered HTML at all. This broke the
   theme toggle (both icons always visible — found via a screenshot
   after the [x-cloak] CSS fix alone didn't help) AND
   `accordion.blade.php`'s chevron rotation, silently, since Part B.
   Fixed by passing `$attributes->except('class')->getAttributes()`
   through to `svg()`'s third parameter (it explicitly supports this).
   When an Alpine directive on `<x-icon>` "does nothing," check the
   rendered HTML for whether the attribute even exists on the `<svg>`
   tag before debugging the Alpine expression itself.
9. **Don't assume an app bug when local manual testing fails after a
   `migrate:fresh --seed` re-run** — a manually-set tinker test password
   gets wiped by reseeding, and a stale login session then fails
   `Auth::attempt()` silently while the surrounding symptoms (auth
   middleware redirecting to `route('login')` without a locale, since
   URL generation for an unauthenticated redirect doesn't get
   `URL::defaults()`) look exactly like a real routing/session bug. Spent
   real effort chasing this as a framework issue before checking
   `Auth::attempt()` directly in tinker — check credentials first.
10. **`App\Models\User` never implemented `MustVerifyEmail`** — the
    import was commented out from the original Breeze scaffold and
    never revisited. Laravel's `EnsureEmailIsVerified` middleware
    checks `$user instanceof MustVerifyEmail` and silently passes
    through for anything that doesn't implement it, so the `verified`
    middleware on every dashboard route did nothing this whole time —
    no error, no hint, just a no-op check. The trait's methods
    (`hasVerifiedEmail()` etc.) were still callable throughout (inherited
    from `Illuminate\Foundation\Auth\User`), which is why nothing ever
    threw — only the interface (the actual thing middleware checks) was
    missing. Fixed when building VerifyEmailNotice for C21. If a
    "verified" or "email confirmation required" feature seems to not be
    enforcing anywhere, check the model implements the contract, not
    just that the trait methods exist.
11. **Never self-manage a separate Alpine instance alongside Livewire** —
    `app.js` had `import Alpine from 'alpinejs'; Alpine.start()`. Livewire
    bundles its own Alpine instance (via `@livewireScripts`), registers
    the Navigate/Morph plugins onto it, and calls `Alpine.start()` itself
    right after firing `livewire:init`. Two competing instances/starts
    meant Livewire's own Navigate plugin never attached correctly, so
    every `redirectRoute(..., navigate: true)` (e.g. after login/register)
    threw `Alpine.navigate is not a function` in the browser console and
    silently failed to redirect — this was the real cause behind
    "login not working" reports, not credentials/seeding. `curl` cannot
    catch this class of bug since it never executes client-side JS. Fix:
    never import/start Alpine yourself; register any plugins you need
    (e.g. `@alpinejs/collapse`) onto Livewire's instance via
    `document.addEventListener('livewire:init', () => window.Alpine.plugin(pluginName))`.
12. **A layout can have zero guest-facing auth links and nothing will
    error** — `public.blade.php`'s header had no Login/Register links at
    all since it was first built; there was no way for a guest to
    navigate to the login page from the public site. Nothing throws for
    this (routes existed, pages worked when hit directly), so it only
    surfaces via a live user actually looking for the button. When
    building any public layout with auth routes registered, explicitly
    check the header/footer for `@guest`/`@auth` (or `@else`) link blocks
    — don't assume they exist just because the routes and pages do.

- **Dashboard (C11-C16) — fully built**: sidebar layout, home (stats +
  continue-learning + recent consultations), My Courses (tabs, progress,
  certificate link), Lesson Viewer (two-column, curriculum sidebar,
  mark-complete), Consultations list (table + modal), Certificates list
  + download, Profile edit (avatar/basic-info/preferences/password, 4
  separate wire:submit cards). Course's "Continue Learning" button now
  goes to a real destination instead of `#`.
- **Certificate PDF generation wired end-to-end** (was listed as
  "not built" before this push): `CourseCompleted` event fires at 100%
  lesson progress, `IssueCertificateListener` generates a unique
  `AZ-{year}-{6digit}` code and a real dompdf-rendered PDF attached to
  the certificate_pdf media collection. Verified with a full real flow
  (enroll → complete 5 lessons → 100% → certificate + PDF on disk).
- **Global loading bar** (Section 9 requirement) — existed only as a
  concept before, now a real `<x-loading-bar>` in all 3 layouts.
- **Dark/light mode toggle** — CSS variables + `darkMode:'class'` existed
  since the very first color-system pass but had zero actual toggle
  mechanism. Built: `<x-theme-init-script>` (blocking, in `<head>`, avoids
  flash), `<x-theme-toggle>` (self-contained Alpine `x-data`, no global
  store — see bug #7 below for why), wired into all 3 layouts.
- **Language switcher URL bug fixed**: was building
  `/{new-locale}/{request()->path()}`, but `request()->path()` already
  includes the current locale segment, producing `/ur/en`-style
  duplicated URLs. Now strips the current locale segment first.
- **Missing placeholder images fixed**: `public/images/` never existed —
  every card's fallback image (`article/course/research/resource/avatar
  -placeholder.png`) 404'd since Part B. Added branded SVG placeholders,
  updated all 8 references from `.png` to `.svg`.

- **Auth Livewire conversion (C21) — done**: all 6 pages
  (Login/Register/ForgotPassword/ResetPassword/ConfirmPassword/
  VerifyEmailNotice) converted to Livewire under
  `app/Modules/User/Livewire/`, `components.layouts.minimal`, full SEO,
  i18n in all 5 locales. Removed the whole dead Breeze scaffold this
  superseded (old ProfileController + /profile routes + profile views —
  dashboard.profile.edit already replaced it; layouts/{app,guest,
  navigation}.blade.php + View\Components\{AppLayout,GuestLayout} —
  nothing referenced them anymore; the 7 old Auth/*Controller classes +
  LoginRequest; the stock root dashboard.blade.php placeholder).
  `MustVerifyEmail` is now properly implemented on the User model (was a
  silent no-op gap — see bug #9 below for detail).
- **Fixed dev login**: `UserSeeder` used a random password every seed
  run (unusable for repeat manual testing) — now fixed to `password` for
  all seeded accounts outside production (director/admin/editor/student
  — added the latter two so every role has a working login), random in
  production. Credentials after any `migrate:fresh --seed`:
  `director@alzahra.institute` / `password` (also admin@, editor@,
  student@ — same password, different role).

## Next up
- RTL and responsive-breakpoint verification (Section 26) — never done
  with actual rendering, only curl+grep. Worth a real check on `/ur/`
  and `/fa/` locales, and at 375/768/1280px.
- `RecentActivityWidget` (Filament) explicitly skipped by the ops-agent,
  reads `activity_log` — pick up later if wanted.
- Blueprint's out-of-scope list (Part G) is fully respected so far —
  no payments/forum/marketplace/mobile-app/comments/live-video built.
  Nothing else known to be missing from Parts A-F at this point; next
  session should re-audit against the full blueprint before assuming
  so, per the "full docs compliance" habit this project has needed
  enforced more than once.
