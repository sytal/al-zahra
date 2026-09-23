# Progress log (bullet points only)

- Initial commit created (Laravel skeleton, docs/).
- `.claude/` folder structure created: 10 agents, 9 skills, 4 rules files,
  memory files (this file + project-intro.md).
- docs/CLAUDE.md + docs/PROJECT-BLUEPRINT.md expanded: seeders/factories,
  mail/notifications, storage disk, env vars checklist, deferred-scope
  memory file, autonomous-memory-update rule.
- `.claude/skills/seeders/SKILL.md` added.
- Branch `phase-0-foundation` created; all Phase 0 packages installed and
  committed (2 commits: scaffolding+docs, package install).
- Filament admin panel installed; Breeze Blade stack installed (Livewire
  stack skipped — conflicts with Filament v5's Livewire ^4.1 requirement).
- Spatie packages (permission, medialibrary, activitylog, sitemap) +
  dompdf config/migrations published; all migrate cleanly on sqlite.
- Tailwind v3 wired with RTL + forms plugins, brand color variables,
  dark mode; AOS initialized in app.js.
- `directors` + `settings` migrations added and migrated. `HasUuid` trait
  + `Director`/`Setting` models added (app/Modules/Director,
  app/Modules/Setting).
- All 16 Part A tables now migrated (categories, articles/tags/pivot,
  research_papers, resources, courses/course_lessons,
  enrollments/lesson_progress, consultations, certificates,
  newsletter_subscribers, contact_messages) with models + enums under
  app/Modules/*/Models and app/Support/Enums.
- users table updated with uuid/preferred_locale/phone/is_active/
  last_login_at/soft-deletes; User model wired with HasUuid + HasRoles.
- Resource module built end-to-end (C7/C8): Repository/Service
  (recordDownload)/Policy, ResourceIndex Livewire, show+download routes
  (paid resources show "Coming soon", download redirects to media URL —
  disk-agnostic for local/s3), disclaimer for questionnaire/guide types,
  SEO+sitemap, ResourceSeeder (8 resources). Moved free/paid labels to
  common.php (shared with Course). Verified live incl. paid + disclaimer
  states.
- Research module built end-to-end (C5/C6): Repository/Policy (no
  Service — no business logic to hold), ResearchIndex Livewire,
  show route with all 6 blueprint sections, SEO+sitemap,
  ResearchPaperSeeder (6 papers). Verified live.
- Course module built end-to-end (C9/C10): Repository/Service/Policy
  (policy auto-discovered via Models->Policies convention, no manual
  Gate::policy() needed), CourseIndex Livewire, show+enroll routes,
  SEO+sitemap, CourseSeeder (3 courses x 5 lessons). Verified live +
  enroll()/isEnrolled() via tinker.
- Demo seeders added: CategorySeeder (16), TagSeeder (10), ArticleSeeder
  (15 mixed published/draft) — /en/articles now shows real cards.
- Fixed a real routing bug: controller methods on {locale}-group routes
  with another URI param must declare $locale explicitly, or Laravel
  swaps the locale value into that param. articles.show was 404ing
  silently (abort() isn't logged). Documented in docs/CLAUDE.md Section
  11 so it doesn't repeat for Course/Research/Resource modules.
- SEO (Section 12) completed for Article module: SeoSchema class (JSON-LD
  builders), $seo array pattern from controller, fixed broken hreflang
  (was pointing every locale at the same URL), sitemap:generate command
  scheduled daily. Was previously only half-done (meta tags inline in
  the view, no schema/sitemap) — user caught the gap by asking directly.
- Article module backend layer built (Repository+Interface, Service,
  Policy) and bound via new RepositoryServiceProvider. Controller only
  has public index/show — admin CRUD is Filament's job (Part D), so
  Store/UpdateArticleRequest + ArticleResource were dropped as unused.
- Locale-prefixed routing wired: {locale} route group (en/ur/hi/fa/
  ur-roman) + SetLocale middleware (sets app locale + URL::defaults).
  All existing routes (auth, dashboard, profile, articles) now live
  under /{locale}/... per blueprint Part E. Verified with route:list,
  route:cache, and live requests.
- Docs audit found 2 gaps in Part A models: added HasActivityLog trait
  (didn't exist) and wired HasMedia/InteractsWithMedia on all models with
  blueprint media collections. Caught a real bug along the way: Spatie
  activitylog's dontSubmitEmptyLogs() doesn't exist in the installed
  version — it's dontLogEmptyChanges(). Verified with migrate:fresh --seed.
- Removed all hardcoded Tailwind gray/indigo/red/green classes from
  Breeze's auth/nav/profile views and stock components — recolored to
  semantic tokens (text-ink, bg-surface, text-brand-primary, etc.).
- Essential seeders added: RolePermissionSeeder, UserSeeder,
  DirectorSeeder, SettingSeeder, wired into DatabaseSeeder. Verified with
  migrate:fresh --seed. Fixed a real bug: removed WithoutModelEvents from
  DatabaseSeeder since it silently disables HasUuid's creating() hook.
- Part B shared component library built (resources/views/components/*):
  button, icon, card, badge, form inputs, table, modal, dropdown,
  accordion, pagination, seo, language-switcher, footer, empty-state,
  breadcrumbs, progress-bar, stat-card, share-buttons, newsletter-form
  (stub), director-profile, instructor-card, detail-layout.
