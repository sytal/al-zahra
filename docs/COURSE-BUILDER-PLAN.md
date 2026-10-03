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
Kept intentionally simple, matching how the rest of the course already
works (no new live-chat or forum system being built right now): a
Discussion block is a Reading-style block that poses a **question or
prompt** for the learner to think about ("How would you apply this with a
bilingual 4-year-old?") — the student reads it as part of their progress,
marks it done, and moves on. If a real back-and-forth discussion
feature (students replying to each other) is wanted later, that is a
separate, bigger feature — flagged under "Open questions" below, not
built as part of this plan.

### 9. Assignment
- Admin writes the task instructions (rich text) and can **attach any
  number of files** (PDF, Word doc, slides, images — whatever the task
  needs), each with its own label.
- Admin can optionally set a due date.
- The student reads the instructions, downloads any attached files, and
  **uploads their own file(s) back** as their submitted work. A status
  shows: Not submitted → Submitted → (if the admin reviews it) Reviewed.
- This is the one block type where the admin later sees a simple list of
  "who submitted what" per course, for their own records — it does not
  auto-grade.

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
5. **Batches / start dates:** the admin can set an optional **"Next batch
   starts on [date]"** banner on the course page (and an optional
   "Enrollment closes on [date]"), shown as a highlighted strip above the
   Enroll button. This does not have to gate enrollment (self-paced
   courses can ignore it) — it is announcement-only unless the admin later
   wants real batch/cohort enrollment limits (see open questions).

---

## PART D — Open questions (need your decision before building)

1. **Block order lock:** should a student be forced to finish block 1
   before block 2 unlocks (strict order), or can they jump around freely
   within a module? Recommendation: lock by default (matches how
   "Mark complete → Next Lesson" already works today), with free
   preview blocks always open regardless.
2. **Discussion — simple or real:** confirmed above as a simple
   read-a-prompt block for now. If you actually want students replying to
   each other (a real discussion thread), say so — it's a materially
   bigger feature (new data model, moderation, notifications) and belongs
   in its own plan, not bundled here.
3. **Assignment grading:** should the admin be able to mark an assignment
   Pass/Fail or give a score, or is "submitted / not submitted" enough for
   now? Plan above assumes just a submission list, not grading.
4. **Batches — announcement only, or real seats?** Plan above is a simple
   date banner. If you want enrollment capped per batch ("only 30 seats
   this intake") that is a bigger feature (waitlists, intake-specific
   progress) — flag if that's actually wanted.
5. **Retroactive migration:** today's existing 3 seeded courses have flat
   lessons, no modules. When this is built, do existing lessons become
   "Module 1" automatically, or do you want to manually re-organize the
   2-3 demo courses by hand afterward? Either is fine, just needs a choice.

Nothing above blocks writing the plan — these are flagged so building
doesn't start on a wrong assumption.
