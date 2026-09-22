---
name: livewire-components
description: How to write Blade/Livewire components for this project, including navigation and animation rules. Use whenever building any view or Livewire component.
---

# Frontend standards (docs/CLAUDE.md Sections 8-10, 26)

- Reuse shared Blade components (`x-button`, `x-icon`, `x-card`, `x-input`,
  etc.) — see `docs/PROJECT-BLUEPRINT.md` Part B for the full inventory and
  props. Never hardcode a hex color; use the CSS variables in
  `resources/css/app.css`.
- Icons only via Heroicons through the `x-icon` wrapper — never Lucide,
  never a raw `<svg>` outside that wrapper.
- Livewire component naming: PascalCase class, kebab-case tag
  (`<livewire:articles.article-card />`).
- **No-reload navigation**: every internal `<a>` uses `wire:navigate`.
  Forms use `wire:submit` with loading state via the shared button
  component's `wire:loading` slot. Trivial UI state (dropdown open/close)
  is Alpine-only, no Livewire round-trip.
- **Animations**: AOS for scroll-reveal (`data-aos="fade-up"` attributes,
  initialized once in the main layout, no per-page JS). Micro-interactions
  via Tailwind `transition`/`duration-200`/`ease-in-out` only. Never add an
  animation library beyond AOS + Tailwind + Alpine unless a specific
  interaction genuinely needs GSAP core (free only).
- Public pages: content-first, one hero max, AOS fade-up on section entry.
- Dashboard: persistent sidebar (`wire:navigate`), card-based widgets,
  shared `<x-table>` for lists.
- Detail pages use the shared `<x-detail-layout>` component.
- Responsive: mobile-first, test at 375px/768px/1280px; sidebar collapses
  to bottom-sheet/hamburger below `md`.

Exact page/section layout for every page is in `docs/PROJECT-BLUEPRINT.md`
Part C — follow it literally, do not invent structure.
