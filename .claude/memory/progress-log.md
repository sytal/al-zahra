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
