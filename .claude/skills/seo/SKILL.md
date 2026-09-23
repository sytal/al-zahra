---
name: seo
description: How to add SEO meta tags, sitemap entries, and structured data for a public page. Use whenever a new public-facing page or content type is added.
---

# SEO standard (docs/CLAUDE.md Section 12)

- `spatie/laravel-sitemap` auto-generates `/sitemap.xml` plus segmented
  sitemaps, via `php artisan sitemap:generate` on Laravel's scheduler
  (daily 02:00 per `docs/PROJECT-BLUEPRINT.md` Part F).
- Every public page defines `title`, `meta_description`, `canonical_url`,
  `og_image` via the shared `<x-seo :title="" :description="" :image="" />`
  component placed once in `<head>` of `layouts/public.blade.php`, fed
  per-page via a `$seo` array from the controller/Livewire component —
  never scattered inline `<meta>` tags.
- JSON-LD structured data via `app/Support/SeoSchema.php` static builders
  (`::article()`, `::course()`, `::person()`, `::organization()`,
  `::breadcrumb()`) — reused, not hand-written per page.
- Multi-language SEO: `hreflang` alternates auto-generated inside
  `<x-seo>`, looping over the 5 locales — do not duplicate per page.
- **Livewire full-page components need the same `$seo` array as classic
  Controller pages** — build it inside `render()` (or a dedicated method)
  and pass it to the view alongside the rest of the data. A Livewire
  `#[Title]` attribute only sets `<title>`; it is NOT a substitute for
  `$seo` (meta description, OG tags, canonical, hreflang, schema). Every
  public-facing page gets full SEO regardless of whether it's rendered
  via a Controller or a Livewire component.
