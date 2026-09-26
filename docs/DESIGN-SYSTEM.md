# Design system

Stack: Tailwind v3 + tailwindcss-rtl, Alpine (Livewire's instance), Blade. Logical utilities only (`ps-`, `pe-`, `text-start`, `start-`), never `ml-/pl-/text-left`. No raw hex outside `resources/theme/theme.json` (enforced by `tests/Feature/ThemeTest.php`).

## Theming: one file

`resources/theme/theme.json` is the single source for every color, gradient and shadow. Nothing else defines colors.

Files a person edits to re-theme: only `resources/theme/theme.json`, then `npm run build`. PHP (`config/theme.php`) reads the same file.

Structure:
- `base.palettes`: primary (teal), secondary (gold), accent (coral, complements teal and gold; use sparingly for badges and highlights), neutral (warm slate), deep (teal-black used for dark surfaces), success, warning, danger, info. 11 steps each: 50..950.
- `base.roles.light|dark|band`: semantic roles referencing `palette.step` or raw hex: page, surface, surface-raised, surface-sunken, overlay, tint, text-strong/body/muted/subtle/inverse, on-brand, on-secondary, border/-strong/-subtle, ring, link (+hover/active), brand (+hover/active), brand-secondary (+hover/active), secondary-text, accent (+hover/active), success, warning, danger, info. `band` re-scopes roles inside `.bg-section-dark` / `[data-band]`.
- `base.aliases`: legacy names (`brand-primary` -> brand, `ink` -> text-strong).
- `base.gradients.light|dark`, `base.shadows.light|dark`: strings with `{primary.700}`, `{primary.500/16}` (alpha percent), `{role:page}`, `{#FFFFFF/45}`.
- `base.status.*` (published, draft, pending, archived, rejected, info, featured) and `base.chart.*` (8 series).
- `presets`: `default` (empty), `sapphire`, `rose`; each overrides any part of `base`. `activePreset` picks the build-time preset. At runtime `<html data-theme="rose">` switches palettes (preset roles too when defined) without rebuilding.

How to:
- Change a color: edit the hex in `base.palettes.<name>.<step>`.
- Add a palette step: not needed (11 fixed). Add a palette: add it under `palettes`, reference it in roles.
- Add a gradient: add a key under `gradients.light` and `.dark`; use `var(--gradient-<key>)` or `bg-gradient-<key>`.
- Switch preset: set `activePreset`, run `npm run build`. Verify contrast: `node resources/theme/contrast.mjs` (AA over every preset and mode).
- PHP: `config('theme.palettes.primary.600')`, `config('theme.roles.dark.brand')`, `config('theme.filament.primary')` (Filament Color array of "r, g, b"). Mail CSS and the mPDF certificate still hold hex (whitelisted in ThemeTest) and must be migrated by their owners.

Tailwind names: `primary|secondary|accent|neutral|deep|success|warning|danger|info` (-50..950, DEFAULT = role), roles `bg-page bg-surface bg-surface-raised bg-surface-sunken bg-tint bg-overlay text-strong text-body text-muted text-subtle text-inverse text-on-brand text-on-secondary text-link text-secondary-text`, borders `border` (default) `border-strong border-subtle`, `ring` default, legacy `brand-primary(-N) brand-secondary(-N) ink surface`, `bg-status-published-bg text-status-published-text`, `bg-chart-1..8`, shadows `shadow-soft|lift|float|glow|gold`. Opacity modifiers work (`bg-brand-primary/20`). CSS vars: `--c-<name>` (RGB triplet), `--color-<name>` (rgb()), `--gradient-<key>`, `--shadow-<key>`, `--border-color`.

Rules: text on brand backgrounds uses `text-on-brand`; on gold `text-on-secondary`; gold as text only via `text-secondary-text`; body text opacity >= /70. Palette steps are fixed across modes; use roles (or `dark:`) for anything that must adapt.

## Fonts (self-hosted, @fontsource, OFL)
Display serif: Playfair Display 600/700. Sans: Figtree 400-700. Urdu: Noto Naskh Arabic 400/600/700. Persian: Vazirmatn 400/600/700. Hindi: Noto Sans Devanagari 400/600/700. Only latin/arabic/devanagari subsets are imported and each face has unicode-range, so a Latin page loads no Arabic or Devanagari file. Stacks switch per `html[lang]`; `font-sans` and `font-display` read `--font-sans/--font-display`. Line height: en 1.65, hi 1.75, fa 1.9, ur 2.0 (headings 1.15/1.4/1.6/1.7). Letter-spacing and uppercase are neutralised for RTL and Hindi.

## Layout and responsive
Breakpoints: base, `xs` 420, sm 640, md 768, lg 1024, xl 1280, 2xl 1536. Verify 320 to 1536 plus 667x375.
- `.container-page` (max 72rem, fluid gutter), `.container-narrow`, `.section` (fluid 48-96px), `.section-tight`.
- Type (fluid clamp): `.heading-display .heading-1 .heading-2 .heading-3 .heading-4 .eyebrow .lead`, `.text-gradient-brand`, `.numeral-display`, `.pull-quote` (with `<cite>`), `.prose-content` (articles: headings, lists, links, blockquote, code, pre (ltr), images, tables, RTL-safe).
- Helpers: `.tap-target` (44px), `.tap-expand` (bigger hit area), `.scroll-x`, `.table-scroll` (wrap a table), `.table-cards` (table becomes cards below md; give each `td` `data-label`), `.pt-safe .pb-safe .ps-safe .pe-safe .bottom-safe` (safe-area insets; `--safe-pad` adds extra padding), `.h-dvh-full .min-h-dvh-full .max-h-dvh-full`, `.break-anywhere`, `.no-scrollbar`, `overscroll-contain` (Tailwind).
- Z-index: `z-raised(10) sticky(30) header(40) dropdown(50) overlay(60) modal(70) toast(80) top(90)`. Radius: `rounded-card`, `rounded-panel`. Width: `max-w-page|narrow|prose`. Motion: `duration-fast|base|slow`, `ease-enter|exit|spring`.

## Surfaces and effects
`.card-surface` (raised, border, soft shadow), `.card-hover` (lift; hover-gated), `.glass` (blur, opaque fallback), `.gradient-border`, `.gradient-border-gold`, `.gradient-border-animated`, `.img-zoom` (wrap an img; also inside `.group`), `.spotlight` (cursor glow, Alpine), `.tilt` (Alpine), `.shimmer-sweep` (hover) / `.shimmer-sweep-auto` (once on load), `.magnetic` (Alpine), `.skeleton` (shimmer), `.marquee > .marquee-track` (duplicate items once; pauses on hover; flips for RTL; `--marquee-duration`), `.focus-ring`, `.snap-track` (carousel), `.stagger` (children fade up, 60ms steps), `.reveal` with Alpine `reveal`.
Decoration: `.bg-pattern-islamic|neural|dots|waves|grid` (uses public/images/patterns/*.svg, tolerant of missing files, opacity `--pattern-opacity`), `.bg-hero-gradient`, `.divider-ornament`, `.grain`, `.gold-thread` / `.gold-thread-v`, `.light-rays`, `.glow-teal|glow-gold` (aliases `.glow-primary|.glow-secondary`; absolutely positioned inside a `relative isolate overflow-hidden` parent; `--glow-size`; place with `inset-inline-*`).
Section backgrounds: `.bg-section-light|tinted|sunken|pattern|dark` (dark = deep-teal band; children adapt automatically through the `band` roles).
Zahra ornament: `.star-mark` (inline star, `currentColor`-free; set size with font-size), `.star-bullet` (on `ul`), `.star-divider` (put a `.star-mark` inside), `.star-loader`, `.star-burst` (success burst), `.bento` grid with `.bento-wide .bento-tall .bento-hero`, `.link-underline` (animated underline; RTL aware; static on touch), `.confetti-piece` (used by the confetti component).
Keyframe utilities: `animate-float|fade-up|fade-in|scale-in|pulse-soft`. `prefers-reduced-motion` turns everything static and content is never hidden without JS (reveal only hides after Alpine adds `.reveal-ready`).

## Alpine components (registered on `alpine:init`, verified to fire with Livewire's bundled Alpine)
- `counter({ to, duration, locale, decimals, prefix, suffix })`: `<span x-data="counter({to:1200})" x-text="display">1,200</span>`.
- `spotlight`: `<div class="spotlight" x-data="spotlight">`.
- `tilt({max})`: `<div class="tilt" x-data="tilt">`.
- `magnetic({strength, radius})`: `<a class="magnetic" x-data="magnetic">`.
- `reveal({delay})`: `<div class="reveal" x-data="reveal({delay:80})">` (not on Livewire component roots).
- `tabs({initial})`: `<div x-data="tabs"><div role="tablist"><button x-bind="tab('a')">..</button></div><div x-bind="panel('a')">..</div></div>` (arrow keys, Home/End, RTL aware).
- `carousel({autoplay})`: `x-ref="track"` on a `.snap-track`; `@click="prev"`, `@click="next"`, `canPrev`, `canNext`.
- `stickyHeader({offset})` exposes `scrolled`; `backToTop({threshold})` exposes `visible`, `top()`.
- `copyToClipboard({text, message})`: `@click="copy"`, `copied`.
- `toast`: `<div x-data="toast" @toast.window="add($event.detail)">` with `items`, `dismiss(id)`; fire with `window.toast('Saved','success')` or Livewire `$this->dispatch('toast', message: '...', type: 'success')`.
- `starBurst`: `:class="{'is-bursting': bursting}" @click="fire"` on a `.star-burst`.
- `confetti({count})`: `@click="fire($event)"` (pass `{x,y}` or nothing) or `@confetti.window="fire()"`.
Pointer effects (spotlight, tilt, magnetic) run only with `(hover:hover) and (pointer:fine)` and no reduced motion.

## Motion rules
150/250/400ms; ease-enter for entrances, ease-exit for exits; animate transform and opacity only; hover effects inside `@media (hover:hover)`; press feedback `active:scale-[0.98]`; one showpiece effect per view; never hide content if JS fails.

## Do / Don't
Do: tokens and roles, logical utilities, `<x-icon>`, `text-on-brand` on brand fills, 44px targets, `min-w-0` + `break-words` for long strings. Don't: raw hex, `ml-/pl-`, hover-only actions, ink opacity below /70, `data-aos` on Livewire component roots, starting Alpine yourself.
