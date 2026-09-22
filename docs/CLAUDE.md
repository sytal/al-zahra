# AL ZAHRA INSTITUTE — PROJECT CONSTITUTION (CLAUDE.md)

> This file is the single source of truth for how this project is built.
> Every session, every agent, every sub-agent MUST read this file first before
> touching any code. If a rule here conflicts with a "default" way of doing
> something in Laravel, THIS FILE WINS.

---

## 0. GOLDEN RULES (Read every session, never break)

1. **Token efficiency is priority #1.** Never read, open, or reprint files that
   are not part of the current task. Never re-explain things already defined
   in this file — just follow them silently. Never dump full file contents in
   chat if a targeted diff/edit is enough. Prefer `grep`/targeted search over
   reading whole directories. Do not re-read this file's full text again mid-session
   if it is already in context — refer to remembered rules.
2. **Delegate, don't do it all in one agent.** For any non-trivial phase,
   spin up specialized sub-agents (see Section 1) and run independent parts
   in PARALLEL, not sequentially. The root/orchestrator agent should mostly
   coordinate, not write every line itself.
3. **Reuse over rebuild.** Before creating any new component, trait, helper,
   enum, or service — search the codebase for an existing one that can be
   reused or extended. Never duplicate logic.
4. **One standard, one place.** Colors, icons, buttons, form flows — defined
   ONCE, imported everywhere. Never hardcode a value that already has a
   central definition.
5. **Every file goes in its module folder.** No loose files in root-level
   generic folders. See Section 4 for exact structure.
6. **No unnecessary comments, no unnecessary README spam, no explaining
   obvious code.** Code should be self-documenting via naming standards
   (Section 3).
7. **Always confirm plan → then execute.** For anything bigger than a single
   file edit, list the exact files you will touch/create BEFORE writing code,
   in a short bullet list (not verbose).

---

## 1. AGENT STRATEGY (`.claude/agents/`)

Create these reusable, project-wide sub-agents inside `.claude/agents/`. Each
agent file = one `.md` with YAML frontmatter (name, description, tools) as
per Claude Code's sub-agent spec. Keep each agent's job narrow.

| Agent | Responsibility |
|---|---|
| `architect` | Plans DB schema, routes, module structure before code is written. Never writes implementation code itself. |
| `migration-builder` | Creates migrations only. Follows Section 5 rules exactly. |
| `model-builder` | Creates Eloquent models, relationships, casts, UUID setup. |
| `backend-builder` | Controllers, Services, Repositories, Requests, Policies. |
| `frontend-builder` | Blade/Livewire components, following Section 8 design system. |
| `seo-agent` | Meta tags, sitemap entries, schema.org JSON-LD for any new public page. |
| `i18n-agent` | Adds/updates translation keys across all 5 locales whenever new UI text is introduced. |
| `security-auditor` | Reviews signed URLs, policies, mass-assignment, query safety after a feature is built. |
| `test-writer` | Writes Pest tests only for the feature just built (never full-suite by default). |
| `qa-runner` | Runs only the relevant test file(s)/lint for the changed module, not the whole suite. |

Rules for agents:
- Sub-agents may spawn their own sub-agents if a task naturally splits
  (e.g. `backend-builder` spawning one sub-agent per module for parallel work).
- Each agent must only load the files relevant to its own task (Rule 0.1).
- Agents must report back a short summary (files touched, not full diffs)
  unless the orchestrator explicitly asks for full code review.

---

## 2. `.claude/` FOLDER LAYOUT

```
.claude/
  CLAUDE.md              ← this file (or symlinked from root)
  agents/                ← agent definitions (Section 1)
  skills/
    migrations/SKILL.md
    models/SKILL.md
    controllers/SKILL.md
    livewire-components/SKILL.md
    forms/SKILL.md
    seo/SKILL.md
    i18n/SKILL.md
    security/SKILL.md
    naming/SKILL.md
  rules/
    folder-structure.md
    color-system.md
    response-standards.md
    error-codes.md
  memory/
    project-intro.md      ← Section 13, loaded at start of every new session
    progress-log.md       ← updated after each completed phase (short bullet log)
```

At the START of every new chat session, Claude must read:
`memory/project-intro.md` + `memory/progress-log.md` ONLY (not the whole repo)
to restore context cheaply.

Claude updates `memory/project-intro.md` (Current phase / Done so far / Next
up) and appends to `memory/progress-log.md` on its own, silently, whenever a
unit of work completes — never ask the user for permission first. Same for
`.claude/memory/deferred-scope.md`: log anything explicitly deferred there
without being asked.

---

## 3. GLOBAL NAMING STANDARDS

- **Files/classes:** PascalCase for classes (`ArticleController`), snake_case
  for migration files (`2025_01_01_000000_create_articles_table.php`),
  kebab-case for Blade/Livewire view files (`article-card.blade.php`).
- **Livewire components:** PascalCase class, kebab-case tag
  (`<livewire:articles.article-card />`).
- **Database tables:** plural snake_case (`articles`, `course_lessons`).
- **Database columns:** snake_case, boolean columns prefixed `is_`/`has_`
  (`is_published`, `has_certificate`).
- **Routes:** kebab-case URIs, dot-notation names matching folder structure
  (`Route::get('/articles/{article:slug}', ...)->name('articles.show')`).
- **Enums:** PascalCase class, UPPER_SNAKE cases, backed by string values
  (e.g. `enum UserRole: string { case DIRECTOR = 'director'; }`).
- **Form Requests:** `{Action}{Model}Request` (`StoreArticleRequest`,
  `UpdateArticleRequest`).
- **Services:** `{Model}Service` with single-responsibility public methods.
- **Repositories:** `{Model}Repository` implementing `{Model}RepositoryInterface`.
- **Policies:** `{Model}Policy`, standard Laravel method names only
  (`viewAny`, `view`, `create`, `update`, `delete`, `restore`).
- **Jobs:** `{Verb}{Noun}Job` (`SendCertificateEmailJob`).
- **Events/Listeners:** `{Noun}{PastTenseVerb}` event (`CourseCompleted`),
  `{Verb}{Noun}` listener (`IssueCertificate`).
- **Traits:** `Has{Capability}` (`HasUuid`, `HasActivityLog`, `HasTranslations`).
- **Helpers:** grouped by domain in `app/Support/{Domain}Helper.php` as static
  methods — never global functions in `helpers.php` unless truly generic
  (e.g. `format_price()`), and even then defined once in
  `app/Support/helpers.php`, autoloaded via composer `files` key.

---

## 4. FOLDER STRUCTURE (Module-based, not type-based at top level)

Every feature lives inside its own module folder under `app/Modules/{Module}/`.
Within each module, standard Laravel sub-folders are used as needed:

```
app/
  Modules/
    Article/
      Models/Article.php
      Http/Controllers/ArticleController.php
      Http/Requests/StoreArticleRequest.php
      Http/Requests/UpdateArticleRequest.php
      Http/Resources/ArticleResource.php
      Policies/ArticlePolicy.php
      Services/ArticleService.php
      Repositories/ArticleRepository.php
      Repositories/ArticleRepositoryInterface.php
      Livewire/ArticleList.php
      Livewire/ArticleCard.php
      database/migrations/  (or root migrations, see Section 5)
    Course/
      Models/ , Http/ , Services/ , Livewire/ ...
    Research/
    Resource/
    Consultation/
    User/
    Certificate/
  Support/
    helpers.php
    Traits/HasUuid.php
    Traits/HasActivityLog.php
    Enums/UserRole.php
    Enums/ActivityAction.php
resources/
  views/components/        ← shared Blade components (buttons, inputs, cards)
  views/livewire/          ← only if a livewire view isn't co-located
  lang/en/ , lang/ur/ , lang/hi/ , lang/fa/ , lang/ur-roman/
routes/
  web.php        ← includes route files per module
  modules/articles.php
  modules/courses.php
  modules/research.php
  admin.php
```

Rule: a Controller/Model/Service/Migration/Test ALWAYS lives in its module
folder. Only truly shared, cross-module code goes in `app/Support/`.

---

## 5. MIGRATION STANDARDS

- File name: `{timestamp}_create_{table}_table.php` or
  `{timestamp}_add_{column}_to_{table}_table.php` for alterations.
- Every table: `id()` (bigint internal PK) **plus** a `uuid` column
  (`$table->uuid('uuid')->unique()`) used for ALL public-facing/URL
  references. Internal `id` is never exposed in routes or APIs.
- Every table: `soft_deletes()` unless explicitly a pure pivot/log table.
- Every table: `timestamps()`.
- Foreign keys: `foreignId('user_id')->constrained()->cascadeOnDelete()`
  (or `nullOnDelete()` where deletion shouldn't cascade destructively —
  decide per relationship and note it as a migration comment... actually no
  comments, name the column clearly instead).
- Use `$table->string('slug')->unique()` for any publicly browsable content
  (articles, courses, research, resources).
- Translatable text fields (title, description, body) are stored as
  `json` columns when using Spatie Translatable (single column holds all
  locales) — column named without locale suffix (e.g. `title`, not
  `title_en`).
- Enum-like states use a `string` column + PHP backed enum cast, never MySQL
  native `ENUM` type (harder to alter later).
- Pivot tables: singular_singular alphabetical order
  (`course_user`, `article_tag`).

---

## 6. MODEL STANDARDS

- Every model uses `HasUuid` trait (auto-generates UUID on creating(), and
  `getRouteKeyName()` returns `uuid` — but public slugs use `slug` for
  content models, `uuid` for transactional/private models like
  Consultation/Order).
- `$fillable` explicitly listed — never `$guarded = []`.
- Relationships: always return type-hinted (`public function author():
  BelongsTo`).
- Every model that has admin-visible state changes uses `HasActivityLog`
  trait → logs at USER-READABLE level ("Article 'X' was published by
  Dr. Arrabah" not "UPDATE articles SET status..."). Backed by
  `spatie/laravel-activitylog`, with custom `getDescriptionForEvent()`
  per model to keep logs human-readable, not developer-level.
- Casts declared via `casts()` method (Laravel 11+ style), not `$casts`
  property.
- Translatable models implement `HasTranslations` (Spatie) and declare
  `public $translatable = ['title', 'description', ...]`.

---

## 7. ROUTES, CONTROLLERS, SERVICES — REQUEST FLOW STANDARD

Universal flow for every feature (e.g. "create article"):

```
routes/modules/articles.php
   Route::middleware(['auth','role:director,admin,editor'])->group(function () {
       Route::get('/admin/articles', [ArticleController::class,'index'])->name('admin.articles.index');
       Route::get('/admin/articles/create', [ArticleController::class,'create'])->name('admin.articles.create');
       Route::post('/admin/articles', [ArticleController::class,'store'])->name('admin.articles.store');
       ...
   });
   Route::get('/articles', [ArticleController::class,'publicIndex'])->name('articles.index');
   Route::get('/articles/{article:slug}', [ArticleController::class,'show'])->name('articles.show');
```

- **Controller**: thin. Only (1) authorize, (2) call Service, (3) return
  view/redirect. No business logic, no direct DB queries in controllers.
- **Form Request**: validates + authorizes (`authorize()` checks Policy).
- **Service**: contains the actual business logic, calls Repository for
  data access. Method names are verbs (`ArticleService::publish(Article
  $article)`).
- **Repository**: only place raw Eloquent query building happens (besides
  simple relationship calls in models). Interface + implementation, bound
  in a `RepositoryServiceProvider`.
- **Policy**: gate for every action; checked in Controller via
  `$this->authorize()` and mirrored in Blade with `@can`.
- **Resource** (if API/Livewire needs JSON shape): transforms model →
  array, hides internal `id`, exposes `uuid`/`slug`.

Standard controller function set per resource: `index, create, store, show,
edit, update, destroy` (+ `publish`/`unpublish` custom actions only where
relevant, as a separate route+method, never overload `update`).

---

## 8. SHARED DESIGN SYSTEM (Colors, Components — "define once")

### 8.1 Colors
Single source: `resources/css/app.css` `@theme` block (Tailwind v4) or
`tailwind.config.js` `theme.extend.colors` (Tailwind v3) — whichever the
installed version uses. NEVER hardcode a hex code inside a Blade file.

```css
--color-brand-primary: #0F766E;   /* deep teal */
--color-brand-secondary: #D4A24C; /* warm gold */
--color-ink: #1E293B;             /* headings/text */
--color-surface: #F8FAFC;         /* light bg */
--color-success: #22C55E;
--color-danger: #F87171;
```
Dark mode overrides declared under `.dark` class variant, same variable
names, different values. Every component (button, card, badge) references
`bg-brand-primary`, `text-ink`, etc. — never raw hex.

### 8.2 One component per UI pattern
- `resources/views/components/button.blade.php` — every button in the
  entire project (edit, save, delete, submit) uses this ONE component with
  props (`variant`, `icon`, `size`). Changing this file changes every
  button everywhere.
- `resources/views/components/icon.blade.php` — icon wrapper. **Do NOT use
  Lucide.** Use **Heroicons** (`blade-ui-kit/blade-heroicons` package),
  referenced only through this wrapper component so the icon set can be
  swapped centrally later.
- `resources/views/components/card.blade.php`
- `resources/views/components/input.blade.php`, `select.blade.php`,
  `checkbox.blade.php`, `radio.blade.php`, `textarea.blade.php` — all share
  one base styling partial for border/focus/error states so validation
  errors look identical everywhere.
- `resources/views/components/table.blade.php` — generic data table with
  slots for columns.
- `resources/views/components/badge.blade.php`, `modal.blade.php`,
  `dropdown.blade.php`, `pagination.blade.php` (or Livewire's default,
  themed once).
- Layout shells: `components/layouts/public.blade.php`,
  `components/layouts/dashboard.blade.php`, `components/layouts/admin.blade.php`
  (Filament handles its own, themed via Filament's theme config, same color
  variables).

### 8.3 Sourcing UI (free components)
When pulling a design block from a free library, pull ONLY the HTML/Tailwind
markup and re-wire it to use our color variables + our Blade components
(don't keep the source's hardcoded colors/icons). Approved free sources:
- https://github.com/markmead/hyperui
- https://github.com/themesberg/flowbite
- https://github.com/htmlstreamofficial/preline
- https://www.tailwindawesome.com/?price=free
Pick ONE primary source per component type to keep visual consistency
(e.g. all hero/nav/footer from HyperUI, don't mix 3 different libraries'
styles on one page).

---

## 9. NO-RELOAD NAVIGATION STANDARD

- Livewire 3 with `wire:navigate` on every internal `<a>` tag (public site
  AND dashboard AND admin where applicable).
- Full-page Livewire components for routes; small reusable pieces as
  Livewire components too, or Blade components with Alpine for pure
  presentational state (dropdown open/close etc. — Alpine only, no
  Livewire round-trip for trivial UI state).
- Global loading indicator via Livewire's built-in `wire:loading` on a top
  progress bar in the main layout — one definition, used everywhere.

---

## 10. ANIMATIONS/TRANSITIONS STANDARD

- Scroll-reveal: **AOS.js**, initialized once in main layout
  (`data-aos="fade-up"` etc. as attributes, no per-page JS).
- Micro-interactions (hover, button press): Tailwind's `transition`,
  `duration-200`, `ease-in-out` utility classes only — no custom JS unless
  Alpine `x-transition` is needed for show/hide.
- Page-level transitions on `wire:navigate`: use Livewire's built-in
  progress bar; do not build a custom page-transition system (keeps token
  cost and complexity low).
- Never use animation libraries beyond AOS + Tailwind + Alpine unless a
  specific complex interaction genuinely requires GSAP — and if so, load
  GSAP core only (free), not premium plugins.

---

## 11. MULTI-LANGUAGE / LOCALIZATION STANDARD

Languages: English (`en`), Urdu (`ur`), Hindi (`hi`), Farsi (`fa`), Roman
Urdu (`ur-roman` — custom locale, not a real ISO code but treated as one).

- Package: Laravel's native `__()` / `lang/{locale}/*.php` for static UI
  text (buttons, labels, nav).
- Package: **`spatie/laravel-translatable`** for database content
  (articles, courses, research — anything admin-authored).
- Locale switch stored in session + user profile (`preferred_locale`
  column on `users` table) so logged-in users persist their choice.
- RTL languages (`ur`, `fa`) trigger `dir="rtl"` on `<html>`, layout uses
  **`tailwindcss-rtl`** plugin classes (`ps-4` instead of `pl-4` etc.)
  everywhere — never hardcode `ml-`/`pl-`/`text-left` directional
  utilities; always use logical properties.
- URL structure: locale prefix (`/en/articles`, `/ur/articles`) via route
  group middleware `SetLocale`.
- Every new UI string added by any agent MUST be added to all 5
  `lang/{locale}/*.php` files in the same commit (i18n-agent's job,
  Section 1) — English + Roman Urdu content can be auto-translated by
  Claude directly; Urdu/Hindi/Farsi should be flagged as
  `// TODO: verify native translation` for wife/native speaker review if
  Claude is not fully confident, keeping a placeholder machine translation
  in the meantime — never leave a key blank.

---

## 12. SEO STANDARD

- Package: **`spatie/laravel-sitemap`** — auto-generates `/sitemap.xml`
  plus segmented sitemaps (`sitemap-articles.xml` etc.) via a scheduled
  command (`php artisan sitemap:generate`) run daily via Laravel's
  scheduler (cron: `* * * * * php artisan schedule:run` — the ONE cron
  entry the whole project needs; all recurring tasks go through Laravel's
  scheduler, not separate cron lines).
- Every public page defines: `title`, `meta_description`, `canonical_url`,
  `og_image` via a shared `<x-seo :title="" :description="" :image="" />`
  Blade component placed once in `<head>` of `layouts/public.blade.php`,
  fed per-page via a `$seo` array passed from the controller/Livewire
  component — never inline `<meta>` tags scattered per view.
- JSON-LD structured data: a `SeoSchema` support class
  (`app/Support/SeoSchema.php`) with static builders (`::article()`,
  `::course()`, `::person()`, `::organization()`, `::breadcrumb()`) —
  reused, not hand-written per page.
- Multi-language SEO: `hreflang` alternate links auto-generated in the same
  `<x-seo>` component looping over the 5 locales.

---

## 13. CACHE / PERFORMANCE STANDARD

- Redis for: session driver, cache driver, queue driver (all three, one
  Redis connection, separate DB indexes: `0` cache, `1` sessions, `2`
  queues).
- Cache keys follow pattern `{module}:{type}:{identifier}` e.g.
  `articles:list:page-1`, `article:slug:how-brain-works`. TTL: 1 hour for
  lists, 24h for single published content, invalidated explicitly on
  save/publish (Model `saved`/`deleted` events call a `CacheService::
  forget()` helper — one central place that knows which keys a model
  touches).
- Eager-load relationships always (`with([...])`) to avoid N+1 — Repository
  layer is the only place allowed to add `->with()`.
- Images: stored via **`spatie/laravel-medialibrary`**, auto-converted to
  WebP + responsive sizes (conversions defined once per model in
  `registerMediaConversions()`), served from `storage` symlink behind
  Cloudflare CDN (cache-everything page rule for `/storage/*`).
- Laravel Octane considered only post-launch if traffic requires it — not
  part of Phase 1.

---

## 14. MEDIA / FILES / CERTIFICATES

- All uploads (images, PDFs, research papers) go through
  `spatie/laravel-medialibrary`, attached to their owning model via a
  `media` collection name (`'featured_image'`, `'attachments'`,
  `'certificate'`).
- PDF/certificate generation: **`barryvdh/laravel-dompdf`** (free), template
  Blade view per certificate type, auto-saved into the model's `media`
  collection AND a unique verification code (UUID) stored on a
  `certificates` table for the public `/certificates/verify` lookup —
  never store raw binary in the database, always filesystem/S3-style disk
  reference through Media Library.
- Disk: local/dev uses `FILESYSTEM_DISK=public` (`php artisan
  storage:link`); production uses `FILESYSTEM_DISK=s3` pointed at any
  S3-compatible provider (e.g. Cloudflare R2 free tier). Disk name is
  never hardcoded anywhere in code — always read from the `FILESYSTEM_DISK`
  env var via config, so switching environments requires no code change.

---

## 15. ROLES & PERMISSIONS

- Package: **`spatie/laravel-permission`**.
- Roles (seeded): `director` (full access — wife's account), `admin`,
  `editor` (content only), `student` (default registered user).
- Permissions grouped by module (`articles.create`, `articles.publish`,
  `courses.manage`, `consultations.respond`, etc.) — never check
  `hasRole()` directly in business logic, always check a specific
  permission (`hasPermissionTo()`), roles just bundle permissions. Defined
  once in a `RolePermissionSeeder`.

---

## 16. VALIDATION / FORMS STANDARD

- Every form: dedicated Form Request class, never inline `$request->
  validate()` in controllers.
- Every input component (Section 8.2) shows validation errors in the same
  visual style automatically via the shared base partial — no per-form
  custom error styling.
- Every form submit uses Livewire (`wire:submit`) → no full page reload,
  loading state shown via the shared button component's `wire:loading`
  slot.

---

## 17. SENSITIVE / SIGNED LINKS STANDARD

- Any link exposing personal data without full login (e.g. certificate
  view, consultation confirmation, unsubscribe) uses Laravel **Signed
  URLs** (`URL::temporarySignedRoute()`), expiry set per use case (e.g.
  24h for consultation confirmations, no-expiry-but-revocable for
  certificate verification since that's meant to be public/permanent but
  only exposes non-sensitive verification data).
- Any such route validates signature via `signed` middleware; controller
  additionally checks the resource's owner/UUID matches before showing
  data — signature alone is not treated as sufficient authorization.

---

## 18. SECURITY STANDARD (applies everywhere, always)

- Mass assignment: `$fillable` always explicit (Section 6).
- All Eloquent queries via Query Builder/Eloquent (parameter binding) —
  raw SQL is forbidden unless absolutely necessary, and if used, always via
  `DB::raw()` with bindings, never string-concatenated values.
- CSRF: Laravel default, never disabled.
- Rate limiting: `throttle` middleware on all public forms (contact,
  consultation request, login, register) — named limiter per form type
  defined once in `RouteServiceProvider`.
- File uploads: mime-type + max-size validated in the Form Request, never
  trust client-provided extension.
- All public IDs in URLs are UUIDs or slugs, never sequential integer IDs
  (Section 5/6).
- `APP_DEBUG=false` in production, always.
- Policies enforced server-side even if UI already hides the action
  (`@can` is UX only, never the real gate).

---

## 19. HTTP RESPONSE / ERROR STANDARDS

- API/JSON responses (if any AJAX/Livewire error payloads needed) follow:
  `{ "success": bool, "message": string, "data": {...} }`.
- Custom error views: `resources/views/errors/404.blade.php`,
  `419.blade.php`, `403.blade.php`, `500.blade.php` — all extending the
  public layout, branded, not Laravel defaults.
- `419` (expired page/session) shows a friendly "session expired, please
  refresh" message with a refresh button — relevant since no-reload nav
  means users may sit on a page a long time.
- Exceptions: custom exception classes per module where meaningful
  (`app/Modules/{Module}/Exceptions/`), caught centrally in
  `bootstrap/app.php` exception handler, logged via `HasActivityLog`-style
  human-readable entries for admin-relevant failures (e.g. failed
  consultation booking).

---

## 20. MIDDLEWARE STANDARD

- Named, single-purpose middleware in `app/Http/Middleware/`:
  `SetLocale`, `EnsureRole` (thin wrapper around Spatie's), `LogActivity`
  (optional, for admin routes).
- Route-group middleware stacks defined once per area (`public`,
  `auth-dashboard`, `admin`) in `RouteServiceProvider`/route files —
  never repeat the same middleware array inline across many route files.

---

## 21. QUEUES / JOBS / SCHEDULED TASKS

- Anything involving external I/O with latency (emails, certificate PDF
  generation, notifications) is a Queued Job (`ShouldQueue`), driver =
  Redis (Section 13).
- Queue names: `default`, `emails`, `media` — jobs dispatched to the
  relevant named queue via `->onQueue()`.
- Scheduled/recurring tasks (sitemap regen, digest emails, cleanup of
  expired signed-link logs) registered in Laravel's `Schedule` facade
  (routes/console.php or a `Console/Kernel`-equivalent) — the SINGLE cron
  entry mentioned in Section 12 runs all of them.

### 21.1 Mail & Notifications

- Mailable classes: `app/Modules/{Module}/Mail/{Purpose}Mail.php` (e.g.
  `app/Modules/Consultation/Mail/ConsultationAnsweredMail.php`).
- A Job only dispatches/queues — it never builds email content itself. The
  Job's `handle()` sends the Mailable (`Mail::to(...)->send(new
  {Purpose}Mail(...))`); all subject/body/markup lives in the Mailable
  class + its Blade view.
- Notification classes (for future in-app notifications), same module
  placement pattern: `app/Modules/{Module}/Notifications/{Purpose}Notification.php`.

---

## 22. TESTING STANDARD

- Framework: **Pest** (`pestphp/pest`), Laravel plugin.
- Test file lives inside the module: `tests/Feature/Modules/Article/
  ArticleCreationTest.php` mirroring the module structure.
- After finishing a feature, `test-writer` agent writes tests ONLY for that
  feature; `qa-runner` runs ONLY that test file (`php artisan test
  --filter=ArticleCreationTest`), not the entire suite, to save tokens/time.
  Full-suite run happens only at the end of a Phase, once, manually
  triggered.
- Minimum coverage per feature: happy path + one authorization-denied case
  + one validation-failure case.

---

## 22B. SEEDERS & FACTORIES STANDARD

- Seeder naming: `{Model}Seeder`, living in root `database/seeders/`
  (Laravel's official convention — module-splitting seeders breaks
  artisan's seeder auto-discovery/ordering, so this is the one place we
  intentionally deviate from the module-folder rule in Section 4).
- `DatabaseSeeder.php` calls seeders in this exact order (dependency
  order — later seeders reference earlier ones):
  ```
  RolePermissionSeeder -> UserSeeder -> DirectorSeeder -> CategorySeeder ->
  TagSeeder -> ArticleSeeder -> ResearchPaperSeeder -> ResourceSeeder ->
  CourseSeeder (creates lessons too) -> SettingSeeder
  ```
- Two distinct kinds of seeder, never mixed in one class:
  - **Essential** (`RolePermissionSeeder`, `SettingSeeder`,
    `DirectorSeeder`, `UserSeeder` for the director's own account) — these
    run in production too, required for the app to function.
  - **Demo** (`ArticleSeeder`, `ResearchPaperSeeder`, `ResourceSeeder`,
    `CourseSeeder`, `CategorySeeder`/`TagSeeder` sample rows) — fake
    content for local/staging only, excluded from the production seed
    command. `DatabaseSeeder` gates demo seeders behind
    `if (! app()->isProduction())`.
- Factories live in root `database/factories/` (Laravel default) — never
  inside a module folder, so Laravel's factory auto-discovery
  (`Model::factory()`) keeps working without manual registration.
- Factory naming: `{Model}Factory`. Use realistic Urdu/English mixed fake
  data (e.g. Faker's `ur_PK`/custom word lists for names/titles) so the dev
  environment's content looks close to real, not generic lorem ipsum.

---

## 23. VERSION CONTROL / GIT STANDARD

- One initial commit right after `.claude/` setup + package installation
  (Section 24) — "Initial project setup".
- Branch per Phase (from the phase roadmap given separately), e.g.
  `phase-1-foundation`, `phase-2-content`, named exactly after the phase.
- Within a phase branch, commit after each logically complete unit of work
  (e.g. "Add Article module migrations+model", "Add Article admin CRUD")
  — small, frequent, auto-committed by Claude once that unit is verified
  working, no need to ask permission each time.
- Merge to `main` happens manually by the user after reviewing a phase (not
  auto-merged by Claude) — Claude opens the branch, works, and tells the
  user it's ready for review/merge.
- `.gitignore` standard Laravel + `.env`, `node_modules`, `vendor`,
  `storage/*.key` etc. (default Laravel `.gitignore` is sufficient, verify
  once at setup).

---

## 24. PACKAGE INSTALLATION STANDARD

Install ALL known-needed packages in ONE batch during Phase 0 setup (not
piecemeal later, to minimize repeated composer/npm resolution overhead):

```
composer require laravel/breeze livewire/livewire spatie/laravel-permission \
  spatie/laravel-translatable spatie/laravel-medialibrary spatie/laravel-sitemap \
  spatie/laravel-activitylog barryvdh/laravel-dompdf blade-ui-kit/blade-heroicons \
  pestphp/pest pestphp/pest-plugin-laravel --dev filament/filament

npm install -D tailwindcss @tailwindcss/forms tailwindcss-rtl aos
```

(Exact final list confirmed by `architect` agent at Phase 0 kickoff against
the phase plan — but the philosophy is: decide the full list up front, run
one install pass.)

---

## 25. MEMORY FILE (`memory/project-intro.md`) — TEMPLATE

This file must be created at Phase 0 and kept short (token-cheap). Update
`memory/progress-log.md` (bullet points only, no prose) after every
completed unit of work, so a brand-new session can catch up in <200 tokens.

```
# Al Zahra Institute — Project Memory

## What this is
Online institute platform for Dr. [Wife's Name] (Neurolinguist/Researcher),
brand name "Al Zahra Institute". Modules: Articles, Research, Courses,
Resources, Consultations, Certificates. No payment gateway yet.

## Stack
Laravel + Livewire (wire:navigate, no page reloads) + Filament (admin) +
MySQL + Redis + Tailwind. 5 locales: en, ur, hi, fa, ur-roman. UUID public
IDs everywhere. See CLAUDE.md for full standards — do not repeat them here,
just follow them.

## Current phase
[updated live]

## Done so far
[bullet list, updated live — short]

## Next up
[bullet list]
```

---

## 26. DASHBOARD / WEBSITE UI-SPECIFIC RULES

- **Public website pages** (Home, About, Articles, Research, Courses,
  Resources, Contact): content-first, generous whitespace, one hero per
  page max, brand colors from Section 8.1, AOS fade-up on section entry.
- **Dashboard (student/user)**: sidebar navigation (persistent, no reload
  on switch via `wire:navigate`), card-based widgets for progress/courses,
  data tables (shared `<x-table>`) for orders/consultations history.
- **Admin (Filament)**: use Filament's own resource/widget system,
  restyled only via the shared color CSS variables (Filament supports
  theme customization) — do not rebuild Filament's UI from scratch.
- **"Meet the Director" / wife's intro block**: one reusable Blade
  component `<x-director-profile />` (photo, name, title, short bio,
  credentials list) used on Home (short version via prop `:compact="true"`)
  and About (full version) — content pulled from a single `directors`
  table/record, not duplicated as static text in two views.
- **Detail pages** (article/{slug}, course/{slug}, research/{slug}): shared
  `<x-detail-layout>` component (title, meta row, share buttons, related
  items section) wrapping module-specific body content — consistent
  detail-page anatomy across all content types.
- **Responsive standard**: mobile-first Tailwind breakpoints
  (`sm/md/lg/xl`), every shared component tested at 375px, 768px, 1280px;
  sidebar collapses to a bottom-sheet/hamburger below `md`.
- **Footer**: one shared `<x-footer>` component site-wide (public +
  dashboard), links pulled from a `footer_links` config or small DB table
  so wife can edit them via admin without a code change.

---

## 27. HOW TO ADD A NEW "THING" (checklist Claude follows silently)

When asked to add a new feature/module, follow this exact order, using the
relevant sub-agent per step, minimal file reads:
1. `architect`: confirm module name, table(s), route names (short plan).
2. `migration-builder`: migration(s) per Section 5.
3. `model-builder`: model + relationships + traits per Section 6.
4. `backend-builder`: Request → Policy → Repository → Service → Controller
   → routes, per Section 7.
5. `frontend-builder`: Livewire component(s) reusing shared Blade
   components from Section 8, per Sections 9–10.
6. `i18n-agent`: add any new UI strings to all 5 locale files.
7. `seo-agent`: if public-facing, add `$seo` data + sitemap entry.
8. `test-writer` + `qa-runner`: test just this feature.
9. Update `memory/progress-log.md` with one bullet line.
10. Commit (Section 23).

No step is skipped, but each step touches ONLY the files it needs.

---

## 28. ENVIRONMENT VARIABLES CHECKLIST

Every `.env` (and `.env.example`) must define these — never hardcode any of
them in code, always read via `config()`:

```
APP_NAME=Al Zahra Institute
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_DEBUG=false        # true only in local

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"

FILESYSTEM_DISK=public   # local/dev; 's3' in production (Section 14)
AWS_ACCESS_KEY_ID=       # or R2 equivalent, only needed when FILESYSTEM_DISK=s3
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
AWS_ENDPOINT=            # Cloudflare R2 endpoint or other S3-compatible URL
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Out of scope for now (add post-launch, not part of current phases): backup
strategy, health-check endpoint, CI/CD pipeline.
