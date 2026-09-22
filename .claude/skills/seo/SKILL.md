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
