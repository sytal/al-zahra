# Assets

All assets are original work created for this project (generated SVG, no third-party files, no photos). License: project-owned, no attribution required. Palette: teal #0F766E, gold #D4A24C, slate. No external references, no filters; every SVG has a viewBox, so scale with `w-full h-auto` inside a max-width. Illustrations are transparent-background and work on light and dark; the `logo-horizontal.svg`, `logo-stacked.svg` wordmark text is dark ink (use `logo-horizontal-dark.svg` or the Blade `<x-brand.logo>` on dark).

Below `sm` (under about 400px wide) use `hero-neurolinguistics-sm.svg` instead of the full hero scene. All other illustrations are single-subject and stay legible from 240px up.

| File | Size (viewBox) | Aspect | Recommended max-width | Use |
|---|---|---|---|---|
| images/brand/logo-mark.svg | 64x64 | 1:1 | 16-128px | Mark only |
| images/brand/logo-horizontal.svg | 260x64 | 65:16 | 120-260px (min 140px for wordmark) | Header, light bg |
| images/brand/logo-horizontal-dark.svg | 260x64 | 65:16 | same | Header/footer, dark bg |
| images/brand/logo-stacked.svg | 200x170 | 20:17 | 120-240px | Auth, footer, PDF |
| images/brand/favicon-square.svg | 64x64 | 1:1 | source for PNG icons | Icon source |
| images/favicon.svg, favicon.svg (public root) | 64x64 | 1:1 | n/a | SVG favicon |
| favicon.ico | 16/32/48 | 1:1 | n/a | Legacy favicon |
| apple-touch-icon.png | 180x180 | 1:1 | n/a | iOS icon |
| icon-192.png, icon-512.png | 192, 512 | 1:1 | n/a | Manifest icons |
| site.webmanifest | n/a | n/a | n/a | PWA manifest, theme #0F766E |
| og-default.png | 1200x630 | 40:21 | n/a | Default Open Graph image, brand only, no text |
| images/patterns/islamic-geometric.svg | 80x80 tile | 1:1 | tile at 80-160px | Background at 3-6% opacity |
| images/patterns/neural-mesh.svg | 100x100 tile | 1:1 | tile at 200-400px | Background, 4-8% opacity |
| images/patterns/dots.svg | 24x24 tile | 1:1 | tile 24px | Background, 6-10% |
| images/patterns/waves.svg | 120x40 tile | 3:1 | tile 120-240px | Background, 4-8% |
| images/patterns/grid.svg | 40x40 tile | 1:1 | tile 40px | Background, 3-6% |
| images/illustrations/hero-neurolinguistics.svg | 600x480 | 5:4 | 560px (sm and up) | Home hero |
| images/illustrations/hero-neurolinguistics-sm.svg | 400x300 | 4:3 | 320px | Hero under 400px |
| images/illustrations/illus-consultation.svg | 400x300 | 4:3 | 360px | Consultation pages/CTA |
| images/illustrations/illus-courses.svg | 400x300 | 4:3 | 360px | Courses empty/section |
| images/illustrations/illus-research.svg | 400x300 | 4:3 | 360px | Research empty/section |
| images/illustrations/illus-resources.svg | 400x300 | 4:3 | 360px | Resources empty/section |
| images/illustrations/illus-articles.svg | 400x300 | 4:3 | 360px | Articles empty/section |
| images/illustrations/illus-empty.svg | 400x300 | 4:3 | 320px | Generic empty state |
| images/illustrations/illus-404.svg | 400x300 | 4:3 | 320px | 404 page |
| images/illustrations/illus-403.svg | 400x300 | 4:3 | 320px | 403 page |
| images/illustrations/illus-500.svg | 400x300 | 4:3 | 320px | 500 page |
| images/illustrations/illus-419.svg | 400x300 | 4:3 | 320px | 419 page |
| images/illustrations/illus-success.svg | 400x300 | 4:3 | 320px | Success states |
| images/illustrations/illus-auth.svg | 400x300 | 4:3 | 360px | Login/register side art |
| images/illustrations/certificate-border.svg | 1123x794 | A4 landscape | full page | Certificate frame (web and mPDF, plain paths) |
| images/director-placeholder.svg | 400x500 | 4:5 | 400px | Director portrait placeholder, replaced via admin |
| images/avatar-placeholder.svg | 100x100 | 1:1 | 32-128px | Avatar fallback |
| images/article-placeholder.svg | 600x375 | 16:10 | fluid | Article card cover fallback |
| images/course-placeholder.svg | 600x400 | 3:2 | fluid | Course card cover fallback |
| images/research-placeholder.svg | 600x375 | 16:10 | fluid | Research cover fallback |
| images/resource-placeholder.svg | 200x200 | 1:1 | fluid | Resource thumbnail fallback |

Blade: `<x-brand.logo variant="mark|horizontal|stacked" class="h-8 w-auto" :tagline="__('...')"/>` and `<x-brand.mark class="size-8"/>` use currentColor (`text-brand-primary`, gold bars via `fill-brand-secondary`), so they follow the theme. Override with `text-ink` etc. The wordmark "Al Zahra" is a brand name and not translated; the optional tagline is passed by the caller.

Notes: SVG wordmarks use font-family Georgia with serif fallback (no embedded font). og-default.png is about 217KB (only file above 200KB, brand gradient).

Third-party files: none.
