# Role & Permission Audit

Checked directly against the code (not assumed). Verdict: **correct, no gaps found.**

## Roles and what each can do

| Role | Admin panel (`/admin`) access | Permissions |
|---|---|---|
| Director | Yes | Everything: articles/research/resources (create, edit, publish, delete), courses manage+publish, respond to consultations, manage certificates, settings, users, contact |
| Admin | Yes | Same as Director (full access) |
| Editor | Yes | Only content creation: articles/research/resources create+edit+publish. **No** delete, no settings, no users, no consultations/certificates management |
| Student | **No** (blocked, see below) | None — only their own dashboard data |

Source: `database/seeders/RolePermissionSeeder.php`.

## Enforcement — verified, not just seeded

1. **Admin panel gate**: `App\Models\User::canAccessPanel()` returns `hasAnyRole(['director','admin','editor']) && is_active`. A Student account gets refused before even reaching the panel — confirmed with a live test (`tests/Feature/Modules/Admin/AdminAccessTest.php`, passing).
2. **Per-action checks inside the panel**: every module has a Policy class (`ArticlePolicy`, `CoursePolicy`, `ConsultationPolicy`, `CertificatePolicy`, etc.) that Filament calls automatically for every create/edit/delete/publish button. Confirmed live in tinker that Laravel resolves each model to its policy correctly (auto-discovery works, nothing silently unprotected).
   - Example: `ArticlePolicy::delete()` requires `articles.delete`, which Editor does **not** have — so an Editor sees no delete button and a direct request would still be rejected server-side, not just hidden in the UI.
3. **A user can never see another user's private data**: the student dashboard's Consultations and Certificates lists are hard-scoped with `->where('user_id', Auth::id())` in the Livewire components — verified in `DashboardConsultationIndex.php` and `DashboardCertificateIndex.php`. There is no way to view someone else's record by guessing a URL (also policy-checked on the `view` action).

## Admin dashboard vs. student dashboard — these are two separate systems, not the same UI reused

| | Student dashboard | Admin panel |
|---|---|---|
| Route | `/{locale}/dashboard/...` | `/admin/...` |
| Layout file | `resources/views/components/layouts/dashboard.blade.php` (site sidebar: My Courses, Consultations, Certificates, Profile) | Filament's own panel (separate framework, its own sidebar: Content, People, Engagement, Settings) |
| Who can open it | Any logged-in, verified user | Only Director/Admin/Editor (Students get a 403, tested) |
| What it shows | Only the logged-in user's own courses/consultations/certificates | All records, management tools, site settings |

They share nothing but the login session — different route prefix, different layout component, different framework (Blade/Livewire vs Filament), different data scope. There is no risk of a student landing on the admin screen or an editor landing on the plain student screen by mistake.

## One thing worth deciding later (not a bug)

Director and Admin currently have **identical** permissions — there is no seeded distinction between them (e.g. only Director being allowed to change site Settings or manage Users). If that distinction matters to you, it's a one-line change in `RolePermissionSeeder.php` (split the permission list between the two `syncPermissions()` calls). Left as-is because the blueprint never asked for a difference.
