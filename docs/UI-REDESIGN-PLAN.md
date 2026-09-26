# UI Redesign Plan: Al Zahra Institute

Status: APPROVED (owner: demo testimonials, placeholder portrait, go). Execution in progress on this branch.
Branch: `ui-redesign` (from `all-merged`). Goal: the whole site looks and behaves like a premium educational institute and clinician website: credible, calm, warm, accessible, fast, in 5 languages (ur/fa RTL), light and dark.

## 0. Ground rules and decisions

Hard constraints (from docs/CLAUDE.md and Known bug patterns): Tailwind v3 + tailwindcss-rtl, tokens not hex, logical utilities only, i18n in the same change for en/ur/hi/fa/ur-roman, Alpine only via Livewire's instance, no `data-aos` on Livewire roots, `text-on-brand` on brand backgrounds, ink text >= /70, `npm run build` before every browser check, Part G out of scope (no payments, comments, forum).

Decisions taken (change only if you object):
| # | Decision | Reason |
|---|---|---|
| D1 | Trust sections use real data where it exists (DB counts, Director credentials/interests). Testimonials are seeded as DEMO content (Settings key `testimonials`, each item flagged `demo: true`, shown with a small visible "Demo" label, hidden automatically when the list is empty) so the design is complete; the owner replaces or removes them before launch | approved by owner; keeps the block honest |
| D2 | FAQ, "how a consultation works" steps and service cards are static translated content in lang files (editable by devs), not new DB tables | Part G scope: no new modules |
| D3 | Original SVG art (logo, patterns, illustrations, empty/error states, portrait placeholder). Stock photos only if CC0/Unsplash/Pexels and recorded in docs/ASSETS.md | no licensing risk, works in dark mode |
| D4 | Fonts self-hosted via @fontsource (no Google CDN): serif display + sans body + Urdu Naskh/Nastaliq-grade + Persian + Devanagari | privacy, speed, offline |
| D5 | Motion is purposeful and reduced-motion safe; cool effects (spotlight glow, tilt, gradient borders, counters, marquee) are opt-in utilities used on a few showpiece cards only | performance and accessibility |
| D6 | Filament admin gets brand theming (colors, logo, fonts, login page), not a rebuild | admin is for staff |
| D7 | Every agent owns disjoint files; foundation lands first, then pages run in parallel | no conflicts |

## 1. Design language

- Color: brand teal #0F766E (primary), warm gold #D4A24C (accent), slate ink. Add tonal scales (50-900), surface levels (base, raised, sunken), gradient tokens, dark equivalents. All AA.
- Type: display serif for h1-h3 (elegant, academic), sans body 16-18px, tight tracking on display, script-aware line-heights (ur/fa 1.9+, hi 1.7). Scale: display 56/48/40, h1 40, h2 32, h3 24, lead 20, body 16, small 14.
- Shape: radius xl/2xl cards, pill badges; layered soft shadows (sm/md/lg/glow); 1px hairline borders at ink/10.
- Space: 8pt grid; section rhythm 96/72/56px (lg/md/sm); container 1152 (max-w-6xl) with 16/24/32 gutters.
- Decor: subtle Islamic-geometric + neural-mesh SVG patterns (2-6% opacity), radial teal glow blobs, divider ornament. No stock-photo clutter.
- Imagery ratios: cards 16:10, hero 4:3, avatars 1:1, OG 1.91:1. Always width/height, lazy, alt, fallback.
- Motion tokens: 150/250/400ms, ease-out for enter, ease-in for exit, spring-lite for hover. Reveal: fade-up 16px, stagger 60ms. Reduced-motion: everything static.
- Tone: reassuring, plain language, clear primary CTA "Book a consultation" and secondary "Browse courses".

## 1b. New look, not a refresh (owner requirement)

The site must look completely new and unique; old structure, card and hero looks are not preserved (data, i18n keys and wire bindings are). Signature concept "Zahra: radiance": the logo's 8-point star as ornament (dividers, bullets, loader, watermark), teal and gold light glows and light-rays, gold hairline threads, editorial oversized serif numerals and pull-quotes, asymmetric overlapping compositions, bento-grid sections, alternating section backgrounds (light, tinted, sunken, dark teal, pattern), layered cards with gradient borders and pattern fills, content-type-specific card personalities (article editorial, course with level meter, research document-like, resource tactile file card, consultation chat-like), one memorable hero moment per page, micro-interactions everywhere (magnetic primary button, star-burst on success, count-up, animated underlines, confetti-lite on course completion, shimmer sweep), first-class dark mode. All motion reduced-motion safe and hover-gated on touch.

## 1c. One-file theming (owner requirement, top priority)

Single source: resources/theme/theme.json (editable by a non-developer): 11-step palettes (primary teal, secondary gold, accent, neutral, success, warning, danger, info), semantic roles for LIGHT and DARK separately (backgrounds, surfaces, text, borders, rings, links, hover/active), gradients for light and dark (hero, section tint, card highlight, brand text, CTA band, glows, light rays, shimmer), shadows and glow colors, status/badge colors, and named presets (default, sapphire, rose) selectable with one key. tailwind.config.js reads it and emits all CSS variables (RGB triplets so opacity modifiers work); config/theme.php reads the same file for Filament colors, the mail theme and the mPDF certificate. Old token names stay as aliases. A Pest test fails on any raw hex outside the theme file and a small documented whitelist. Static SVG illustration files cannot read CSS variables: logos and ornaments used in pages are inline Blade partials that do use the tokens; illustration files are two-tone and documented.

## 2. Design system deliverables (Phase A, foundation)

| Item | Detail |
|---|---|
| Tokens | tailwind.config.js + app.css: color scales, surfaces, gradients, shadows, radii, z-index, container, dark overrides; all existing token names keep working |
| Fonts | @fontsource packages verified with npm view; per-locale stacks via html[lang]; unicode-range subsets; size budget reported |
| Utilities | .container-page .section .eyebrow .heading-display .lead .prose-content .glass .card-surface .card-hover .img-zoom .gradient-border .spotlight .tilt .bg-pattern-* .bg-hero-gradient .text-gradient-brand .divider-ornament .skeleton .marquee .focus-ring + keyframes |
| Alpine behaviors (alpine:init) | counter (count-up on view), spotlight, tilt, reveal fallback, tabs, carousel (scroll-snap, RTL aware), stickyHeader, backToTop, copy-to-clipboard, toast |
| Assets | logo (mark, horizontal, stacked, light/dark), favicon set, manifest, og-default, 5 patterns, ~14 illustrations, director placeholder, 4 upgraded content placeholders, certificate border |
| Docs | docs/DESIGN-SYSTEM.md (reference), docs/ASSETS.md, docs/THIRD-PARTY.md |
| Gate | build clean, sample page verified en/ur, light/dark, 375/1280, JS/CSS KB reported |

## 3. Components (Phase B1). Every Part B component is restyled; new ones added

Existing (restyle, keep props): button (5 variants x 3 sizes + icon-only + loading, press feedback, icon/label inline bug fixed), icon, card (padding, hoverable, media slot), badge (+ dot, outline, sizes), input/textarea/select/checkbox/radio (floating or top labels, hint, error, prefix/suffix icon, focus glow), forms/_field-wrapper, table (zebra, sticky header, responsive card-stack on mobile), modal (focus trap, slide-up on mobile), dropdown, accordion (smooth, icon rotate, RTL), pagination (themed, compact on mobile), seo, language-switcher (flag-less, native names, popover), director-profile (compact + full), instructor-card, detail-layout (sticky sidebar TOC/share, reading progress bar), footer (mega), empty-state (illustration), breadcrumbs (truncate), progress-bar (animated, ring variant), newsletter-form, share-buttons (copy link + native share), stat-card (counter), theme-toggle, theme-init-script, loading-bar, nav-link/responsive-nav-link, auth-session-status, application-logo -> brand logo.
New: section-heading (eyebrow+title+lead+link), hero (variants: home, inner-page banner), feature-card, service-card, stat-counter row, cta-band, timeline/steps, faq (accordion group), testimonial-card (data-gated), publication-marquee, course-card, article-card, research-card, resource-card (all with hover lift, badges, skeleton), filter-bar (chips, search, clear), tabs, toast/flash, alert, avatar (+stack), tooltip, skeleton set, back-to-top, cookie/notice slot (none unless needed), pdf-viewer link, social-links, contact-info-card, opening-hours card.

## 4. Layouts and shells (Phase B2)

| Layout | Plan |
|---|---|
| public | top info bar (phone, email, hours, language + theme), sticky blurred header with logo, nav (About, Articles, Courses, Research, Resources, Contact), primary CTA "Book a consultation", auth buttons or user menu (Dashboard, Logout), mobile drawer with focus trap, skip link, back-to-top, mega footer (brand + blurb, 3 link columns, newsletter, contacts, social, language switcher, legal row), page-transition bar |
| minimal (auth, signed view, confirmations) | split screen: illustration + trust points on one side, form card on the other (stacks on mobile), logo, language + theme switchers |
| dashboard | collapsible sidebar (icons + labels, active state, user card, logout), top bar (search-less, notifications placeholder none, theme, language, avatar menu), mobile bottom nav or drawer, breadcrumbs, simplified footer |
| error | minimal variant with illustration per code (404/403/419/500), search-free, "Back to Home", 419 refresh button |
| print | article/research/course pages print-friendly (hide chrome) |
| email | vendor/mail html theme + emails/** re-skinned to brand (logo, teal button, footer, RTL-aware) |
| PDF certificate | mPDF template redesign (ornamental border, seal, signature block, QR to verify URL if library already present, RTL-safe) |
| Filament | brand colors, logo, favicon, font, dark mode, custom login page, dashboard widgets card polish |

## 5. Pages (Phase C). Each page lists sections, states, motion, data

States for every page: loading (skeleton), empty (illustration + CTA), error (retry), success, validation, no-JS fallback, 375/768/1280, RTL, dark.

**C1 Home** (10 sections): 1 Hero: display headline (site_tagline), lead, 2 CTAs, trust chips (credentials from Director), hero illustration + floating glass cards (real counts), pattern + glow, parallax-lite. 2 Trust bar: animated counters (articles, courses, papers, learners; real DB counts, cached). 3 Director intro: portrait/placeholder, name, credentials, interest pills, "Read more". 4 Services: 3 cards (Free question, Book consultation, Learn with courses) with icons and CTAs. 5 Featured courses: carousel/grid of course-cards. 6 Latest articles: 1 large + 2 small editorial layout. 7 Research highlights: horizontal cards + publication marquee. 8 How consultation works: 4-step timeline. 9 Testimonials (hidden unless data) + FAQ accordion (6 items) . 10 CTA band + newsletter. Motion: staggered reveal, counters, card lift, hero float.
**C2 About**: cover hero, full director profile (bio, credentials timeline, interests pills, publications count), mission/vision cards, values grid, timeline, resources CTA, social row, CTA band.
**C3/C5/C7/C9 Lists** (articles, research, resources, courses): inner-page hero with illustration, sticky filter bar (category chips, search, extra filters for courses: audience, level, free/paid), result count, animated grid with skeleton while filtering, pagination, empty state. Card variants per type. Course list adds "X learners" and level badges.
**C4 Article detail**: breadcrumbs, title block (category, author avatar, date, reading time), hero image, reading-progress bar, prose-content, sticky share + TOC (lg), tags, author box, related articles, next/prev, print styles.
**C6 Research detail**: title block + meta, boxed Question/Findings/Significance cards with icons, methodology accordion, "Read full paper" primary button, cite/copy citation, share, related.
**C8 Resource detail**: cover, type/free badges, description, disclaimer callout, download button states (free/paid/disabled tooltip), related.
**C10 Course detail**: banner hero with cover, level/audience/price badges, sticky enroll card (lg) with CTA states (guest/enroll/continue), outcomes checklist, curriculum accordion with lock/preview/duration, instructor card, FAQ, related courses.
**C17 Consultation**: two-column: form card (segmented type toggle, stepper-lite, datetime picker styled, char counter, privacy note) + side panel (how it works, response time, reassurance, illustration); success state with illustration; validation states.
**C18 Contact**: form card + info cards (email, phone, hours, social), map-less location card (address text from Settings), success state.
**C19 Certificate verify**: centered card, code input with format hint, valid result as seal card (first name + last initial, course, date), invalid neutral state, illustration.
**C20 Signed view / newsletter confirmed**: minimal layout, Q and A conversation bubbles, CTA; confirmed page with success illustration.
**C21 Auth** (login, register, forgot, reset, confirm, verify-email): split-screen minimal layout, show/hide password, strength hint (register), social-proof column, error and status alerts, resend timer feel, language switcher.
**C22 Errors**: illustration per code, friendly copy, actions.
**C11 Dashboard home**: welcome banner (greeting by time, avatar), stat cards with counters, continue-learning cards with progress ring, recent consultations list, quick actions, empty states.
**C12 My courses**: tabs with counts, course cards with progress ring and Continue/Review, certificate icon.
**C13 Lesson viewer**: focus-mode two-column, sticky curriculum with progress, video/text area with prose, attachments, bottom sticky action bar, keyboard shortcuts hint, mobile curriculum drawer.
**C14 Consultations**: responsive table -> cards on mobile, status timeline in modal, filters by status.
**C15 Certificates**: certificate cards with ornament thumbnail, verify code copy button, download.
**C16 Profile**: sectioned settings page (photo cropper-lite preview, info, preferences, password), toasts, danger-zone none.
**Admin (Filament)**: theme + login + widgets polish.

## 6. Cross-cutting quality gates (applied to every phase)

Accessibility (WCAG AA: contrast, focus rings, 44px targets, aria, landmarks, reduced motion, no color-only meaning, keyboard menus). Performance (LCP < 2.5s local, CSS < 80KB gz, JS < 60KB gz, fonts subset, lazy images, no layout shift). SEO (keep $seo, structured data intact, headings order). i18n (all strings in 5 locales, RTL mirrored icons/arrows, numerals per locale where sensible, dates translatedFormat). Dark mode parity. Tests (Pest stays green; add tests where PHP changes). Real-browser QA with the CDP helper: en+ur (spot fa, hi), 375/768/1280, light/dark, zero overflow and console errors, screenshots READ by the agent.

## 6b. Responsive standard (owner requirement) and agent/skill usage

Mobile-first. Every page and component is verified at 320, 375, 414, 768, 1024, 1280, 1536 and landscape phone 667x375, in en and ur (RTL), light and dark, plus 200% zoom/large text, long translated strings, 1 and 20 item lists, empty/loading/error states, touch (hover effects gated by hover:hover), 44px targets, safe-area insets, no horizontal scroll, tables to cards on mobile, modals/drawers that scroll on short screens, fluid display typography (clamp), correct inputmode/autocomplete. A page with any failing cell is not done. Full text lives in the agent brief and is repeated in DESIGN-SYSTEM.md.

Skills and agents are used wherever they apply: taste-skill, redesign-skill, impeccable (audit, polish, layout, typeset, colorize, adapt, harden, delight, optimize), emil-design-eng, animate, find-animation-opportunities, review-animations, output-skill, token-reducer, and the project skills (i18n, livewire-components, forms, seo, security, ...). Project agents: frontend-builder for Blade/Livewire UI work, i18n-agent for string audits, seo-agent for head/schema checks, security-auditor for forms/auth/downloads, admin-builder for the Filament theme, test-writer + qa-runner for tests, architect for structure questions.

## 7. Execution plan

| Phase | Agents (parallel) | Owns | Gate |
|---|---|---|---|
| A foundation | A1 tokens/fonts/CSS/JS/docs; A2 assets (SVG, logo, favicon, patterns) | tailwind.config.js, app.css, app.js, package*.json, public/images/**, docs/DESIGN-SYSTEM.md, ASSETS.md, THIRD-PARTY.md | sample page verified; docs written |
| B1 components | B1a existing-component restyle; B1b new components (cards, hero, sections) | resources/views/components/** split by name lists | component gallery verified (temporary route, not committed) |
| B2 shells | B2a public layout + footer; B2b minimal + dashboard layouts + error layout; B2c emails + PDF + Filament theme | layouts, emails, certificate pdf, AdminPanelProvider (theme only) | header/footer/menu/drawer verified |
| C pages | C1 Home+About; C2 lists (articles, research, resources); C3 details (article, research, resource); C4 courses list+detail; C5 consultation, contact, certificate verify, signed view, newsletter; C6 auth + error pages; C7 dashboard (all 6 pages) | disjoint view + lang files, Livewire PHP only for extra view data | per-page matrix |
| D integration | D1 real-browser matrix over all pages; D2 accessibility + performance audit (impeccable audit); D3 i18n completeness + RTL audit; D4 tests + fixes | tests, fixes in owners' files | zero known issues |
| E finish | me | docs/memory, migrate:fresh --seed, full pest, final smoke, commit | branch ui-redesign ready |

Skills used: taste-skill and redesign-skill (direction), impeccable audit/polish/layout/typeset/colorize/delight, emil-design-eng + animate + find-animation-opportunities + review-animations (motion), output-skill (complete files), token-reducer for large reads. Internet: MIT/Apache/CC0 blocks (HyperUI, Flowbite, Tailwind examples) adapted, licenses logged.

Definition of done: every page and state in section 5 built and verified in the matrix; no English hardcoded; tests green; docs updated; nothing merged to main (branch ready for review).

## 8. Risks

Big diff (mitigated by phases and small commits); font weight/size (subsetting, budget); RTL regressions (RTL screenshots are a gate); animation jank on low-end phones (opt-in effects, reduced-motion); design drift between agents (single DESIGN-SYSTEM.md contract); PDF limits (mPDF CSS subset); Filament theming limits (colors/brand only).
