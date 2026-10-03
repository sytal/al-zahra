# Course Builder — User-Journey Plan

Status: PLAN ONLY. Nothing here is built yet. Written from the admin's and
the student's point of view — what they see and do, not how it's coded.
Where something already exists today, it's marked **(exists)**; everything
else is **(new)**.

## What exists today, in plain terms

A course today is: a title, a short and full description, a level,
an audience, free-or-paid, and a flat list of lessons (no grouping). A
lesson is: a title, text or video, a duration, and a "free preview" flag.
A student enrolls, works through lessons in order, marks each complete,
and gets a certificate when the course is 100% done. That's it — no
modules, no quizzes, no assignments, no case studies yet. Everything below
is the plan to get from here to what you described.

---

## PART A — How the Admin builds a course (step by step)

### The wizard, from the admin's seat

Clicking **"Create Course"** does not drop the admin into one long form.
It opens a **step-by-step wizard** — a progress bar at the top shows
"Step 1 of 3: Introduction", "Step 2: Modules", "Step 3: Review & Publish".
Each step must be filled in and marked complete before "Next" unlocks
(a half-finished step shows a red dot in the progress bar so the admin
always knows what's left). The draft saves automatically at every step,
so closing the tab halfway through never loses work — reopening the course
from "My Courses" (admin side) resumes exactly where they left off.

### Step 1 — Introduction

One page, four clearly separated sections, each with its own heading and
helper text so the admin always knows what goes where:

| Section | What the admin writes | How it looks to the student |
|---|---|---|
| **a. About** | The course's story in their own words — why it matters, who it's for. Rich-text editor (bold, lists, headings) **(exists — today's "full description")** | The main course-detail page body, the first thing a visitor reads |
| **b. Learning Objectives** | A simple bullet list: "By the end of this course, you will be able to..." — admin adds one line per objective, can add/remove lines freely **(exists — today's "learning outcomes")** | A checklist block on the course page ("What you'll learn") |
| **c. Prerequisites** | Plain bullet list of what a learner should already know or have ("Basic understanding of child development", "Completed Foundations course") — optional, admin can leave empty and it simply doesn't show **(new)** | A short "Before you start" box on the course page, hidden if empty |
| **d. Course Resources** | A free-form attachment list: admin can add any mix of — a file (PDF/doc), an external link, or a plain text note. Each item gets a label the admin writes ("Reading list (PDF)", "Official research portal"). Add as many as needed, reorder them, remove any **(new)** | A "Resources" box on the course page, downloadable/clickable, visible to everyone (not locked behind enrollment, since it helps a visitor decide to enroll) |

Each of the four sections is genuinely independent — admin can write the
"About" paragraph today, come back tomorrow and add Prerequisites, in any
order, and the page remembers.

### The one rule that applies to everything below: nothing is mandatory, nothing is uniform

No content-block type is required in any course, no two courses have to
look alike, and there is no "standard template" the admin is forced into.
One course might be five Readings and a Graded Quiz. Another might be
all Case Studies and Assignments with zero quizzes. A third might use
every block type at least once. **The admin picks exactly which block
types exist, how many of each, and in what order, independently for every
single course.** The menu in Step 3 is a toolbox, not a checklist to
complete — the builder never says "you're missing a Discussion block" or
forces a minimum. This applies to modules too: a course can have 1 module
or 20, and a module can have 1 block or 50.

### Step 2 — Modules

Before adding content, the admin is asked once, plainly:

> **"Do you already know how many modules this course will have?"**
> - **Yes, let me name them all now** — admin types a number (e.g. 5) and
>   immediately gets 5 empty module slots with editable titles
>   ("Module 1: Introduction to...", etc.), which they can rename, reorder,
>   add to, or delete at any time later anyway.
> - **No, I'll add modules as I go** — admin starts with zero modules and
>   a single "+ Add Module" button; each click adds one more at the end.

**Recommendation:** default to "add as you go" (it's the lower-friction
path and nothing is lost — an admin can always add or rename a module
later), but keep the "name them all upfront" option for admins who plan
their curriculum on paper first. Neither path locks anything in — module
count and names stay editable for the life of the course, even after
publishing, so this is a convenience choice, not a commitment.

Each module is just: a title, an optional one-line description, and then
a growing list of **content blocks** inside it (below). Modules can be
drag-reordered, collapsed/expanded in the builder, and the admin sees a
live count under each ("4 blocks • ~35 min").

### Step 3 — Content blocks inside a module

Inside any module, the admin clicks **"+ Add Content"** and picks a block
type from a menu (icons + one-line descriptions so it's self-explanatory,
not a jargon list):

> Reading · Practical Quiz · Case Study · Research Reading · Discussion ·
> Graded Quiz · Research Paper · Case Analysis · Assignment ·
> Research Activity

There is **no limit** on how many blocks a module has, no limit on mixing
types, and the same type can repeat as many times as the admin wants
(three Readings then a Quiz then another Reading — any order, drag to
reorder). Each block the admin adds appears as a numbered row in the
module; clicking it opens that block's own small editor (detailed below).
A block can be marked **"free preview"** (same as today's lesson preview
flag) so guests can sample it before enrolling.

---

## PART B — Each content-block type, exactly what it is

Every block type below follows the SAME outer shell (a title the admin
sets, a "free preview" toggle, an estimated time, and a position in the
module) — only the INSIDE differs, as described:

### 1. Reading
The simplest block: a rich-text editor, styled like a published article
when the student reads it — generous line height, a large **drop-cap**
first letter on the opening paragraph (like a magazine/journal article),
proper heading hierarchy, block quotes, images. No quiz, no submission —
just read and move to the next block. **Research Reading** (below) reuses
this exact editor and style; the only difference is framing, not format.

### 2. Practical Quiz & 6. Graded Quiz
Same editor, different purpose:
- Admin adds **as many questions as they want**, each a multiple-choice
  question with **as many options as they want** (2, 4, 6 — no fixed
  number) and marks the correct option(s).
- Optional per-question explanation shown after the student answers
  ("Why this is correct").
- **Practical Quiz** = ungraded practice. Student sees "Correct!" or "Try
  again" instantly, can retake it freely, and it does NOT block progress
  or affect any score — purely for self-check.
- **Graded Quiz** = counts toward course completion. A student must score
  above a pass mark the admin sets (default configurable, e.g. 70%) to
  have this block count as "done." Shows a final score, right/wrong
  review, and a retake button if they want to try again (admin decides
  whether retakes are unlimited or capped — simple toggle).

### 3. Case Study, 7. Research Paper, 8. Case Analysis
All three share one pattern (same editor, different labels/icons so the
student instantly recognizes which kind of material it is):
- A rich-text write-up area (the admin's own case study / paper / analysis
  text, written in the editor or pasted from elsewhere).
- An **external links** list — admin can add any number of outside links
  (e.g. a real published case study, a PDF hosted elsewhere, a news
  article used as source material).
- A **mandatory "Reference / Source" field** for every link or quoted
  material the admin brings in from outside — a short citation line
  (e.g. "Smith, J. (2019). Title. Journal Name.") so the course always
  credits where material came from. The admin cannot save the block if an
  external link has no reference filled in — this is a deliberate small
  guardrail so nothing gets pulled in without attribution.
- No student submission here — these are read-and-reflect blocks, same
  family as Reading, just sourced partly from outside material.

### 4. Research Reading
Identical block to Reading (drop-cap article styling), just a different
label/icon on the module list so students know it's academic/research
material rather than a general lesson.

### 5. Discussion
**Confirmed: student-and-admin only, never student-to-student.** This is
a private back-and-forth thread tied to that exact block, the same shape
as the site's existing Consultation feature, just scoped to one module
instead of standing alone:
- Admin writes an opening question or prompt when creating the block
  ("How would you apply this with a bilingual 4-year-old?").
- The student reads it and types a reply. The admin sees every student's
  reply (grouped by course → module → student) and can respond back.
  Each student only ever sees their OWN thread with the admin — never any
  other student's replies, exactly like today's Consultations are
  private per person.
- The block counts as "done" for the student once they've posted at
  least one reply — an admin response is not required to unlock the next
  block, since the admin may reply later.
- On the admin side, unanswered discussion replies show up the same way
  pending consultations do today — a count, a list, oldest-first.

### 9. Assignment
- Admin writes the task instructions (rich text) and can **attach any
  number of files** (PDF, Word doc, slides, images — whatever the task
  needs), each with its own label.
- Admin can optionally set a due date.
- The student reads the instructions, downloads any attached files, and
  **uploads their own file(s) back** as their submitted work.
- **Confirmed: the admin marks and checks it.** Status flow is:
  Not submitted → Submitted → Reviewed, and on Reviewed the admin leaves
  a **Pass/Needs revision** mark plus an optional written comment the
  student can see (e.g. "Good analysis, but add a source for paragraph 2
  — please resubmit"). If marked "Needs revision," the student can
  re-upload and it goes back to Submitted for another look. The block
  only counts as "done" toward course completion once the admin marks it
  Pass (exact pass/fail-vs-numeric-score question is in Open Questions).
- Admin's view: a simple reviewable list per course — "who submitted
  what, submitted when, current status" — oldest/unreviewed first.

### 10. Research Activity
A lighter version of Assignment: the admin gives an example or a
real-world prompt ("Observe a bilingual child for 10 minutes and note
code-switching patterns") and simply asks the student to either (a) mark
it done once they've done the activity themselves, no file needed, or
(b) submit a short write-up / file if the admin wants evidence — admin
picks which of these two modes when creating the block, per activity.

---

## PART C — The student's side of this

1. **Browsing a course page (not yet enrolled):** sees About, Learning
   Objectives, Prerequisites, Course Resources, the full module list with
   block counts, and can open any block marked "free preview" without
   logging in — exactly like today's preview-lesson behavior, just
   extended to every block type instead of only text/video lessons.
2. **Enrolling:** same as today — "Enroll Now" (free) button, logs the
   enrollment, unlocks every block.
3. **Working through the course:** modules appear as collapsible sections
   (current module open, others collapsed with a progress check next to
   each), each block inside shows its type icon, a completion checkmark
   once done, and a lock icon if the admin set blocks to unlock in order
   (see open question below). Quizzes show instant feedback; assignments
   show submission status; everything else is "read it, it's marked done."
4. **Progress & certificate:** the existing progress bar and
   certificate-on-100%-completion logic (already built) extends naturally
   — "100% complete" now means every block across every module is done,
   not just every lesson in a flat list.
5. **Batches, with real seats (confirmed, not just an announcement):**
   a course can optionally have one or more **batches/intakes**, each with
   its own start date and a **seat limit** the admin sets (e.g. "Batch 1 —
   Starts Jan 15 — 30 seats"). On the course page, if batches exist, the
   student picks a batch when enrolling (if there's only one open batch,
   it's pre-selected, one click). The Enroll button shows live seats left
   ("12 of 30 seats left") and switches to **"Full — Join Waitlist"**
   once a batch is full: the Enroll button becomes a Waitlist button, the
   student joins a simple ordered waitlist, and the admin has a view per
   batch of who's waiting, in order, with a one-click "move to enrolled"
   action if a seat opens up (someone drops, or the admin raises the
   seat count). A course with no batches at all stays fully self-paced,
   exactly like today — batches are opt-in per course, matching the
   "nothing is mandatory" rule above.
6. **Enrollment limit — at most 2 courses in progress at once:** a
   student can be actively enrolled in **at most 2 courses simultaneously**.
   "Actively enrolled" means not yet 100% complete — the moment a course
   is finished and the certificate is issued, it stops counting toward
   this limit, freeing a slot. If a student already has 2 in-progress
   courses and tries to enroll in a 3rd, the Enroll button is replaced
   with a clear message: *"You can be enrolled in up to 2 courses at a
   time. Finish one of your current courses to enroll in a new one."*
   with links to their 2 in-progress courses so they know exactly what
   to finish. *(Assumption, flag if wrong: there's no "drop/leave a
   course early" button — the only way to free a slot is finishing one.
   Say so if you want a voluntary leave option too; it's a small addition
   on top of this.)*
7. **End of course — a real "you're done" screen, not a dead end:**
   whatever the last block in the last module is, once the student
   finishes it, they land on a dedicated **course-complete screen** —
   not just silence or a greyed-out "next" button. It clearly says the
   course has ended and shows their completion percentage for it. If
   every block is done, this percentage is 100% and the flow continues
   into point 8 below. If the admin's course structure allows an "end"
   before every block is strictly done (e.g. optional content), the
   screen still shows the real percentage honestly rather than forcing
   100%.
8. **Final submission is one-way — confirmed:** once a course reaches
   100% and the student finally submits/completes it, they land on a
   **terminal completion screen**: final score (if the course had any
   graded quizzes/assignments — combined into one summary number) and a
   **Download Certificate** button. After this point, the student can
   **no longer re-enter or re-view the course's lessons, quizzes, case
   studies, or anything else inside it** — the only thing that remains
   accessible from "My Courses" for that course, forever, is this
   completion screen (score + certificate download). This is a
   deliberate one-way door: completed means closed, not "completed but
   still browsable." *(Note: this is a firmer rule than typical course
   platforms, which usually let you revisit finished material — confirmed
   per your instruction, not a mistake on my part. Per-block retake
   options like the Graded Quiz retake toggle in Part B still apply WHILE
   a course is in progress — this rule only kicks in after the whole
   course is finally submitted.)*

---

## PART D — Decisions already made

1. **Block order lock:** confirmed — locked by default, a student must
   finish block 1 before block 2 unlocks, same logic as today's
   "Mark complete → Next Lesson." Free-preview blocks stay open
   regardless of lock state (a guest/non-enrolled visitor can always open
   them). An admin cannot create ambiguity here — order lock applies
   uniformly within a module, not block-by-block.
2. **Discussion:** confirmed private, student-with-admin-only (see Part B,
   section 5) — never student-to-student.
3. **Assignment:** confirmed the admin marks and checks every submission
   (see Part B, section 9) — this is not just a submission log.
4. **Batches:** confirmed real seat capacity, not just a date banner (see
   Part C, point 5) — optional per course, never forced.
5. **Database/seeders:** confirmed build order — write the new migrations
   for modules + every block type FIRST, get the schema right, then write
   fresh seeders against that new structure (not a patch of the old flat
   lessons). The 3 existing demo courses get properly re-seeded with real
   modules and a mix of block types as part of this work, not migrated
   automatically from their current flat lesson lists and not left for
   manual admin cleanup — this is a seeder-rewrite, done once, properly.
6. **Assignment grading:** confirmed Pass / Needs revision (two-state),
   not a numeric score.
7. **Full batches:** confirmed Full status + Waitlist (see Part C, point
   5) — not a hard block with nothing the student can do.
8. **Course intro content (About/Goals/Prerequisites/Resources):**
   confirmed fully visible to everyone, no enrollment needed — only the
   module/block content itself is locked behind enrollment.
9. **Graded Quiz default pass mark:** set to **70%** as the starting
   default for every new Graded Quiz block — the admin can change it per
   quiz at any time, this only affects what a freshly-created quiz starts
   at.
10. **Activity log — everywhere, properly:** confirmed. Every new piece
    of this feature gets the same `HasActivityLog` treatment already
    applied site-wide (docs/RBAC-AUDIT.md, the 16 models already logged):
    module created/reordered/deleted, every block type created/edited/
    deleted, every student block-completion, every quiz attempt and
    score, every discussion reply (both sides), every assignment
    submission and every admin mark, every batch created and every seat
    taken/waitlisted, and the final course-completion + certificate
    issuance. The admin's Recent Activity view (already built) picks all
    of this up automatically — nothing here needs a separate "course
    activity" screen, it already flows into the one activity log.

## PART E — Resolved

1. **Voluntary "leave a course early": confirmed, add it.** A student can
   click "Leave this course" on an in-progress (not yet completed) course
   from My Courses. This immediately frees one of their 2 enrollment
   slots. Their progress on that course is kept (not deleted) in case
   they re-enroll later — re-enrolling resumes where they left off rather
   than starting over. Leaving does not affect certificate/completed
   courses (those are already outside the 2-slot cap and cannot be
   "left").

This document is final and ready to build against.
