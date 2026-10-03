# Course Builder — Developer Implementation Plan

Audience: developer (this doc, unlike docs/COURSE-BUILDER-PLAN.md, assumes
Laravel/Filament/Livewire knowledge). Source of truth for WHAT to build is
docs/COURSE-BUILDER-PLAN.md (user-journey, all decisions confirmed) — this
doc is HOW, in phases, so work can land in small steady steps.

Branch: `course-builder-dev`. Follow docs/CLAUDE.md throughout (module
structure, i18n alongside every feature, HasActivityLog on every new
model, CacheService for public list/detail reads, UI quality gate from
Section 29). Non-tech admin rule (binding on every form built below):
**no slug field, no icon/URL text field, anywhere.** Slugs
auto-generate from the title (existing `Str::slug()` pattern already used
on Article/Course). Icons are a fixed `Select` of heroicon names with a
preview, never a free-text input.

## Packages — reuse what's already installed, add nothing unless justified

Already in `composer.json` and sufficient for 100% of this feature:
`filament/filament` (reorderable repeaters, file uploads, rich editor —
covers quiz builder, module/block reordering, assignment file uploads),
`spatie/laravel-medialibrary` (assignment submissions, resource files),
`spatie/laravel-permission` (role/permission extension), `spatie/
laravel-activitylog` (every model below), `spatie/laravel-translatable`
(module/block titles, 5 locales), `livewire/livewire` (student-facing
pages), Laravel's own notification system (database + mail channels,
already wired — `MAIL_MAILER` configured this session).

**No new package needed.** Specifically rejected: a dedicated quiz-builder
package (Filament's `Repeater` + `Radio`/`Checkbox` already builds this
in ~80 lines, no dependency risk), a drag-and-drop JS library (Filament's
`Repeater::reorderable()` and Blade `x-collapse`/Alpine already cover
student-side curriculum reordering display), a ticketing package (the
whole feature is 2 tables and a few Livewire pages — a package would cost
more to configure/theme than to write). This keeps the stack "steady" —
every tool here is already proven in this codebase.

## Phase 0 — Schema foundation (migrations only, no UI)

New tables (module prefix in parentheses = where the model lives):

```
course_modules (Course)
  id, uuid, course_id (fk), title (json/translatable), description (json, nullable),
  sort_order (uint), timestamps, softDeletes

course_blocks (Course)
  id, uuid, course_module_id (fk), type (string: reading|practical_quiz|case_study|
    research_reading|discussion|graded_quiz|research_paper|case_analysis|
    assignment|research_activity),
  title (json), is_preview (bool default false), sort_order (uint),
  estimated_minutes (uint nullable),
  -- type-specific data lives in `content` (json) so block types don't each
  -- need their own table; keeps the schema small and the Filament form
  -- switches fields based on `type` (Filament Schema `->live()` + match())
  content (json),  -- shape documented per type in Phase 2
  timestamps, softDeletes

course_block_progress (Course)
  id, enrollment_id (fk), course_block_id (fk), status (string: locked|
  unlocked|done), completed_at (nullable), -- quiz score / assignment status
  -- stored in `data` json: {score, attempts, submission_media_id, mark}
  data (json nullable), timestamps
  unique(enrollment_id, course_block_id)

course_discussion_replies (Course)
  id, course_block_id (fk), user_id (fk, the student), author_id (fk, who wrote
  this specific reply - student or admin), body (text), timestamps

course_batches (Course)
  id, uuid, course_id (fk), label (string, e.g. "Batch 1"), starts_at (date),
  ends_at (date nullable), seats (uint), timestamps, softDeletes

course_batch_enrollments (Course)
  id, course_batch_id (fk), enrollment_id (fk, unique), roll_number (string,
  unique per batch - see Phase 4), status (string: enrolled|waitlisted),
  waitlist_position (uint nullable), timestamps
```

Changes to existing tables:
- `enrollments`: add `course_batch_id` (fk, nullable — self-paced courses
  have none), add `left_at` (timestamp nullable, for voluntary leave),
  add `final_score` (decimal nullable, computed on final submission).
- `course_lessons` / old flat-lesson flow: **kept as-is, not deleted.**
  New courses use modules+blocks; nothing breaks for existing data this
  session (the 3 demo courses get re-seeded under the new structure per
  Phase 6 — the old table can be dropped in a later cleanup phase once
  confirmed nothing references it, not part of this plan).

Every new model gets `HasActivityLog` (docs/CLAUDE.md pattern, already
used 16x in this codebase) from the moment it's created — not bolted on
later.

## Phase 1 — Models + policies

One model per table above, in `app/Modules/Course/Models/`. Key methods:
- `Course::modules()` hasMany, ordered by `sort_order`.
- `CourseModule::blocks()` hasMany, ordered.
- `CourseBlock::progressFor(Enrollment $e): ?CourseBlockProgress`.
- `Enrollment::activeCourseCount(User $user): int` static helper — powers
  the 2-course cap (Phase 5).
- Policies: `CourseModulePolicy`, `CourseBlockPolicy` mirror `CoursePolicy`
  (same `courses.manage` permission — modules/blocks are part of course
  authorship, no new permission needed). `CourseBatchPolicy` same.

## Phase 2 — Admin builder (Filament)

Extend `CourseResource` with 3 relation managers / a wizard-style Filament
form, per docs/COURSE-BUILDER-PLAN.md Part A:

1. **Introduction step**: 4 `Section`s on the existing course form —
   About (existing `full_description` RichEditor, unchanged), Learning
   Objectives (existing repeater, unchanged), Prerequisites (new
   `Repeater` of plain text lines), Course Resources (new `Repeater`:
   label + `Select` of kind[file|link|note] + conditional `FileUpload`/
   `TextInput` based on kind — Filament's `->live()` switches the field).
2. **Modules step**: a `Repeater` (or Filament's native nested
   `RelationManager` with `reorderable()`) over `course_modules`, each
   row expandable into its own blocks `Repeater`.
3. **Block editor**: one `Repeater` item = one block. `type` is a
   `Select` with the 10 options (label + icon via
   `Select::make('type')->options([...])->native(false)` with heroicon
   prefixes — no icon-url input, the icon is chosen BY the type, not
   entered). Below the type select, a `match($type)` in the Schema
   closure swaps in the right fields:
   - `reading`/`research_reading`: one `RichEditor`.
   - `practical_quiz`/`graded_quiz`: nested `Repeater` of questions, each
     a `TextInput` (question) + nested `Repeater` of options (`TextInput`
     + `Checkbox` "correct") + `Textarea` (explanation, optional). Graded
     quiz additionally gets `TextInput::make('pass_percent')->default(70)`.
   - `case_study`/`research_paper`/`case_analysis`: `RichEditor` (write-up)
     + `Repeater` of links (`TextInput` url + `TextInput` reference,
     reference `->required()` — this is the "no save without citation"
     guardrail from the plan, enforced via Filament field validation, not
     custom JS).
   - `discussion`: one `Textarea` (the admin's opening prompt).
   - `assignment`: `RichEditor` (instructions) + `FileUpload::make()
     ->multiple()` (via medialibrary) + `DatePicker` (due date, nullable).
   - `research_activity`: `RichEditor` (prompt) + `Toggle` ("requires a
     submission" — on/off, switches whether students see an upload field).
4. **Batches step**: a `Repeater` over `course_batches` — label,
   `DatePicker` starts_at/ends_at, `TextInput` seats (integer). No roll
   number field here — roll numbers are generated automatically on
   enrollment (Phase 4), never typed by the admin.

## Phase 3 — Student-facing pages (Livewire)

- `CourseIndex`/`CourseShow` (existing): add Prerequisites + Resources
  boxes (public, no enrollment check) and the module/block list replacing
  the flat lesson list, preview blocks open to guests per existing
  `is_preview` pattern already used.
- New `CourseLearn` Livewire component (replaces/extends
  `LessonViewer`): renders the current module's blocks, lock state from
  `CourseBlockProgress`, block-type-specific inner view per Phase 2's
  type list (quiz-taking UI, discussion thread UI matching the existing
  Consultation UI pattern, assignment upload UI, etc).
- `CourseCompleteScreen` — the Part C point 7/8 terminal screens (course
  ended / final score + certificate), reusing the existing certificate
  download flow already built this session (mPDF renderer).

## Phase 4 — Batches, roll numbers, enrollment cap

- `EnrollmentService::enroll(User $user, Course $course, ?CourseBatch
  $batch)`: checks `Enrollment::activeCourseCount($user) < 2` first
  (Part D point 6) — throws a domain exception the Livewire component
  catches and turns into the plan's exact message. If `$batch` given,
  checks seats; if full, enrolls as `waitlisted` instead and assigns
  `waitlist_position = max+1`.
- **Roll number generation**: `RollNumberGenerator::for(CourseBatch
  $batch): string` — simple, deterministic, human-readable: `{batch
  label initials}-{zero-padded sequence}`, e.g. `B1-0001`, incrementing
  per batch, generated once at the moment of confirmed enrollment (not
  for waitlisted — a waitlisted person gets their roll number only when
  promoted off the waitlist, keeping numbers gap-free and meaningful).
- `LeaveCourseAction` — sets `enrollments.left_at`, decrements the active
  count, keeps all `course_block_progress` rows (Part E point 1 —
  resuming later picks up where they left off).
- Waitlist promotion: a model observer on `Enrollment` (or `Certificate`
  issuance event) — when a seat frees up (another student's enrollment
  gets `left_at` set, or the admin raises `seats`), promote the earliest
  `waitlist_position` automatically and fire the enrollment-confirmed
  notification (Phase 7).

## Phase 5 — Final submission lock

`CourseCompletionService::finalize(Enrollment $e)`: called when the last
required block is marked done. Computes `final_score` (average of any
graded-quiz percentages + assignment pass/fail converted to 100/0,
weighted equally — simplest honest formula, documented inline since this
is a judgment call not in the user plan), sets `completed_at`, issues the
certificate (existing `IssueCertificateListener`, unchanged), and from
this point `CourseLearn`'s `mount()` redirects any visit to that course's
learn routes straight to `CourseCompleteScreen` — enforced server-side in
the Livewire `mount()`, not just hidden in the UI, so there's no URL
loophole back into a finished course's content.

## Phase 6 — Seeders

Per docs/COURSE-BUILDER-PLAN.md Part D point 5: new
`CourseModuleSeeder`/rewritten `CourseSeeder` builds the 3 existing demo
courses with real modules (3-4 each) and a deliberate MIX of block types
per course (not the same mix twice, to prove "nothing is uniform" from
the plan) — at least one course gets a batch with a small seat count
(e.g. 2) so the waitlist path is demonstrably seedable/testable.

## Phase 7 — Notifications (every role, every relevant event)

Laravel's built-in notification system (`database` + `mail` channels,
both already configured) — one `Notification` class per event, each with
a `toArray()` (shown in a bell-icon dropdown, new `NotificationsIndex`
Livewire component or Filament's built-in database-notifications panel
feature for admin) and `toMail()` (reuses the branded mail theme already
built this session):

| Event | Notify | Channel |
|---|---|---|
| Enrolled / waitlisted / promoted off waitlist | Student | database+mail |
| Admin replies to a Discussion | Student | database+mail |
| Student posts a Discussion reply | Director/Admin (consultations.respond perm) | database |
| Assignment submitted | Director/Admin | database |
| Assignment marked (Pass/Needs revision) | Student | database+mail |
| Course completed + certificate issued | Student | database+mail (reuses existing certificate-issued pattern) |
| New support ticket (Phase 8) | Director/Admin | database+mail |
| Support ticket replied | the other party | database+mail |
| Batch about to start (1 day before `starts_at`) | Enrolled students | mail, via a scheduled command `php artisan courses:batch-reminders` (Laravel scheduler, already has `routes/console.php` wired) |

## Phase 8 — Support tickets (new small module, `app/Modules/Support/`)

```
support_tickets
  id, uuid, user_id (fk), subject (string), status (open|answered|closed),
  timestamps, softDeletes
support_ticket_messages
  id, support_ticket_id (fk), author_id (fk), body (text), timestamps
```
Student: "Help" page in dashboard (new `nav_support` sidebar entry,
pattern-matches the existing Consultations UI almost exactly — same
thread shape, reuse the same Blade partial where reasonable instead of
duplicating markup). Admin: new `SupportTicketResource` in Filament,
`shouldRegisterNavigation()` gated to the same permission pattern as
Consultations (`contact.manage` or a new `tickets.manage` permission —
reuse `contact.manage` to avoid a role-seeder change unless you want
tickets separated from contact-message handling specifically).

## Phase 9 — i18n, QA, activity log audit (every phase above, not a separate pass)

Per docs/CLAUDE.md: every phase above ships its 5 lang files in the SAME
commit as the feature, not deferred. After Phase 8, one short audit pass
(reuse this session's `HasActivityLog` coverage pattern) confirms every
new model from Phase 0 logs activity and every new Filament
resource/widget has correct `canView()`/`shouldRegisterNavigation()`
gating per role (reuse the exact audit method from docs/RBAC-AUDIT.md —
tinker loop checking `canView()`/`shouldRegisterNavigation()` per role,
cheap and already proven this session).

## Build order recap

0 schema → 1 models/policies → 2 admin builder → 3 student pages →
4 batches/roll-numbers/cap → 5 completion lock → 6 seeders → 7
notifications → 8 support tickets → 9 i18n+QA pass woven through all of
the above, not deferred to the end.

Each phase is independently committable and testable (Pest feature test
per phase, matching this session's established pattern) — no phase
requires a later phase to be meaningful on its own except that Phase 3
needs Phase 1-2 data to render against.
