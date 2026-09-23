# AL ZAHRA INSTITUTE — EXACT BLUEPRINT (Complete: Tables → Routes → Pages → Every Element)

> Companion to `CLAUDE.md` (the HOW/standards file). This file is the WHAT —
> down to individual page sections. Claude must not invent structure;
> follow this literally. Anything genuinely not covered here must still
> follow `CLAUDE.md`'s standards, and gets logged as an addition in
> `memory/progress-log.md`.

---

## HOW TO READ THIS FILE

For every page below:
- **Route** — exact method/URI/name
- **Access** — who can view it
- **Layout** — which shared layout wraps it
- **Sections (top to bottom)** — every visual block on the page, in order,
  with what data/fields it shows and which component renders it
- **Data source** — which controller method / Livewire component feeds it

---

# PART A — DATABASE (every table, every column, fully)

## A1. `users`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| uuid | uuid unique | |
| name | string(150) | |
| email | string(191) unique | |
| email_verified_at | timestamp nullable | |
| password | string | |
| preferred_locale | string(10) default 'en' | |
| phone | string(30) nullable | optional, shown in profile only |
| is_active | boolean default true | admin can deactivate instead of delete |
| last_login_at | timestamp nullable | |
| remember_token | string(100) nullable | |
| soft deletes, timestamps | | |
Media collection: `avatar` (single file).

## A2. `directors`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| uuid | uuid unique | |
| user_id | FK users nullable | |
| full_name | string(150) | not translatable (proper noun) |
| professional_title | json translatable | e.g. "Neurolinguist & Language Researcher" |
| tagline | json translatable | one-line hook for hero section |
| bio_short | json translatable | 2-3 sentences, homepage |
| bio_full | json translatable | full About page, rich text |
| credentials | json | array of strings: degrees/certifications |
| research_interests | json translatable | array of strings |
| social_links | json | {linkedin, researchgate, twitter, email} nullable keys |
| is_published | boolean default true | |
| soft deletes, timestamps | | |
Media collections: `profile_photo` (single), `cover_photo` (single).

## A3. `settings`
| Column | Type | Notes |
|---|---|---|
| id | bigint PK | |
| key | string unique | |
| value | json | |
| timestamps | | |
Seeded keys: `site_name`, `site_tagline`, `contact_email`, `contact_phone`,
`footer_about_text`(translatable), `footer_links`(array of
{label(translatable), url}), `social_links`(array), `mission_text`
(translatable), `vision_text`(translatable), `newsletter_enabled`(bool).

## A4. `categories`
id(PK), uuid(unique), name(json translatable), slug(unique), type(enum:
article/research/resource/course), description(json translatable
nullable), timestamps.

## A5. `articles`
id, uuid(unique), author_id(FK users), category_id(FK categories
nullable), title(json translatable), slug(unique), excerpt(json
translatable), body(json translatable longtext), reading_time_minutes
(unsignedSmallInteger nullable, auto-calc on save), is_published(bool
default false), published_at(timestamp nullable), views_count
(unsignedInteger default 0), meta_title/meta_description(json
translatable nullable), soft deletes, timestamps.
Media: `featured_image` (conversions: thumb 400x250, card 600x375, hero
1200x630).
Plus **tags** table: id, uuid, name(json translatable), slug(unique).
Plus **article_tag** pivot: article_id, tag_id.

## A6. `research_papers`
id, uuid, author_id(FK users), category_id(FK categories nullable),
title(json translatable), slug(unique), research_question(json
translatable nullable), methodology_summary(json translatable nullable),
findings_summary(json translatable nullable), significance(json
translatable nullable), full_paper_type(enum: pdf_upload/external_link),
external_url(string nullable), published_year(year nullable), co_authors
(json nullable, array of strings), is_published(bool default false),
meta_title/meta_description(json translatable nullable), soft deletes,
timestamps.
Media: `paper_file`(single pdf), `cover_image`(single).

## A7. `resources`
id, uuid, category_id(FK categories nullable), title(json translatable),
slug(unique), description(json translatable), resource_type(enum:
pdf/template/questionnaire/guide), is_free(bool default true), price
(decimal 8,2 nullable, reserved/unused), download_count(unsignedInteger
default 0), is_published(bool default false), meta_title/meta_description
(json translatable nullable), soft deletes, timestamps.
Media: `resource_file`(single), `thumbnail`(single).

## A8. `courses`
id, uuid, category_id(FK categories nullable), instructor_id(FK users),
title(json translatable), slug(unique), short_description(json
translatable), full_description(json translatable), level(enum:
beginner/intermediate/advanced), audience(enum:
students/teachers/parents/professionals), learning_outcomes(json
translatable array), estimated_duration_hours(unsignedSmallInteger
nullable), is_free(bool default true), price(decimal 8,2 nullable,
reserved), is_published(bool default false), enrolled_count
(unsignedInteger default 0, cached counter), meta_title/meta_description
(json translatable nullable), soft deletes, timestamps.
Media: `cover_image`(conversions: card 600x400, hero 1200x630).

## A9. `course_lessons`
id, uuid, course_id(FK courses cascade), title(json translatable),
content_type(enum: text/video/mixed), body(json translatable nullable),
video_url(string nullable), duration_minutes(unsignedSmallInteger
nullable), is_preview(bool default false, viewable without enrollment),
sort_order(unsignedInteger default 0), soft deletes, timestamps.
Media: `lesson_attachments`(multiple).

## A10. `enrollments`
id, uuid, user_id(FK users cascade), course_id(FK courses cascade),
status(enum: active/completed), progress_percent(unsignedTinyInteger
default 0, cached), enrolled_at(timestamp), completed_at(timestamp
nullable), timestamps, unique(user_id, course_id).

## A11. `lesson_progress`
id, uuid, enrollment_id(FK enrollments cascade), course_lesson_id(FK
course_lessons cascade), completed_at(timestamp nullable), timestamps,
unique(enrollment_id, course_lesson_id).

## A12. `consultations`
id, uuid, user_id(FK users nullable), guest_name(string nullable),
guest_email(string nullable), type(enum: free_question/paid_booking),
topic(string nullable), question(text), status(enum:
pending/answered/scheduled/completed/cancelled), answer(text nullable),
preferred_datetime(timestamp nullable), scheduled_datetime(timestamp
nullable, admin-set), answered_at(timestamp nullable), soft deletes,
timestamps.

## A13. `certificates`
id, uuid, verification_code(string unique, format AZ-{year}-{6digit}),
user_id(FK users cascade), course_id(FK courses cascade),
issued_at(timestamp), timestamps, unique(user_id, course_id).
Media: `certificate_pdf`(single).

## A14. `newsletter_subscribers`
id, uuid, email(unique), locale(default 'en'), is_confirmed(bool default
false), confirmed_at(timestamp nullable), unsubscribed_at(timestamp
nullable), timestamps.

## A15. `contact_messages`
id, uuid, name, email, subject, message(text), status(enum:
new/read/replied), soft deletes, timestamps.

**Package-generated (not hand-written):** `roles`, `permissions`,
`model_has_roles`, `model_has_permissions`, `role_has_permissions`
(Spatie Permission); `activity_log` (Spatie Activitylog); `media` (Spatie
Medialibrary); `sessions`, `password_reset_tokens`, `jobs`, `failed_jobs`,
`cache` (Laravel defaults).

**Total custom tables: 16** — users, directors, settings, categories,
articles, tags, article_tag, research_papers, resources, courses,
course_lessons, enrollments, lesson_progress, consultations,
certificates, newsletter_subscribers, contact_messages.

---

# PART B — SHARED COMPONENTS INVENTORY (build once in Phase 1, reused everywhere)

| Component | Props | Used on |
|---|---|---|
| `<x-button>` | variant(primary/secondary/outline/danger/ghost), size(sm/md/lg), icon, href/wire:click, loading | everywhere |
| `<x-icon>` | name(heroicon), size, class | everywhere |
| `<x-card>` | padding, hoverable | article/course/research cards, dashboard widgets |
| `<x-badge>` | color(brand/success/warning/danger/neutral), text | status labels |
| `<x-input>` | name, label, type, placeholder, wire:model, error | all forms |
| `<x-textarea>` | name, label, rows, wire:model, error | all forms |
| `<x-select>` | name, label, options, wire:model, error | all forms |
| `<x-checkbox>` / `<x-radio>` | name, label, wire:model | all forms |
| `<x-table>` | headers slot, rows slot | dashboard lists |
| `<x-modal>` | name, maxWidth | confirmations, quick views |
| `<x-dropdown>` | trigger slot, content slot | user menu, filters |
| `<x-accordion>` | items array (title, content) | curriculum outline, research methodology |
| `<x-pagination>` | wraps Livewire pagination, themed | all paginated lists |
| `<x-seo>` | title, description, image, type, schema | every public page head |
| `<x-language-switcher>` | — | header |
| `<x-director-profile>` | :compact | home / about |
| `<x-instructor-card>` | instructor model | course detail (non-director instructors) |
| `<x-detail-layout>` | title, meta slot, share slot, related slot | article/research/resource/course detail |
| `<x-footer>` | — | public + dashboard layouts |
| `<x-empty-state>` | icon, title, message, action slot | empty lists |
| `<x-breadcrumbs>` | items array | inner pages |
| `<x-progress-bar>` | percent, label | course progress |
| `<x-newsletter-form>` | — | footer, homepage |
| `<x-share-buttons>` | url, title | article/research detail |
| `<x-stat-card>` | icon, label, value | dashboard/admin widgets |

---

# PART C — PAGE-BY-PAGE BREAKDOWN (every page, every section, in order)

## C1. Home — `GET /{locale}/` name: `home`
Access: public. Layout: `layouts.public`.
1. Hero — headline (Settings `site_tagline`), sub-text, 2 CTA buttons
   ("Explore Articles", "Browse Courses"), brand gradient bg, AOS fade-up.
2. Director intro strip — `<x-director-profile :compact="true" />`.
3. "Explore" section — 3 latest published articles as `<x-card>` (image,
   title, excerpt, reading time), "View all" link.
4. Featured courses — up to 3 published courses as cards (cover, title,
   level/audience/free-paid badges), empty-state if none.
5. Research highlight — 2 latest research_papers, horizontal cards.
6. "Ask Al Zahra" CTA band — brand-colored, links to consultation form.
7. Newsletter signup — `<x-newsletter-form>`.
8. Footer.
Data: `HomeController::index()`.

## C2. About — `GET /{locale}/about` name: `about`
1. Hero: cover photo + name/title overlay.
2. `<x-director-profile :compact="false" />` — full bio, credentials
   list, research interests as pills.
3. Mission & Vision — two columns from Settings.
4. Social/contact links row.
5. Footer.
Data: `PageController::about()`.

## C3. Articles list — `GET /{locale}/articles` name: `articles.index`
1. Header: title + intro text.
2. Filter bar: category select + debounced search input (Livewire,
   no reload).
3. Grid of article cards, paginated 12/page.
4. Empty state if no matches.
5. Footer.
Livewire: `ArticleIndex`.

## C4. Article detail — `GET /{locale}/articles/{article:slug}` name: `articles.show`
`<x-detail-layout>`:
1. Breadcrumbs: Home / Articles / {Category} / {Title}.
2. Title, author, date, reading time, category badge.
3. Featured image (hero conversion).
4. Body content.
5. `<x-share-buttons>`.
6. Tags row (pills → filtered article list).
7. Related Articles (3, same category).
8. No comment system (out of scope).
9. Footer.
Controller: `ArticleController::show()` — increments `views_count` once
per session.

## C5. Research list — `GET /{locale}/research` name: `research.index`
Same pattern as C3: filter by category, search by title, cards show
findings_summary excerpt + published_year badge. Livewire: `ResearchIndex`.

## C6. Research detail — `GET /{locale}/research/{research_paper:slug}` name: `research.show`
`<x-detail-layout>`:
1. Breadcrumbs.
2. Title, co-authors, published year, category badge, cover image.
3. "Research Question" boxed section.
4. "What They Found" boxed section.
5. "Why It Matters" boxed section.
6. Methodology summary in `<x-accordion>` (collapsible).
7. "Read Full Paper" button (opens PDF or external_url).
8. Share buttons.
9. Related research (same category).
10. Footer.

## C7. Resources list — `GET /{locale}/resources` name: `resources.index`
Same list pattern. Cards show resource_type badge, Free/Paid badge,
thumbnail. Livewire: `ResourceIndex`.

## C8. Resource detail — `GET /{locale}/resources/{resource:slug}` name: `resources.show`
`<x-detail-layout>`:
1. Breadcrumbs, title, category + type badges, thumbnail.
2. Description.
3. Disclaimer text for questionnaire/guide types: "This tool is
   educational and does not provide a medical diagnosis." (static
   translated string).
4. Download button: free → direct download (increments download_count,
   no login needed); paid → disabled with "Coming soon" tooltip (no
   gateway yet).
5. Related resources.
6. Footer.

## C9. Courses list — `GET /{locale}/courses` name: `courses.index`
1. Header + intro.
2. Filter bar: audience select, level select, free/paid toggle, search.
3. Grid of course cards (cover, title, badges, "X students enrolled").
4. Pagination, empty state.
5. Footer.
Livewire: `CourseIndex`.

## C10. Course detail — `GET /{locale}/courses/{course:slug}` name: `courses.show`
`<x-detail-layout>`:
1. Breadcrumbs, hero cover, title, level/audience/free-paid badges.
2. Short description.
3. "What You'll Learn" bullet list.
4. Curriculum outline accordion (lesson title, duration, lock icon if not
   preview and not enrolled; preview lessons openly clickable without
   login).
5. Instructor card — `<x-director-profile :compact="true">` if
   instructor is Director, else `<x-instructor-card>`.
6. Enroll button: guest → redirect to login (preserving intended URL);
   logged-in not enrolled → "Enroll Now"; enrolled → "Continue Learning"
   → `dashboard.courses.learn`.
7. Related courses.
8. Footer.

## C11. Dashboard Home — `GET /{locale}/dashboard` name: `dashboard`
Access: auth. Layout: `layouts.dashboard` (persistent sidebar: Dashboard,
My Courses, Consultations, Certificates, Profile, Logout — all
wire:navigate, active link highlighted).
1. Welcome banner.
2. Stat cards row (×3): courses in progress, certificates earned,
   consultations — always rendered, 0 if empty.
3. "Continue Learning" — up to 2 in-progress enrollments with progress
   bar; empty-state with CTA to courses.index if none.
4. "Recent Consultations" — last 2 with status badge; empty-state if none.
5. Dashboard footer (simplified, no newsletter form).

## C12. My Courses — `GET /{locale}/dashboard/my-courses` name: `dashboard.courses.index`
1. Header "My Courses".
2. Tabs: All / In Progress / Completed (Livewire, no reload).
3. Enrolled course cards with progress bar, "Continue"/"Review" button;
   completed ones show certificate icon link.
4. Empty state, CTA to courses.index.

## C13. Lesson viewer — `GET /{locale}/dashboard/courses/{course:slug}/learn/{lesson:uuid}` name: `dashboard.courses.lesson`
Two-column (stacks on mobile):
- Left: lesson title, video embed or text body, attachments list.
- Right: curriculum sidebar, checkmarks for completed, current
  highlighted, clickable (no reload).
- Bottom bar: "Mark as Complete" + "Next Lesson" buttons.
Livewire: `LessonViewer`.

## C14. Consultations (dashboard) — `GET /{locale}/dashboard/consultations` name: `dashboard.consultations.index`
1. Header + "Ask a New Question" button.
2. `<x-table>`: topic, type badge, status badge, date, "View" (opens
   `<x-modal>` with question+answer).
3. Empty state if none.

## C15. Certificates (dashboard) — `GET /{locale}/dashboard/certificates` name: `dashboard.certificates.index`
1. Header "My Certificates".
2. Certificate cards: course title, issued date, verification code,
   "Download PDF" button.
3. Empty state, CTA to My Courses.

## C16. Profile edit — `GET /{locale}/dashboard/profile` name: `dashboard.profile.edit`
Separate stacked cards, each own `wire:submit` + own success toast:
1. Profile photo upload/preview/remove.
2. Basic info: name, email (change triggers re-verification), phone.
3. Preferences: preferred_locale select.
4. Password change (separate form).

## C17. Consultation form (public) — `GET/POST /{locale}/consultation` name: `consultation.show` / `consultation.store`
1. Header + explanatory text.
2. Type toggle: Free Question / Book a Consultation (changes fields
   shown).
3. Fields: (guest) name, email; topic; question textarea; (paid_booking)
   preferred datetime picker.
4. Submit → inline success confirmation (no redirect).
Livewire: `ConsultationForm`.

## C18. Contact — `GET/POST /{locale}/contact` name: `contact.show` / `contact.submit`
1. Header + intro.
2. Two-column: form (name, email, subject, message) | info block
   (email/phone/social from Settings).
3. Submit → inline success message.
Livewire: `ContactForm`.

## C19. Certificate verification — `GET/POST /{locale}/certificates/verify` name: `certificates.verify.form` / `certificates.verify.check`
1. Header "Verify a Certificate".
2. Single input (verification code) + submit.
3. Result panel: valid → green card (first name + last initial, course
   title, issued date, institute seal graphic); invalid → neutral "not
   found" message.

## C20. Signed guest consultation view — `GET /{locale}/consultations/{consultation:uuid}/view/{signature}` name: `consultations.signed-view`
1. Centered card: question + answer + answered date, "Have another
   question?" link. Minimal layout variant (header+footer only, no nav
   menu).

## C21. Auth pages (Login/Register/Forgot/Reset)
Centered card layout (same minimal variant as C20), brand logo top,
Breeze-standard fields restyled with shared `<x-input>`/`<x-button>`,
switch link at bottom, language switcher visible.

## C22. Error pages (404 / 403 / 419 / 500)
Centered icon, code-appropriate heading, one-line friendly message,
"Back to Home" button. 419 additionally: "Your session took a while —
please refresh and try again" + hard-reload "Refresh" button (only
intentional hard reload in the whole app).

---

# PART D — ADMIN PANEL (Filament) — RESOURCE LIST

| Resource | Table columns | Form fields |
|---|---|---|
| ArticleResource | thumb, title, category, author, status badge, published_at, views_count | title(translatable tabs), slug(auto, editable), category select, excerpt(translatable), body(translatable rich editor), featured_image upload, tags multi-select, is_published toggle, published_at datetime, collapsible "SEO" section (meta_title/meta_description translatable) |
| ResearchPaperResource | cover thumb, title, category, year, status | title, category, research_question/findings_summary/significance (translatable, separate fields), full_paper_type radio (conditionally shows upload or url), co_authors repeater, published_year, cover_image upload, is_published toggle |
| ResourceResource | thumb, title, type badge, free/paid badge, downloads | title, category, description(translatable), resource_type select, resource_file upload, thumbnail upload, is_free toggle, price(shown if not free), is_published toggle |
| CourseResource | cover thumb, title, level badge, audience badge, enrolled_count, status | title, category, instructor select, short/full description(translatable), level select, audience select, learning_outcomes repeater, estimated_duration_hours, cover_image upload, is_free toggle, price, is_published toggle — **relation manager**: CourseLessons (title, content_type, duration, is_preview toggle, drag-sort) |
| ConsultationResource | topic, requester, type badge, status badge, created_at | read-only question, answer textarea, status select, scheduled_datetime (if paid_booking), header action "Send Answer" (sets answered_at, dispatches email job) |
| DirectorResource | photo thumb, full_name, is_published | full_name, professional_title/tagline/bio_short/bio_full(translatable), credentials repeater, research_interests repeater(translatable), social_links key-value repeater, profile_photo/cover_photo upload, is_published toggle |
| CategoryResource | name, type badge, slug | name(translatable), type select, description(translatable) |
| TagResource | name, slug | name(translatable) |
| SettingResource | key, updated_at | custom Filament page, sections: General (site_name/tagline/contact), Footer (links repeater, about text), Mission/Vision, Social Links |
| CertificateResource | code, user, course, issued_at | read-only list; "Revoke" (soft-delete) action only |
| NewsletterSubscriberResource | email, locale, confirmed badge, date | read-only list + export-to-CSV action |
| ContactMessageResource | name, subject, status badge, date | read-only message, status select |
| UserResource | avatar thumb, name, email, role badge, is_active | name, email, role select, is_active toggle, "Send password reset" action (no editable password field) |

Filament dashboard widgets: `TotalArticlesWidget`, `TotalCoursesWidget`,
`TotalEnrollmentsWidget`, `PendingConsultationsWidget` (links to filtered
list), `RecentActivityWidget` (human-readable `activity_log` feed),
`NewsletterSubscribersWidget`.

---

# PART E — FULL ROUTE TABLE (single reference, all phases combined)

```
# Public
GET  /{locale}/                                                    home
GET  /{locale}/about                                                about
GET  /{locale}/articles                                             articles.index
GET  /{locale}/articles/{article:slug}                              articles.show
GET  /{locale}/research                                             research.index
GET  /{locale}/research/{research_paper:slug}                       research.show
GET  /{locale}/resources                                            resources.index
GET  /{locale}/resources/{resource:slug}                            resources.show
POST /{locale}/resources/{resource:slug}/download                   resources.download
GET  /{locale}/courses                                              courses.index
GET  /{locale}/courses/{course:slug}                                courses.show
POST /{locale}/courses/{course:slug}/enroll                         courses.enroll
GET  /{locale}/consultation                                         consultation.show
POST /{locale}/consultation                                         consultation.store
GET  /{locale}/consultations/{consultation:uuid}/view/{signature}    consultations.signed-view
GET  /{locale}/contact                                               contact.show
POST /{locale}/contact                                               contact.submit
GET  /{locale}/certificates/verify                                   certificates.verify.form
POST /{locale}/certificates/verify                                   certificates.verify.check
POST /{locale}/newsletter/subscribe                                  newsletter.subscribe
GET  /{locale}/newsletter/confirm/{subscriber:uuid}/{signature}      newsletter.confirm

# Auth
GET  /{locale}/login                                                login
POST /{locale}/login                                                 login.store
GET  /{locale}/register                                              register
POST /{locale}/register                                              register.store
POST /{locale}/logout                                                logout
GET  /{locale}/forgot-password                                       password.request
POST /{locale}/forgot-password                                       password.email
GET  /{locale}/reset-password/{token}                                 password.reset
POST /{locale}/reset-password                                        password.update

# Dashboard (auth required)
GET  /{locale}/dashboard                                             dashboard
GET  /{locale}/dashboard/my-courses                                   dashboard.courses.index
GET  /{locale}/dashboard/courses/{course:slug}/learn                   dashboard.courses.learn
GET  /{locale}/dashboard/courses/{course:slug}/learn/{lesson:uuid}      dashboard.courses.lesson
POST /{locale}/dashboard/lessons/{lesson:uuid}/complete                 dashboard.lessons.complete
GET  /{locale}/dashboard/consultations                                 dashboard.consultations.index
GET  /{locale}/dashboard/certificates                                  dashboard.certificates.index
GET  /{locale}/dashboard/certificates/{certificate:uuid}/download       dashboard.certificates.download
GET  /{locale}/dashboard/profile                                       dashboard.profile.edit
PUT  /{locale}/dashboard/profile                                       dashboard.profile.update
PUT  /{locale}/dashboard/profile/password                              dashboard.profile.password.update

# Admin — Filament panel, mounted at /admin, routes auto-generated per Part D
```

---

# PART F — JOBS / EVENTS / LISTENERS / SCHEDULE (exact list)

| Job (queued) | Trigger | Queue |
|---|---|---|
| `SendContactEmailJob` | ContactController::store | emails |
| `SendConsultationReceivedEmailJob` | ConsultationController::store | emails |
| `SendConsultationAnsweredEmailJob` | Filament "Send Answer" action | emails |
| `GenerateCertificatePdfJob` | CourseCompleted listener | media |
| `SendNewsletterConfirmationEmailJob` | newsletter.subscribe | emails |
| `SendWelcomeEmailJob` | Registered event (Laravel default) | emails |

| Event | Fired by | Listener |
|---|---|---|
| `CourseCompleted` | `CourseService::markLessonComplete()` at 100% progress | `IssueCertificateListener` (creates certificate row, dispatches PDF job) |

| Scheduled task | Frequency |
|---|---|
| `sitemap:generate` | daily 02:00 |
| cleanup unconfirmed newsletter subscribers >30 days old | weekly |

---

# PART F2 — SEEDERS: EXACT LIST (essential vs demo, per docs/CLAUDE.md 22B)

**Essential (run in every environment, including production):**

| Seeder | What it creates |
|---|---|
| `RolePermissionSeeder` | 4 roles (director/admin/editor/student) + full permission set per module |
| `UserSeeder` | 1 director account (wife's login), 1 admin account |
| `DirectorSeeder` | 1 `directors` row linked to the director user — real bio/credentials, `is_published=true` |
| `SettingSeeder` | all seeded `settings` keys from Part A3 with real launch copy (site_name, tagline, contact info, mission/vision text, footer links) |

**Demo (local/staging only — gated behind `! app()->isProduction()`):**

| Seeder | What it creates |
|---|---|
| `CategorySeeder` | 4 categories per type (article/research/resource/course) = 16 rows |
| `TagSeeder` | 10 generic tags |
| `ArticleSeeder` | 15 demo articles spread across categories/tags, mixed published/draft, realistic reading_time |
| `ResearchPaperSeeder` | 6 demo research papers, mix of `pdf_upload`/`external_link`, varied published_year |
| `ResourceSeeder` | 8 demo resources, mix of all 4 resource_type values, mostly `is_free=true` |
| `CourseSeeder` | 3 demo courses (one per audience: students/teachers/parents), each with 5 `course_lessons` (mixed text/video, 1 marked `is_preview`) |
| `EnrollmentSeeder` | a couple of demo enrollments (with `lesson_progress` rows) tied to the demo student user, for dashboard screenshots |
| `ConsultationSeeder` | 5 demo consultations across all statuses |
| `ContactMessageSeeder` | 3 demo contact messages across all statuses |

Goal: after `php artisan migrate --seed` on local/staging, every public list
page and the dashboard have believable content — never an empty-state
screen during development or client demos. Production seed command
(`php artisan db:seed --class=DatabaseSeeder` gated by the `isProduction()`
check in Section 22B) only runs the essential seeders.

---

# PART G — EXPLICITLY OUT OF SCOPE (do not build unless asked)
Payments/checkout, community/discussion forum, multi-instructor
marketplace, mobile app, comment system on articles, live video/webinar
hosting. Schema (`price`/`is_free` fields) is left ready for payments to
be added later without destructive migrations.
