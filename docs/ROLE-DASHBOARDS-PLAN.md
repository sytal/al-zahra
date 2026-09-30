# Role-based dashboard plan

Status: PLAN. Written from the actual current codebase (not assumed) — see
"What's already built" per role before "What's missing." Nothing here is
implemented until approved.

## The 4 roles and their real job

| Role | Real-world job | Where they should work day to day |
|---|---|---|
| Student | Takes courses, asks questions, downloads certificates | Public site → `/dashboard` (learner area) |
| Editor | Writes/publishes Articles, Research, Resources only | Admin panel → `/admin` (Content section only) |
| Admin | Runs the whole site day to day: content, consultations, contact, settings | Admin panel → `/admin` (everything) |
| Director | Owns the institute: same as Admin, plus is the instructor of courses | Admin panel → `/admin` (everything) |

## Part 1 — What's already built (verified against code, not assumed)

### Student dashboard — DONE, fully built
- Route group `/{locale}/dashboard/*`, `auth`+`verified` middleware.
- Home (`dashboard/index.blade.php`): greeting, stat cards (courses in
  progress / certificates / consultations), Continue Learning, Recent
  Consultations, Quick Actions. Just fixed for responsive overflow and
  name wrapping (commit `547c143`).
- My Courses, Lesson Viewer (mark-complete, certificate-earned banner),
  Consultations list + modal, Certificates list + download, Profile
  (photo/info/preferences/password, 4 separate forms).
- Backend: `DashboardController`, `App\Modules\Course\Livewire\*`,
  `App\Modules\Consultation\Livewire\DashboardConsultationIndex`,
  `App\Modules\Certificate\Livewire\DashboardCertificateIndex` — all
  scoped with `->where('user_id', Auth::id())`, verified in
  `docs/RBAC-AUDIT.md`.

### Admin panel (Director/Admin/Editor) — DONE, fully built
- Filament panel at `/admin`. `canAccessPanel()` allows only
  director/admin/editor (Student gets 403 — tested).
- 13 resources across 4 nav groups (Content, People, Engagement,
  Settings), each gated by its Policy + `shouldRegisterNavigation()` —
  Editor's sidebar now correctly shows only Content (just fixed,
  commit `75ea88f`).
- `ManageSettings` page (tabs: Brand, Contact, Home hero, Testimonials,
  Footer, Mission/Vision).
- Custom branded split-screen login, dark mode, admin locale middleware.
- Dashboard home widgets: `TotalArticlesWidget`, `TotalCoursesWidget`,
  `TotalEnrollmentsWidget`, `NewsletterSubscribersWidget`,
  `PendingConsultationsWidget`, `RecentActivityWidget`.
- Consultation reply flow: `EditConsultation` page shows the question
  (read-only) plus an answer field — Director/Admin can respond from here.

### RBAC enforcement — DONE, audited
See `docs/RBAC-AUDIT.md`: policies match permissions, nav visibility now
matches policy, student data is scoped, admin vs student UI are separate
code paths (different route prefix, different layout, different
framework).

## Part 2 — What's missing (the actual gaps, each with backend/frontend split)

### Gap 1 — Staff landing on the wrong (student) dashboard
**Symptom:** Director/Admin/Editor can log in at the public `/login` and
land on `/dashboard` — the exact same "My Courses / Ask a question"
screen built for students. Nothing routes them to `/admin`, and nothing
hides the student-only actions for them.
**Fix (backend only, small):** `DashboardController::index()` — if
`auth()->user()->hasAnyRole(['director','admin','editor'])`, redirect to
`/admin` instead of rendering the student view. One `if` + a role check,
no new routes.
**Why not just let them see both:** a Director is never "enrolled" in
their own courses, so `Course in progress / Continue Learning` is always
empty for them — showing it is actively confusing, not harmless.
**Open question for you:** should staff be ALLOWED to also browse/take
courses as a learner (self-enroll) sometimes? If yes, the redirect should
be a choice screen instead of automatic — flag this before I build it.

### Gap 2 — Editor's admin dashboard leaks numbers they can't act on
**Symptom:** `PendingConsultationsWidget` (count of pending consultations)
and `RecentActivityWidget` (site-wide activity log — includes Settings
changes, user edits, consultation replies) have no `canView()` check, so
Editor sees them on `/admin` even though Editor cannot open Consultations,
Users, or Settings at all (per the RBAC audit). It's not a security hole
(no click-through, since those resources are correctly hidden), but it's
inconsistent — Editor sees a number they can't investigate.
**Fix (backend only):** add `public static function canView(): bool` to
both widgets, returning `auth()->user()?->can('consultations.respond')`
and `auth()->user()?->can('settings.manage') || can('users.manage')`
respectively (or a simpler "not an editor-only account" check). Same
one-line pattern already used for resource nav visibility.

### Gap 3 — No distinct "first thing you see" per staff role
**Symptom:** Director, Admin and Editor all land on the identical
`/admin` dashboard grid (after Gap 2's fix, Editor just sees fewer
widgets — but nothing is *tailored* to "you're the content editor" vs
"you're the director").
**Decision needed from you, not yet built:**
| Option | What it means |
|---|---|
| A. Leave as one shared dashboard, widgets just filtered by permission (Gap 2's fix) | Simplest, consistent with "Admin and Director are identical" decision already made in the RBAC audit |
| B. Editor gets a dedicated lighter dashboard (e.g., "Your drafts", "Needs your attention: X unpublished articles") | More tailored, more work: 1 new Filament page + 1-2 new widgets scoped to `author_id = auth()->id()` |
| C. Director gets an extra widget Admin doesn't (e.g., "Courses you instruct", since Director is also the seeded instructor) | Small addition: 1 widget, `Course::where('instructor_id', auth()->id())->count()` |

I recommend **A now, C as a quick follow-up** (Director-as-instructor is
real data already in the schema, cheap to surface) and **B only if you
want Editor to feel like a distinct "workspace,"** since it's the most
engineering for the least functional gain right now.

### Gap 4 — Consultation reply UX is a generic edit form, not a reply flow
**Symptom:** `EditConsultation` is Filament's default "edit a record" page
with the question shown read-only and an answer textarea — functional,
but not phrased as "reply to this person," no indication of urgency
(how long it's been pending), no "mark answered" distinct action (saving
the form presumably also flips status — needs a look).
**Not a blocker**, but worth a small polish pass later: a custom page or
a `Actions\Action` button ("Send reply") instead of a bare edit form,
plus the `PendingConsultationsWidget` linking straight to the oldest
pending one.

### Gap 5 — No activity log entry differentiates "who did what" for staff accountability
`RecentActivityWidget` reads `activity_log` (spatie/laravel-activitylog),
which is already wired via `HasActivityLog` on models — so this exists
at the data level. Not a gap in data, just confirm it's actually causer
(`auth()->user()`)-populated for admin actions (content publish, settings
change) — worth a 5-minute spot check before relying on it, not a build
task.

## Part 3 — Recommended order of work

1. Gap 1 (staff redirect) — highest confusion, smallest fix.
2. Gap 2 (widget `canView()`) — small, consistent with work already done today.
3. Gap 3 option C (Director's "Courses you instruct" widget) — cheap, real data.
4. Gap 4 (consultation reply polish) — only if you want it now; not broken, just plain.
5. Gap 3 option B (dedicated Editor dashboard) — only on your explicit go-ahead, it's the one genuinely new screen in this whole plan.

Nothing above changes routes other pages depend on, touches student-facing
pages, or requires new database tables/migrations — everything is
Filament-config-level (widgets, one controller redirect) except option B,
which would need one new Filament Page class.
